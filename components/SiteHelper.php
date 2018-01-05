<?php
namespace app\components;

use Yii;
use yii\base\Component;
use app\models\Address;
use app\models\Customer;
use app\modules\product\models\Coupon;
use app\modules\product\models\CouponUse;
use app\models\ProductOrder;
use app\models\ProductCart;
use app\models\Pay;
use app\components\PriceHelper;
use app\components\SmsHelper;
use app\modules\product\models\ProductList;

/**
 * 基础帮助类
 * @author yangyuncai
 *
 */
class SiteHelper extends Component{
    public static function addCustomer($phone) {
      $id = Customer::find()->where(['phone' => $phone])->select('id')->scalar();
      if ($id > 0) {
        $up = Customer::findOne($id);
        $up->status = 1;
        $up->save();
      } else {
        $ar = new Customer();
        $ar->phone = $phone;
        $ar->status = 1;
        $ar->save();
        $id = $ar->id;
      }

      return $id;
    }

    public static function getLayout() {
        $width = isset($_COOKIE['width']) ? $_COOKIE['width'] : 0;

        if ($width == 0) {
            return self::getTermimal();
        }

        if ($width <= 767) {
            return 'wap';
        }

        return 'pc';
    }

    public static function getTermimal() {
        $terminal = isset($_COOKIE['terminal']) ? $_COOKIE['terminal'] : 'wap';
        return $terminal;
    }

    // TODO 完善微信来源
    public static function getSource() {
        $terminal = self::getTermimal();

        if (isset($_COOKIE['openid'])) {
            return 'wechat';
        }

        return $terminal;
    }

    public static function checkPhone($phone) {
        $reg ='/^1\d{10}$/';
        if(preg_match($reg, $phone)) {
            return true;
        }

        return false;
    }

    public static function buildSecret($phone) {
        return substr(md5($phone . Yii::$app->params['salt']), 0, 8);
    }

    public static function checkSecret() {
        $cid = isset($_COOKIE['cid']) ? $_COOKIE['cid'] : '';
        $secret = isset($_COOKIE['secret']) ? $_COOKIE['secret'] : '';

        if (empty($cid) || empty($secret)) {
            return false;
        }

        $phone = self::getCustomerPhone($cid);

        if ($secret == self::buildSecret($phone)) {
            return true;
        }

        return false;
    }

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
        $cartData = ProductCart::find()->where(['id' => $cartId])->select('cart,type')->asArray()->one();
        $cartArr = json_decode($cartData['cart'], true);

        if ($cartData['type'] == 0) {
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
            $couponIds = explode(',', $coupons['coupon_ids']);
            foreach($couponIds as $item) {
                $exsit = CouponUse::find()->where(['cid' => $item, 'customer_id' => $data['customer_id']])->count();
                if ($exsit > 0) {
                    $add = CouponUse::findOne($item);
                    $add->use_status = 2;
                    $add->save();
                } else {
                    $add = new CouponUse();
                    $add->customer_id = $data['customer_id'];
                    $add->cid = $item;
                    $add->use_status = 2;
                    $add->save();
                }
            }
        }

        // 更新支付积分
        self::addCustomerScore(round($data['online_money'] + $data['wallet_money']), $data['customer_id']);

        // 更新折扣
        $discountData = ProductOrder::find()->where(['id' => $data['order_id']])
            ->select('discount_fee, discount_phone, customer_id, id')->asArray()->one();

        if ($discountData['discount_fee'] > 0) {
            $money = round($discountData['discount_fee'] * 0.5, 1);
            PriceHelper::addFriendWallet($money, $discountData['discount_phone'], 'friend_discount');

            $userphone = self::getCustomerPhone($discountData['customer_id']);
            SmsHelper::sendDiscount($discountData['discount_phone'], [
                'code' => substr($userphone, 7),
                'order' => $discountData['id'],
                'visit' => $discountData['id'],
            ]);
        }
    }

    public static function encrpytPhone($phone) {
        return substr($phone, 0, 3) . '****' . substr($phone, 7);
    }

    public static function getCustomerId($phone) {
        return Customer::find()->where(['phone' => $phone])->select('id')->scalar();
    }

    public static function getCustomerPhone($cid) {
        return Customer::find()->where(['id' => $cid])->select('phone')->scalar();
    }
}
