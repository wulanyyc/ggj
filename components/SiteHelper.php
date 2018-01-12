<?php
namespace app\components;

use Yii;
use yii\base\Component;
use app\models\Address;
use app\models\Customer;
use app\models\CustomerWeixin;
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

        if (!empty($_COOKIE['openid'])) {
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
        $cid    = isset($_COOKIE['cid']) ? $_COOKIE['cid'] : '';
        $secret = isset($_COOKIE['secret']) ? $_COOKIE['secret'] : '';
        $openid = isset($_COOKIE['openid']) ? $_COOKIE['openid'] : '';

        if (empty($openid)) {
            if (empty($cid) || empty($secret)) {
                return false;
            }

            $phone = self::getCustomerPhone($cid);

            if ($secret == self::buildSecret($phone)) {
                return true;
            }
        } else {
            $cid = CustomerWeixin::find()->select('customer_id')->where(['openid' => $openid])->scalar();
            if ($cid > 0) {
                return true;
            }
        }

        return false;
    }

    /**
     * 查询手机归属地
     */
    public static function getPhoneArea($phone) {
        $host = "http://jshmgsdmfb.market.alicloudapi.com";
        $path = "/shouji/query";
        $method = "GET";
        $appcode = Yii::$app->params['expressQuery']['appcode'];
        $headers = array();
        array_push($headers, "Authorization:APPCODE " . $appcode);
        $querys = "shouji=" . $phone;
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

    public static function encrpytPhone($phone) {
        return substr($phone, 0, 3) . '****' . substr($phone, 7);
    }

    public static function getClientIp() {
        $ip = '';
        if (isset($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        } else if (getenv('REMOTE_ADDR')) {
            $ip = getenv('REMOTE_ADDR');
        }

        return $ip;
    }

    public static function getServerIp(){
        return gethostbyname($_SERVER['SERVER_NAME']);
    }

    public static function getCustomerId() {
        if (!empty($_COOKIE['cid'])) {
            return $_COOKIE['cid'];
        }

        if (!empty($_COOKIE['openid'])) {
            $cid = CustomerWeixin::find()->select('customer_id')->where(['openid' => $_COOKIE['openid']])->scalar();
            if ($cid > 0) {
                return $cid;
            }
        }

        return 0;
    }

    public static function render($status, $data = '') {
        if ($status == 'ok') {
            echo json_encode(['status' => $status, 'data' => $data]);
        } else {
            echo json_encode(['status' => $status, 'msg' => $data]);
        }
        
        Yii::$app->end();
    }

    public static function renderText($msg) {
        echo $msg;
        Yii::$app->end();
    }

    public static function getCustomerPhone($cid) {
        return Customer::find()->where(['id' => $cid])->select('phone')->scalar();
    }
}
