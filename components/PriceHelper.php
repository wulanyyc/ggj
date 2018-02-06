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
use app\components\WechatHelper;
use app\components\NotifyHelper;
use app\models\GiftUse;

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
        $promotion = Yii::$app->params['new_promotion'];
        if ($id == $promotion['id']) {
            $price = round($price * $promotion['discount'], 2);
        }

        return $price;
    }

    public static function getDayPromotion($id, $price) {
        $promotions = Yii::$app->params['day_promotion'];

        $dayofweek = date('w', time());

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

        $customerId = SiteHelper::getCustomerId($openid);

        if ($type == 1) {
            $exsit = CouponUse::find()->where(['cid' => $couponid, 'customer_id' => $customerId])->count();
            if ($exsit) {
                return 0;
            } else {
                $ar = new CouponUse();
                $ar->cid = $couponid;
                $ar->customer_id = $customerId;
                $ar->save();

                return $ar->id;
            }
        } else {
            $ar = new CouponUse();
            $ar->cid = $couponid;
            $ar->customer_id = $customerId;
            $ar->save();

            return $ar->id;
        }
    }

    public static function createCouponById($couponid, $customerId) {
        $today = date('Ymd', time());
        $info = Coupon::find()->where(['id' => $couponid])->asArray()->one();
        if (empty($info)) return 0;

        if ($today > $info['end_date']) {
            return 0;
        }

        $type = $info['type'];

        if ($type == 1) {
            $exsit = CouponUse::find()->where(['cid' => $couponid, 'customer_id' => $customerId])->count();
            if ($exsit) {
                return 0;
            } else {
                $ar = new CouponUse();
                $ar->cid = $couponid;
                $ar->customer_id = $customerId;
                $ar->save();

                return $ar->id;
            }
        } else {
            $ar = new CouponUse();
            $ar->cid = $couponid;
            $ar->customer_id = $customerId;
            $ar->save();

            if ($type == 3) {
                $openid = Customer::find()->select('openid')->where(['id' => $customerId])->scalar();
                if (!empty($openid)) {
                    NotifyHelper::sendFriend($openid, $info['money'], '感谢您一直对果果佳的支持，特赠送您一张优惠券', '优惠券请在菜单【聚优惠】中查看');
                }
            }

            return $ar->id;
        }
    }

    public static function createGift($gid, $openid) {
        $customerId = SiteHelper::getCustomerId($openid);

        $ar = new GiftUse();
        $ar->gid = $gid;
        $ar->customer_id = $customerId;
        $ar->save();

        return $ar->id;
    }

    public static function getValidGift() {
        $customer_id = SiteHelper::getCustomerId();

        return GiftUse::find()->where(['customer_id' => $customer_id, 'use_status' => 1])->asArray()->all();
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

    public static function getValidCartCoupon($id) {
        $data = self::getValidCoupon();
        $customerId = SiteHelper::getCustomerId();

        $price = ProductCart::find()->select('product_price')
            ->where(['id' => $id, 'customer_id' => $customerId])
            ->scalar();

        foreach($data as $key => $value) {
            $limit = Coupon::find()->where(['id' => $value['cid']])->select('money_limit')->scalar();
            if ($limit > 0 && $limit > $price) {
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
                $cid = CouponUse::find()->select('cid')->where(['id' => $id])->scalar();
                if ($item['cid'] == $cid) {
                    $money = Coupon::find()->select('money')->where(['id' => $cid])->scalar();
                    $fee += $money;
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
        
        foreach($carts as $key => $item) {
            $id = $item['id'];
            if (isset($item['limit']) && $item['limit'] > 0 && $item['num'] > $item['limit']) {
                $orignalPrice = ProductList::find()->where(['id' => $id])->select('price')->scalar();
                $productPrice += ($item['num'] - $item['limit']) * $orignalPrice + $item['limit'] * PriceHelper::getProductPrice($id, $data['order_type']);
            } else {
                $productPrice += $item['num'] * PriceHelper::getProductPrice($id, $data['order_type']);
            }
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
        $wallet = Customer::find()->where(['id' => $cid])->select('money')->scalar();

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

    public static function getPrize($rotate) {
        $prizeNum = (5 * 360 - $rotate) / 45;
        if ($prizeNum == 8) $prizeNum = 7;

        $prize = [
            0 => [
                'type' => 'coupon',
                'id'   => 15,
                'text' => '5元优惠券',
            ],
            1 => [
                'type' => 'gift',
                'id'   => 7,
                'text' => '100g进口单J车厘子礼品券',
            ],
            2 => [
                'type' => 'coupon',
                'id'   => 16,
                'text' => '10元优惠券',
            ],
            3 => [
                'type' => 'gift',
                'id'   => 2,
                'text' => '半斤香梨礼品券',
            ],
            4 => [
                'type' => 'coupon',
                'id'   => 17,
                'text' => '20元优惠券',
            ],
            5 => [
                'type' => 'gift',
                'id'   => 6,
                'text' => '125g开心果礼品券',
            ],
            6 => [
                'type' => 'coupon',
                'id'   => 18,
                'text' => '2元优惠券',
            ],
            7 => [
                'type' => 'gift',
                'id'   => 4,
                'text' => '半斤皇帝柑礼品券',
            ]
        ];

        return $prize[$prizeNum];
    }

    public static function handlePrize($key, $openid) {
        // 抽奖间隔
        $prizeLimit = 2;

        $exsit = Customer::find()->where(['openid' => $openid])->count();
        if ($exsit == 0) {
            WechatHelper::addWxCustomer($openid);
        }

        $data = Yii::$app->redis->get('prize_' . $key);
        $ret  = 0;
        $info = json_decode($data, true);
        $str = '';

        $get = Yii::$app->redis->get('prize_' . $key . '_get');
        $userGet = Yii::$app->redis->get('prize_' . $openid . '_get');
        if ($get > 0 || $userGet > 0) {
            $remainDay = self::getPrizeRemainDay($info['uniq']);
            return '您已领取过奖品。请' . $remainDay . '天后再抽奖，有疑问请联系客服';
        }

        if (empty($data)) {
            return '奖品已过期，请再次抽奖[聚优惠->抽奖]，有疑问请联系客服';
        }

        if ($info['type'] == 'gift') {
            $ret = self::createGift($info['id'], $openid);
            $str = '您的抽奖：' . $info['text'] . ', 已成功领取, 5天后可再抽奖。使用[果果商城->逛商城], 查看菜单[聚优惠->礼品券]。';
        }

        if ($info['type'] == 'coupon') {
            $ret = self::createCoupon($info['id'], $openid);
            $str = '您的抽奖：' . $info['text'] . ', 已成功领取, 5天后可再抽奖。使用[果果商城->逛商城], 查看菜单[聚优惠->优惠券]。';
        }

        if ($ret > 0) {
            Yii::$app->redis->setex('prize_' . $openid . '_get', 86400 * $prizeLimit, 1);
            Yii::$app->redis->setex('prize_' . $key . '_get', 86400 * $prizeLimit, 1);

            // 来源openid
            $fromOpenid = Yii::$app->redis->get($info['uniq']. '_from');

            if (!empty($fromOpenid)) {
                $fopenid = Customer::find()->select('from_openid')->where(['openid' => $openid])->scalar();
                if (empty($fopenid) && $fromOpenid != $openid) {
                    Customer::updateAll(['from_openid' => $fromOpenid], ['openid' => $openid]);

                    // 分享好友享返利
                    NotifyHelper::sendFanli($openid, $fromOpenid, 2);
                }
            }

            return $str;
        } else {
            return '您的抽奖礼品：' . $info['text'] . ', 获取失败，请联系客服';
        }
    }

    public static function getPrizeRemainDay($uniq) {
        $remainTime = Yii::$app->redis->ttl($uniq . "_exsit");
        if ($remainTime > 0) {
            $remainDay = round($remainTime / 86400, 1);
        } else {
            $remainDay = 5; // TODO
        }

        return $remainDay;
    }

    public static function handleShare($key, $openid) {
        $exsit = Customer::find()->where(['openid' => $openid])->count();
        if ($exsit == 0) {
            WechatHelper::addWxCustomer($openid);
        }

        // Yii::error("key:" . $key);

        $keyArr = explode('_', $key);
        $cid = $keyArr[2];

        $fromOpenid = Customer::find()->select('openid')->where(['id' => $cid])->scalar();

        $currentParent = Customer::find()->select('from_openid')->where(['openid' => $openid])->scalar();
        if (empty($currentParent) && $fromOpenid != $openid) {
            Customer::updateAll(['from_openid' => $fromOpenid], ['openid' => $openid]);

            // 分享好友享返利
            NotifyHelper::sendFanli($openid, $fromOpenid, 2);
        }

        return '';
    }

    public static function addParentFanli($customerId, $orderId) {
        $percent = 1;

        $info = Customer::find()->select('from_openid, nick')->where(['id' => $customerId])->asArray()->one();
        if (!empty($info['from_openid'])) {
            $payMoney = ProductOrder::find()->select('pay_money')->where(['id' => $orderId])->scalar();
            $fanli = round($payMoney * $percent / 100, 1);
            $text = '好友"' . $info['nick'] . '"下单返利';
            $memo = '感谢您对果果佳的支持，分享给更多好友享更多返利。返利请查看钱包余额';

            $fromCustoerId = Customer::find()->select('id')->where(['openid' => $info['from_openid']])->scalar();
            self::adjustWallet($fromCustoerId, $fanli, 'plus', '好友下单返利');

            NotifyHelper::sendFriend($info['from_openid'], $fanli, $text, $memo);
        }
    }
}
