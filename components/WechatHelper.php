<?php
namespace app\components;

use Yii;
use yii\base\Component;
use app\models\CustomerWeixin;

/**
 * 基础帮助类
 * @author yangyuncai
 *
 */
class WechatHelper extends Component{
    public static $api = "https://api.weixin.qq.com/cgi-bin";

    public static function checkSignature() {
        $params = Yii::$app->request->get();

        $signature = $params['signature'];
        $timestamp = $params['timestamp'];
        $nonce = $params['nonce'];

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
            $url = self::$api . '/token?grant_type=client_credential&appid='. $config['appid'] .'&secret=' . $config['appsecret'];

            $ret = self::curlRequest($url);
            $data = json_decode($ret, true);

            if (isset($data['access_token'])) {
                Yii::$app->redis->setex('access_token', $data['expires_in'] - 60, $data['access_token']);
                return $data['access_token'];
            } else {
                return '';
            }
        } else {
            return $cache;
        }
    }

    public static function getUserInfo($openid) {
        $token = self::getAccessToken();
        $url = self::$api . '/user/info?access_token=' . $token . '&openid='. $openid . '&lang=zh_CN';
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

    /**
     * 远程调用api
     */
    public static function curlRequest($url, $data = "", $header = array(), $timeout = 20) {
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
            Yii::error($error);
            curl_close($ch);
            return false;
        }
    }
}
