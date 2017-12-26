<?php
namespace app\components;

use Yii;
use yii\base\Component;
use app\models\Address;
use app\models\Customer;
use app\modules\product\models\Coupon;
use app\modules\product\models\CouponUse;

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
            return 'page';
        }
        return 'site';
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

    public static function calculateExpressFee($type, $productPrice) {
        if ($type == 0) {
            if ($productPrice < Yii::$app->params['buyGod']) {
                return Yii::$app->params['expressFee'];
            } else {
                return 0;
            }
        }

        if ($type == 1) {
            if ($productPrice < Yii::$app->params['bookingGod']) {
                return Yii::$app->params['expressFee'];
            } else {
                return 0;
            }
        }
    }

    public static function getValidCoupon() {
        $currentDate = date('Ymd', time());
        $phone = $_COOKIE['userphone'];
        $tongyong = Coupon::find()->where(['type' => 2])
            ->andWhere(['<=', 'start_date', $currentDate])
            ->andWhere(['>=', 'end_date', $currentDate])
            ->asArray()->all();

        foreach($tongyong as $key => $item) {
            $exsit = CouponUse::find()->where(['userphone' => $phone, 'use_status' => 2, 'cid' => $item['id']])->count();
            if ($exsit > 0) {
                unset($tongyong[$key]);
            }
        }

        $lingqu = Coupon::findBySql("select c.* from coupon as c, coupon_use as u where c.id = u.cid and c.start_date <= " . $currentDate . " and c.end_date >=" . $currentDate . " and u.userphone=" . $phone . " and u.use_status = 1 and c.type = 1")->asArray()->all();

        $data = array_merge($tongyong, $lingqu);

        return $data;
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
}
