<?php
namespace app\components;

use Yii;
use yii\base\Component;
use app\components\WechatHelper;
use app\components\SiteHelper;


/**
 * 基础帮助类
 * @author yangyuncai
 *
 */
class WxpayHelper extends Component{
    public static $api = 'https://api.mch.weixin.qq.com/pay/unifiedorder';

    public static function pay($params) {
        $data = [];
        $data['appid'] = Yii::$app->params['wechat']['appid'];
        $data['mch_id'] = Yii::$app->params['wechat']['mch_id'];
        $data['body'] = $params['subject'];
        $data['nonce_str'] = uniqid();
        $data['out_trade_no'] = $params['out_trade_no'];
        $data['total_fee'] = $params['total_amount'];
        $data['spbill_create_ip'] = SiteHelper::getClientIp();
        $data['notify_url'] = Yii::$app->params['wechat']['notify_url'];
        $data['trade_type'] = $params['trade_type'];

        if (!empty($params['openid'])) {
            $data['openid'] = $params['openid'];
        }

        $sign = self::buildSign($data);
        $data['sign'] = $sign;
        $xml = self::buildXml($data);
        $postData = $xml->asXML();

        $ret = WechatHelper::curlRequest(self::$api, $postData);

        return WechatHelper::xmlToArray($ret);
    }

    public static function buildSign($data) {
        $keys = array_keys($data);
        sort($keys);

        $str = '';
        foreach($keys as $item) {
            $str .= $item . "=" . $data[$item] . "&";
        }

        $str .= "key=" . Yii::$app->params['wechat']['key'];

        $sign = md5($str);

        return strtoupper($sign);
    }

    public static function buildXml($data) {
        $xmlObj = new \SimpleXMLElement('<xml></xml>');
        self::arrayToXml($data, $xmlObj);
        return $xmlObj;
    }

    public static function arrayToXml($data, &$xmlObj) {
        foreach($data as $key => $value) {
            if (is_numeric($key)){
                $key = 'item'.$key;
            }

            if (is_array($value)) {
                $subnode = $xmlObj->addChild($key);
                self::arrayToXml($value, $subnode);
            } else {
                $xmlObj->addChild("$key", htmlspecialchars("$value"));
            }
        }
    }

    public static function scanpay($params) {

    }

    public static function check($data, $terminal) {

    }

    public static function refund($data) {
 
    }

    public static function query($data) {

    }
}
