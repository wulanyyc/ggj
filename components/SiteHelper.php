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
        $data = Customer::find()->where(['phone' => $phone])->select('id,score')->scalar();

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
}
