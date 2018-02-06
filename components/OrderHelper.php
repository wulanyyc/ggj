<?php
namespace app\components;

use Yii;
use yii\base\Component;
use app\models\Customer;
use app\modules\product\models\Coupon;
use app\modules\product\models\CouponUse;
use app\models\ProductOrder;
use app\models\ProductCart;
use app\models\Pay;
use app\components\PriceHelper;
use app\components\SmsHelper;
use app\components\SiteHelper;
use app\modules\product\models\ProductList;
use app\components\NotifyHelper;
use app\models\GiftUse;
use app\models\Gift;

/**
 * 基础帮助类
 * @author yangyuncai
 *
 */
class OrderHelper extends Component {
    /**
     * 查询快递信息
     */
    public static function getExpressInfo($number, $type='auto') {
        $host = "http://jisukdcx.market.alicloudapi.com";
        $path = "/express/query";
        $method = "GET";
        $appcode = Yii::$app->params['expressQuery']['appcode'];
        $headers = array();
        array_push($headers, "Authorization:APPCODE " . $appcode);
        $querys = "number=" . $number . "&type=" . $type;
        $bodys = "";
        $url = $host . $path . "?" . $querys;

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_FAILONERROR, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, false);
        if (1 == strpos("$".$host, "https://"))
        {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        }

        $result = curl_exec($curl);
        curl_close($curl);

        return $result;
    }

    public static function addCustomerScore($num, $id) {
        $score = Customer::find()->where(['id' => $id])->select('score')->asArray()->scalar();

        $score = $score + $num;
        $ar = Customer::findOne($id);
        $ar->score = $score;
        $ar->save();
    }

    public static function handlePayOkOrder($payid, $trade_no = '') {
        $data = Pay::find()->where(['id' => $payid])->asArray()->one();

        if ($data['pay_type'] != 0 && $data['wallet_money'] > 0) {
            PriceHelper::adjustWallet($data['customer_id'], $data['wallet_money'], 'minus', 'pay_order_' + $data['id'] + "_" + $data['order_id']);
        }

        // 更新支付状态
        $up = Pay::findOne($payid);
        $up->trade_no = $trade_no;
        $up->pay_result = 1;
        $up->save();

        // 跟新订单状态
        $op = ProductOrder::findOne($data['order_id']);
        $op->status = 2;
        $op->save();

        // 更新库存
        $cartId = ProductOrder::find()->where(['id' => $data['order_id']])->select('cart_id')->scalar();
        $cartData = ProductCart::find()->where(['id' => $cartId])->select('cart,order_type')->asArray()->one();
        $cartArr = json_decode($cartData['cart'], true);

        if ($cartData['order_type'] == 1) {
            foreach($cartArr as $item) {
                $tmp = ProductList::find()->where(['id' => $item['id']])->asArray()->one();
                $newInventory = $tmp['num'] - $item['num'];
                $up = ProductList::findOne($item['id']);
                $up->num = $newInventory;
                $up->save();
            }
        }

        // 更新券
        $coupons = ProductOrder::find()->where(['id' => $data['order_id']])
            ->select('coupon_fee, coupon_ids')->asArray()->one();

        if ($coupons['coupon_fee'] > 0) {
            $couponUseIds = explode(',', $coupons['coupon_ids']);
            foreach($couponUseIds as $item) {
                CouponUse::updateAll(['use_status' => 2], ['id' => $item]);
            }
        }

        // 更新礼品
        $gifts = ProductOrder::find()->where(['id' => $data['order_id']])
            ->select('gift_ids')->asArray()->scalar();

        if (!empty($gifts)) {
            $giftUseIds = explode(',', $gifts);
            foreach($giftUseIds as $item) {
                GiftUse::updateAll(['use_status' => 2], ['id' => $item]);
            }
        }

        // 更新支付积分
        self::addCustomerScore(round($data['online_money'] + $data['wallet_money']), $data['customer_id']);

        // 更新折扣, 此功能暂时屏蔽
        // $discountData = ProductOrder::find()->where(['id' => $data['order_id']])
        //     ->select('discount_fee, discount_phone, customer_id, id')->asArray()->one();

        // if ($discountData['discount_fee'] > 0) {
        //     $money = round($discountData['discount_fee'] * 0.5, 1);
        //     PriceHelper::addFriendWallet($money, $discountData['discount_phone'], 'friend_discount');

        //     $userphone = SiteHelper::getCustomerPhone($discountData['customer_id']);
        //     SmsHelper::sendDiscount($discountData['discount_phone'], [
        //         'code' => substr($userphone, 7),
        //         'order' => $discountData['id'],
        //         'visit' => $discountData['id'],
        //     ]);
        // }

        // 首单发优惠券
        // $customerId = SiteHelper::getCustomerId();
        // $orderNum = ProductOrder::findBySql('select count(*) as num from product_order where customer_id=' . $customerId . ' and status in (2,3)')->scalar();

        // if ($orderNum == 1) {
        //     PriceHelper::createCouponById(Yii::$app->params['coupon']['order'], $customerId);
        // }

        // add order fanli
        PriceHelper::addParentFanli($data['customer_id'], $data['order_id']);

        // 微信通知老板
        NotifyHelper::newOrder($data['order_id']);
    }
}
