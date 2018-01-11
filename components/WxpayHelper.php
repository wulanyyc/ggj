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

        if (!empty($ret)) {
            return WechatHelper::xmlToArray($ret);
        } else {
            return [];
        }
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

    public static function refund($params) {
        $api = 'https://api.mch.weixin.qq.com/secapi/pay/refund';

        $data = [];
        $data['appid'] = Yii::$app->params['wechat']['appid'];
        $data['mch_id'] = Yii::$app->params['wechat']['mch_id'];
        $data['refund_desc'] = '果果佳后台退款';
        $data['nonce_str'] = uniqid();
        $data['out_trade_no'] = $params['out_trade_no'];
        $data['total_fee'] = $params['online_money'] * 100;
        $data['refund_fee'] = $params['online_money'] * 100;
        $data['out_refund_no'] = $params['order_id'];

        $sign = self::buildSign($data);
        $data['sign'] = $sign;
        $xml = self::buildXml($data);
        $postData = $xml->asXML();

        $ret = self::curl($postData, $api, true);

        return WechatHelper::xmlToArray($ret);
    }

    public static function query($data) {

    }

    public static function curl($xml, $url, $useCert = false, $second = 30) {
        $ch = curl_init();
        //设置超时
        curl_setopt($ch, CURLOPT_TIMEOUT, $second);
        
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST, false);

        //设置header
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        //要求结果为字符串且输出到屏幕上
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    
        if($useCert == true){
            //设置证书
            //使用证书：cert 与 key 分别属于两个.pem文件
            curl_setopt($ch,CURLOPT_SSLCERTTYPE,'PEM');
            curl_setopt($ch,CURLOPT_SSLCERT, Yii::$app->basePath . '/config/apiclient_cert.pem');
            curl_setopt($ch,CURLOPT_SSLKEYTYPE,'PEM');
            curl_setopt($ch,CURLOPT_SSLKEY, Yii::$app->basePath . '/config/apiclient_key.pem');
        }
        //post提交方式
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        //运行curl
        $data = curl_exec($ch);

        Yii::error(var_dump($data));

        //返回结果
        if($data){
            curl_close($ch);
            return $data;
        } else { 
            $error = curl_errno($ch);
            curl_close($ch);
            Yii::error($error);
            return '';
        }
    }


}
