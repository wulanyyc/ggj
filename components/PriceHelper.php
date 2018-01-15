<?php
namespace app\components;

use Yii;
use yii\base\Component;
use app\modules\product\models\ProductList;
use app\modules\product\models\Coupon;
use app\modules\product\models\CouponUse;
use app\models\ProductOrder;
use app\models\ProductCart;
use app\models\Customer;
use app\models\CustomerMoneyList;
use app\models\ProductPackage;
use app\models\Pay;
use app\components\AlipayHelper;
use app\components\WxpayHelper;
use app\components\SiteHelper;

/**
 * 基础帮助类
 * @author yangyuncai
 *
 */
class PriceHelper extends Component {
    public static $precison = 1;

    /**
     * $id 产品id
     * $type 订购类型  1: 普通  2: 预订
     */
    public static function getProductPrice($id, $type = 2) {
        $data = ProductList::find()->where(['id' => $id])->select('price, fresh_percent')->asArray()->one();

        $price = $data['price'];
        if (empty($price)) {
            return 0;
        }

        // 天天特价
        $price = self::getDayPromotion($id, $price);

        // 店铺特价
        $price = self::getNewPromotion($id, $price);

        if ($type == 1) {
            return round(Yii::$app->params['buyDiscount'] * $price * $data['fresh_percent'] / 100, self::$precison);
        }

        if ($type == 2) {
            return round(Yii::$app->params['bookingDiscount'] * $price, self::$precison);
        }

        return $price;
    }

    public static function getNewPromotion($id, $price) {
        $promotions = Yii::$app->params['new_promotion'];
        if ($id == $promotions['id']) {
            return $promotions['price'];
        }

        return $price;
    }

    public static function getDayPromotion($id, $price) {
        $promotions = Yii::$app->params['day_promotion'];

        $dayofweek = date('w', time());
        if ($dayofweek == 0) {
            $dayofweek = 7;
        }

        if ($promotions[$dayofweek]['id'] == $id) {
            $price = round($price * $promotions[$dayofweek]['discount'], 2);
        }

        return $price;
    }

    public static function createCoupon($couponid, $openid = '') {
        $today = date('Ymd', time());
        $info = Coupon::find()->where(['id' => $couponid])->asArray()->one();
        if (empty($info)) return 0;

        if ($today > $info['end_date']) {
            return 0;
        }

        $type = $info['type'];

        if (empty($openid)) {
            $openid = isset($_COOKIE['openid']) ? $_COOKIE['openid'] : '';
        }

        if ($type == 1) {
            $exsit = CouponUse::find()->where(['cid' => $couponid])->count();
            if ($exsit) {
                return 0;
            } else {
                $ar = new CouponUse();
                $ar->cid = $couponid;
                $ar->customer_id = SiteHelper::getCustomerId();
                $ar->openid = $openid;
                $ar->save();

                return $ar->id;
            }
        } else {
            $ar = new CouponUse();
            $ar->cid = $couponid;
            $ar->customer_id = SiteHelper::getCustomerId();
            $ar->openid = $openid;
            $ar->save();

            return $ar->id;
        }
    }

    public static function getValidCoupon() {
        $customer_id = SiteHelper::getCustomerId();

        $currentDate = date('Ymd', time());

        $data = CouponUse::find()->where(['customer_id' => $customer_id, 'use_status' => 1])->asArray()->all();

        foreach ($data as $key => $item) {
            $endDate = Coupon::find()->where(['id' => $item['cid']])->select('end_date')->scalar();
            if ($currentDate > $endDate) {
                unset($data[$key]);
            }
        }

        return $data;
    }

    public static function calculateCounponFee($ids) {
        if (empty($ids)) return 0;

        $data = self::getValidCoupon();
        if (empty($data)) return 0;

        $coupons = explode(',', $ids);
        $fee = 0;
        foreach($data as $item) {
            foreach($coupons as $id) {
                if ($item['cid'] == $id) {
                    $money = Coupon::find()->select('money')->where(['id' => $id])->scalar();
                    $fee += $item['money'];
                }
            }
        }

        return $fee;
    }

    // 计算邮费 $type: 运输类型
    public static function calculateExpressFee($expressRule, $type, $productPrice) {
        if ($expressRule == 2) {
            return 0;
        }

        if ($type == 1) {
            if ($productPrice < Yii::$app->params['buyGod']) {
                return Yii::$app->params['expressFee'];
            } else {
                return 0;
            }
        }

        if ($type == 2) {
            if ($productPrice < Yii::$app->params['bookingGod']) {
                return Yii::$app->params['expressFee'];
            } else {
                return 0;
            }
        }
    }

    // 计算产品价格
    public static function calculateProductPrice($cartid) {
        $data = ProductCart::find()->where(['id' => $cartid])->select('cart,order_type')->asArray()->one();
        // 计算产品价格
        $carts = json_decode($data['cart'], true);

        $productPrice = 0;
        foreach($carts as $item) {
            $pid = $item['id'];
            $productPrice += $item['num'] * PriceHelper::getProductPrice($pid, $data['order_type']);
        }

        $productPrice = round($productPrice, 2);

        return $productPrice;
    }

