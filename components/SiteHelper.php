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

/**
 * 基础帮助类
 * @author yangyuncai
 *
 */
class SiteHelper extends Component{
    public static function addCustomer($phone) {
      $exsit = Customer::find()->where(['phone' => $phone])->count();
      if ($exsit == 0) {
        $ar = new Customer();
        $ar->phone = $phone;
        $ar->status = 1;
        $ar->save();
      }
    }

    public static function getLayout() {
        $width = isset($_COOKIE['width']) ? $_COOKIE['width'] : 1280;

        if ($width <= 767) {
            return 'wap';
        }
        return 'pc';
    }

    public static function getTermimal() {
        $width = isset($_COOKIE['width']) ? $_COOKIE['width'] : 1280;
        $terminal = isset($_COOKIE['terminal']) ? $_COOKIE['terminal'] : '';

        if (empty($terminal)) {
            if ($width <= 767) {
                return 'wap';
            }

            return 'pc';
        } else {
            return $terminal;
        }
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
        $phone = isset($_COOKIE['userphone']) ? $_COOKIE['userphone'] : '';
        $secret = isset($_COOKIE['secret']) ? $_COOKIE['secret'] : '';

        if (empty($phone) || empty($secret)) {
            return false;
        }

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

    public static function addCustomerScore($num) {
        $phone = $_COOKIE['userphone'];
        $data = Customer::find()->where(['phone' => $phone])->select('id,score')->asArray()->one();

        $score = $data['score'] + $num;
        $ar = Customer::findOne($data['id']);
        $ar->score = $score;
        $ar->save();
    }

    // TODO 完善微信来源
    public static function getSource() {
        $terminal = self::getTermimal();

        return $terminal;
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

        // 更新券
        $coupons = ProductOrder::find()->where(['id' => $data['order_id']])
            ->select('coupon_fee, coupon_ids')->asArray()->one();

        if ($coupons['coupon_fee'] > 0) {
            $couponIds = explode(',', $coupons['coupon_ids']);
            foreach($couponIds as $item) {
                $exsit = CouponUse::find()->where(['cid' => $item, 'userphone' => $data['userphone']])->count();
                if ($exsit > 0) {
                    $add = CouponUse::findOne($item);
                    $add->use_status = 2;
                    $add->save();
                } else {
                    $add = new CouponUse();
                    $add->userphone = $data['userphone'];
                    $add->cid = $item;
                    $add->use_status = 2;
                    $add->save();
                }
            }
        }

        // 更新支付积分
        self::addCustomerScore(round($data['online_money'] + $data['wallet_money']));

        // 更新折扣
        $discountData = ProductOrder::find()->where(['id' => $data['order_id']])
            ->select('discount_fee, discount_phone, userphone, id')->asArray()->one();

        if ($discountData['discount_fee'] > 0) {
            $money = round($discountData['discount_fee'] * 0.5, 1);
            PriceHelper::addFriendWallet($money, $discountData['discount_phone'], 'friend_discount');

            SmsHelper::sendDiscount($discountData['discount_phone'], [
                'code' => substr($discountData['userphone'], 7),
                'order' => $discountData['id'],
                'visit' => $discountData['id'],
            ]);
        }
    }

    public static function encrpytPhone($phone) {
        return substr($phone, 0, 3) . '****' . substr($phone, 7);
    }
}
