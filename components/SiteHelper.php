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
use app\components\WechatHelper;
use app\modules\product\models\ProductList;

/**
 * 基础帮助类
 * @author yangyuncai
 *
 */
class SiteHelper extends Component{
    public static function addCustomer($phone) {
        $openid = self::getOpenid();
        if (!empty($openid)) {
            $id = WechatHelper::addWxCustomer($openid);

            $up = Customer::findOne($id);
            $up->phone = $phone;
            $up->save();

            return $id;
        }

        // TODO combine zhanghao
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

            // TODO 发注册券
            PriceHelper::createCouponById(Yii::$app->params['coupon']['subscribe'], $id);
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
        $openid   = self::getOpenid();

        if (!empty($openid)) {
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
        $openid = self::getOpenid();

        if (empty($openid)) {
            if (empty($cid) || empty($secret)) {
                return false;
            }

            $phone = self::getCustomerPhone($cid);

            if ($secret == self::buildSecret($phone)) {
                return true;
            }
        } else {
            $cid = Customer::find()->select('id')->where(['openid' => $openid])->scalar();
            if ($cid > 0) {
                return true;
            } else {
                $cid = WechatHelper::addWxCustomer($openid);
                if ($cid > 0) {
                    return true;
                }
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

    public static function getCustomerId($openid = "") {
        if (!empty($openid)) {
            $cid = Customer::find()->select('id')->where(['openid' => $openid])->scalar();
            if ($cid > 0) {
                return $cid;
            } else {
                return WechatHelper::addWxCustomer($openid);
            }
        }

        $openid = self::getOpenid();
        if (!empty($openid)) {
            $cid = Customer::find()->select('id')->where(['openid' => $openid])->scalar();
            if ($cid > 0) {
                setcookie('cid', $cid, time() + 86400 * 30, '/');
                return $cid;
            } else {
                $cid = WechatHelper::addWxCustomer($openid);
                setcookie('cid', $cid, time() + 86400 * 30, '/');
                return $cid;
            }
        }

        if (!empty($_COOKIE['cid'])) {
            return $_COOKIE['cid'];
        }

        return 0;
    }

    public static function getOpenid() {
        if (!empty($_SESSION['openid'])) {
            return $_SESSION['openid'];
        }

        if (!empty($_COOKIE['openid'])) {
            return $_COOKIE['openid'];
        }

        return '';
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

    public static function isWechatBrowser() {
        if (!empty($_COOKIE['wechat'])) {
            return true;
        }

        return false;
    }

    /**
     * 随机生成字符串
     * @return string 生成的字符串
     */
    public static function getRandomStr($num) {
        $str = "";
        $str_pol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
        $max = strlen($str_pol) - 1;
        for ($i = 0; $i < $num; $i++) {
            $str .= $str_pol[mt_rand(0, $max)];
        }
        return $str;
    }

    public static function handleNick($nick) {
        $nick = preg_replace('/\*|\./', '', $nick);

        $nick = json_encode($nick);
        $nick = preg_replace("/\\\u[ed][0-9a-f]{3}\\\u[ed][0-9a-f]{3}/", "", $nick);
        $nick = json_decode($nick);

        $nick = trim($nick);

        if (empty($nick)) {
            $nick = '果友';
        }

        return $nick;
    }
}