    // 计算订单价格
    public static function calculateOrderPrice($orderid) {
        $data = ProductOrder::find()->where(['id' => $orderid])->asArray()->one();

        $productPrice = self::calculateProductPrice($data['cart_id']);
        $expressFee   = self::calculateExpressFee($data['express_rule'], $data['order_type'], $productPrice);
        $couponFee    = self::calculateCounponFee($data['coupon_ids']);
        $discountFee  = $data['discount_fee'];

        return round($productPrice + $expressFee - $couponFee - $discountFee, 2);
    }

    /**
     * 调整钱包余额
     * type: plus, minus
     */
    public static function adjustWallet($cid, $money, $type = 'minus', $reason = '') {
        $wallet  = Customer::find()->where(['id' => $cid])->select('money')->scalar();

        if ($type == 'minus') {
            $newmoney = $wallet - $money;
        }

        if ($type == 'plus') {
            $newmoney = $wallet + $money;
        }

        $cmlar = new CustomerMoneyList();
        $cmlar->money = $money;
        $cmlar->operator = $type;
        $cmlar->reason = $reason;
        $cmlar->cid = $cid;
        $cmlar->save();

        $up = Customer::findOne($cid);
        $up->money = $newmoney;
        $up->save();
    }

    /**
     * 朋友钱包奖励
     * type: plus, minus
     */
    public static function addFriendWallet($money, $phone, $reason = '', $operator = 'plus') {
        $data  = Customer::find()->where(['phone' => $phone])->select('id, money')->asArray()->one();

        if (empty($data)) {
            $add = new Customer();
            $add->phone = $phone;
            $add->status = 2;
            $add->save();
            $data  = Customer::find()->where(['phone' => $phone])->select('id, money')->asArray()->one();
        }

        if ($operator == 'plus') {
            $newmoney = $data['money'] + $money;
        } else {
            $newmoney = $data['money'] - $money;
        }

        $cmlar = new CustomerMoneyList();
        $cmlar->money = $money;
        $cmlar->operator = $operator;
        $cmlar->reason = $reason;
        $cmlar->cid = $data['id'];
        $cmlar->save();

        $up = Customer::findOne($data['id']);
        $up->money = $newmoney;
        $up->save();
    }

    public static function getUpdateCart($cid) {
        $data = ProductCart::find()->where(['id' => $cid])->asArray()->one();
        $cart = json_decode($data['cart'], true);

        foreach($cart as $key => $value) {
            $cart[$key]['price'] = PriceHelper::getProductPrice($value['id'], $data['order_type']);
        }

        return $cart;
    }

    /**
     * 调整钱包积分
     * type: plus, minus
     */
    public static function adjustScore($score, $type = 'minus') {
        $cid = SiteHelper::getCustomerId();
        $currentScore = Customer::find()->where(['id' => $cid])->select('score')->scalar();

        if ($type == 'minus') {
            $newscore = $currentScore - $score;
        }

        if ($type == 'plus') {
            $newscore = $currentScore + $score;
        }

        $up = Customer::findOne($cid);
        $up->score = $newscore;
        $up->save();

        return true;
    }

    public static function updatePackagePrice($id) {
        $packageDiscount = 0.9;

        $data = ProductPackage::find()->where(['product_package_id' => $id])->asArray()->all();

        if (empty($data)) return;
        if (ProductList::find()->where(['id' => $id])->count() == 0) return ;
        $price = 0;

        foreach($data as $item) {
            $productPrice = ProductList::find()->select('price')->where(['id' => $item['product_id']])->scalar();
            $price += $productPrice * $item['num'];
        }

        $price = round($price * $packageDiscount);

        $up = ProductList::findOne($id);
        $up->price = $price;
        $up->save();
    }

    public static function refund($payData) {
        // 钱包退款
        if ($payData['pay_type'] == 0) {
            self::adjustWallet($payData['customer_id'], $payData['wallet_money'], 'plus', 'refund_' . $payData['order_id']);
            
            $orderId = $payData['order_id'];
            $up = ProductOrder::findOne($orderId);
            $up->status = 4;
            $up->save();
        }

        // 支付宝退款
        if ($payData['pay_type'] == 1) {
            $result = AlipayHelper::refund($payData);

            if ($result->alipay_trade_refund_response->code == 10000) {
                $orderId = $payData['order_id'];
                $up = ProductOrder::findOne($orderId);
                $up->status = 4;
                $up->save();

                if ($payData['wallet_money'] > 0) {
                    self::adjustWallet($payData['customer_id'], $payData['wallet_money'], 'plus', 'refund_' . $payData['order_id']);
                }
            } else {
                return '退款失败';
            }
        }

        // 微信退款
        if ($payData['pay_type'] == 2) {
            $result = WxpayHelper::refund($payData);

            if (isset($result['return_code']) && $result['return_code'] == 'SUCCESS') {
                $orderId = $payData['order_id'];
                $up = ProductOrder::findOne($orderId);
                $up->status = 4;
                $up->save();

                if ($payData['wallet_money'] > 0) {
                    self::adjustWallet($payData['customer_id'], $payData['wallet_money'], 'plus', 'refund_' . $payData['order_id']);
                }
            } else {
                return '退款失败:' . $result['return_msg'];
            }
        }

        return 'ok';
    }
}
