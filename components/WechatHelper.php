<?php
namespace app\components;

use Yii;
use yii\base\Component;
use app\models\Customer;
use app\models\CustomerWeixin;
use app\components\SiteHelper;

/**
 * 基础帮助类
 * @author yangyuncai
 *
 */
class WechatHelper extends Component{
    public static $api = "https://api.weixin.qq.com";

    public static function checkSignature() {
        $params = Yii::$app->request->get();

        $signature = isset($params['signature']) ? $params['signature'] : '';
        $timestamp = isset($params['timestamp']) ? $params['timestamp'] : '';
        $nonce = isset($params['nonce']) ? $params['nonce'] : '';

        $config = self::getConfig();
        $tmp = [$config['token'], $timestamp, $nonce];
        sort($tmp, SORT_STRING);

        $tmpStr = implode($tmp);
        $tmpStr = sha1($tmpStr);

        if ($signature == $tmpStr) {
            return true;
        } else {
            return false;
        }
    }

    public static function getConfig() {
        return Yii::$app->params['wechat'];
    }

    public static function getAccessToken() {
        $key = 'access_token';

        $cache = Yii::$app->redis->get($key);

        if (empty($cache)) {
            $config = self::getConfig();
            $url = self::$api . '/cgi-bin/token?grant_type=client_credential&appid='. $config['appid'] .'&secret=' . $config['appsecret'];

            $ret = self::curlRequest($url);
            $data = json_decode($ret, true);

            if (isset($data['access_token'])) {
                Yii::$app->redis->setex($key, $data['expires_in'] - 60, $data['access_token']);
                return $data['access_token'];
            } else {
                return '';
            }
        } else {
            return $cache;
        }
    }

    public static function addWxCustomer($openid) {
        $userinfo = self::getUserInfo($openid);
        $exsit    = Customer::find()->where(['openid' => $openid])->asArray()->one();

        if (count($exsit) > 0) {
            $ar = Customer::findOne($exsit['id']);
        } else {
            $ar = new Customer();
        }

        $ar->openid = $userinfo['openid'];
        $ar->sex = isset($userinfo['sex']) ? $userinfo['sex'] : '';
        $ar->headimgurl = isset($userinfo['headimgurl']) ? $userinfo['headimgurl'] : '';
        $ar->city = isset($userinfo['city']) ? $userinfo['city'] : '';
        $ar->nick = isset($userinfo['nickname']) ? SiteHelper::handleNick($userinfo['nickname']) : '';
        $ar->is_subscribe = $userinfo['subscribe'];

        if (isset($userinfo['unionid'])) {
            $ar->unionid = $userinfo['unionid'];
        }

        $ar->save();

        return $ar->id;
    }

    public static function getPageAccessToken() {
        $key = 'page_access_token';
        $keyRefresh = 'page_refresh_token';

        $cache = Yii::$app->redis->get($key);

        if (empty($cache)) {
            $refreshToken = Yii::$app->redis->get($keyRefresh);
            if (empty($refreshToken)) {
                return '';
            }

            $config = self::getConfig();
            $url = self::$api . '/sns/oauth2/refresh_token?appid='. $config['appid'] .'&grant_type=refresh_token&refresh_token=' . $refreshToken;

            $ret = self::curlRequest($url);
            $data = json_decode($ret, true);

            if (isset($data['access_token'])) {
                Yii::$app->redis->setex($key, $data['expires_in'] - 60, $data['access_token']);
                Yii::$app->redis->setex($keyRefresh, 30 * 86400 - 3600, $data['refresh_token']);

                if (!isset($_SESSION['openid'])) {
                    $_SESSION['openid'] = $data['openid'];
                }

                return $data['access_token'];
            } else {
                return '';
            }
        } else {
            return $cache;
        }
    }

    public static function getJsapiTicket() {
        $key = 'jsapi_ticket';

        $cache = Yii::$app->redis->get($key);

        if (empty($cache)) {
            $config = self::getConfig();
            $url = self::$api . '/cgi-bin/ticket/getticket?access_token=' . self::getAccessToken() . '&type=jsapi';

            $ret = self::curlRequest($url);
            $data = json_decode($ret, true);

            if (isset($data['ticket'])) {
                Yii::$app->redis->setex($key, $data['expires_in'] - 60, $data['ticket']);
                return $data['ticket'];
            } else {
                return '';
            }
        } else {
            return $cache;
        }
    }

    public static function buildPageSignature($url, $timestamp, $noncestr) {
        $data = [
            'url' => $url,
            'noncestr' => $noncestr,
            'timestamp' => $timestamp,
            'jsapi_ticket' => self::getJsapiTicket(),
        ];

        ksort($data, SORT_STRING);

        $str = '';
        foreach($data as $key => $value) {
            $str .= $key . "=" . $value . '&';
        }

        $str = substr($str, 0, -1);

        return sha1($str);
    }

    public static function getUserInfo($openid) {
        $token = self::getAccessToken();
        $url = self::$api . '/cgi-bin/user/info?access_token=' . $token . '&openid='. $openid . '&lang=zh_CN';
        $ret = self::curlRequest($url);

        return json_decode($ret, true);
    }

    public static function getPageUserInfo($openid) {
        $token = self::getPageAccessToken();
        $url = self::$api . '/sns/userinfo?access_token=' . $token . '&openid='. $openid . '&lang=zh_CN';
        $ret = self::curlRequest($url);

        return json_decode($ret, true);
    }

    public static function renderText($data) {
        $xml = '<xml><ToUserName><![CDATA[%s]]></ToUserName><FromUserName><![CDATA[%s]]></FromUserName><CreateTime>%s</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA[%s]]></Content></xml>';

        return sprintf($xml, $data['user'], $data['wxid'], time(), $data['msg']);
    }

    public static function xmlToArray($xml) {
        $obj  = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
        $json = json_encode($obj);
        $data = json_decode($json, TRUE);

        return $data;
    }

    public static function getCurrentUrl() {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $url = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

        return $url;
    }

    public static function initWxPageVisit($code) {
        // https://api.weixin.qq.com/sns/oauth2/access_token?appid=APPID&secret=SECRET&code=CODE&grant_type=authorization_code

        $key = 'page_access_token';
        $keyRefresh = 'page_refresh_token';

        $config = self::getConfig();
        $url = self::$api . '/sns/oauth2/access_token?appid='. $config['appid'] .'&secret=' . $config['appsecret'] . '&code=' . $code . '&grant_type=authorization_code';

        $ret = self::curlRequest($url);
        $data = json_decode($ret, true);

        if (isset($data['access_token'])) {
            Yii::$app->redis->setex($key, $data['expires_in'] - 60, $data['access_token']);
            Yii::$app->redis->setex($keyRefresh, 30 * 86400 - 3600, $data['refresh_token']);
            setcookie('openid', $data['openid'], time() + 86400 * 30, '/');

            if (!isset($_SESSION['openid'])) {
                $_SESSION['openid'] = $data['openid'];
            }
        }
    }

    public static function getPageWechatData() {
        $wechatData = [];
        
        if (!empty($_GET['code'])) {
            $code = $_GET['code'];

            // init weixin user
            if (empty($_SESSION['openid'])) {
                self::initWxPageVisit($code);
            }
        }

        if (!empty($_SESSION['openid'])) {
            $url = self::getCurrentUrl();
            $timestamp = time();
            $noncestr  = self::getNoncestr();
            $signature = self::buildPageSignature($url, $timestamp, $noncestr);

            $wechatData = [
                'timestamp' => $timestamp,
                'noncestr'  => $noncestr,
                'signature' => $signature,
                'appid'     => Yii::$app->params['wechat']['appid'],
            ];
        }

        return $wechatData;
    }

    /**
     * 随机生成16位字符串
     * @return string 生成的字符串
     */
    public static function getNoncestr() {
        $str = "";
        $str_pol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
        $max = strlen($str_pol) - 1;
        for ($i = 0; $i < 16; $i++) {
            $str .= $str_pol[mt_rand(0, $max)];
        }
        return $str;
    }

    /**
     * 远程调用api
     */
    public static function curlRequest($url, $data = "", $header = array(), $timeout = 30) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);

        curl_setopt($ch, CURLOPT_URL, $url);

        if (count($header) > 1) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        }
        
        if (!empty($data)) {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }

        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);

        // curl_setopt($ch,CURLOPT_SSLCERT, Yii::$app->basePath .'/config/wx.pem');

        $response = curl_exec($ch);

        if($response){
            curl_close($ch);
            return $response;
        } else { 
            $error = curl_errno($ch);
            Yii::error("curl: " . $error);
            curl_close($ch);
            return false;
        }
    }

    public static function getTempqrcode($scene) {
        $token = self::getAccessToken();
        $url = self::$api . '/cgi-bin/qrcode/create?access_token=' . $token;

        $data = [
            'expire_seconds' => 2592000,
            'action_name' => 'QR_STR_SCENE',
            'action_info' => [
                'scene' => [
                    'scene_str' => $scene
                ]
            ]
        ];

        $ret = self::curlRequest($url, json_encode($data));

        return json_decode($ret, true);
    }

    public static function getShareqrcode($scene) {
        $token = self::getAccessToken();
        $url = self::$api . '/cgi-bin/qrcode/create?access_token=' . $token;

        $data = [
            'action_name' => 'QR_LIMIT_STR_SCENE',
            'action_info' => [
                'scene' => [
                    'scene_str' => "uid_" . $scene
                ]
            ]
        ];

        $ret = self::curlRequest($url, json_encode($data));

        return json_decode($ret, true);
    }

    public static function getCouponqrcode($id) {
        $token = self::getAccessToken();
        $url = self::$api . '/cgi-bin/qrcode/create?access_token=' . $token;

        $data = [
            'action_name' => 'QR_LIMIT_STR_SCENE',
            'action_info' => [
                'scene' => [
                    'scene_str' => "coupon_" . $id,
                ]
            ]
        ];

        $ret = self::curlRequest($url, json_encode($data));

        return json_decode($ret, true);
    }
}
