<?php
namespace app\components;

use Yii;
use yii\base\Component;
use app\models\Customer;
use app\components\SiteHelper;

/**
 * 基础帮助类
 * @author yangyuncai
 *
 */
class ExpressHelper extends Component{
    public static function getEorder($params, $company = 'SF') {
        $requestData = [];

        $requestData['ShipperCode'] = $company;
        $requestData['OrderCode']   = $params['id'] ;
        $requestData['PayType']     = 1; // 1-现付，2-到付，3-月结，4-第三方支付
        $requestData['ExpType']     = 1;

        $requestData['Receiver']['Name']         = $params['rec_name'];
        $requestData['Receiver']['Mobile']       = $params['rec_phone'];
        $requestData['Receiver']['ProvinceName'] = $params['rec_province'];
        $requestData['Receiver']['CityName']     = $params['rec_city'];
        $requestData['Receiver']['ExpAreaName']  = $params['rec_district'];
        $requestData['Receiver']['Address']      = $params['rec_detail'];

        $requestData['Sender']['Name']         = Yii::$app->params['kdn']['sender_name'];
        $requestData['Sender']['Mobile']       = Yii::$app->params['kdn']['sender_phone'];
        $requestData['Sender']['ProvinceName'] = Yii::$app->params['kdn']['sender_province'];
        $requestData['Sender']['CityName']     = Yii::$app->params['kdn']['sender_city'];
        $requestData['Sender']['ExpAreaName']  = Yii::$app->params['kdn']['sender_district'];
        $requestData['Sender']['Address']      = Yii::$app->params['kdn']['sender_detail'];

        $requestData['Commodity'] = [];
        $requestData['Commodity'][] = ['GoodsName' => $params['order_name']];


        // print_r($requestData);exit;

        $jsonParam = json_encode($requestData, JSON_UNESCAPED_UNICODE);

        return self::submitEOrder($jsonParam);
    }

    /**
        * Json方式 调用电子面单接口
    */
    public static function submitEOrder($requestData){
        $datas = array(
            'EBusinessID' => Yii::$app->params['kdn']['uid'],
            'RequestType' => '1007',
            'RequestData' => urlencode($requestData) ,
            'DataType' => '2',
        );

        $datas['DataSign'] = self::encrypt($requestData, Yii::$app->params['kdn']['apikey']);

        $result = self::sendPost(Yii::$app->params['kdn']['eorderurl'], $datas);   
        
        //根据公司业务处理返回的信息......
        return $result;
    }

    public static function encrypt($data, $appkey) {
        return urlencode(base64_encode(md5($data.$appkey)));
    }

    /**
     *  post提交数据 
     * @param  string $url 请求Url
     * @param  array $datas 提交的数据 
     * @return url响应返回的html
     */
    public static function sendPost($url, $datas) {
        $temps = array();   
        foreach ($datas as $key => $value) {
            $temps[] = sprintf('%s=%s', $key, $value);      
        }   
        $post_data = implode('&', $temps);
        $url_info  = parse_url($url);

        if(empty($url_info['port'])) {
            $url_info['port']=80;   
        }

        $httpheader = "POST " . $url_info['path'] . " HTTP/1.0\r\n";
        $httpheader.= "Host:" . $url_info['host'] . "\r\n";
        $httpheader.= "Content-Type:application/x-www-form-urlencoded\r\n";
        $httpheader.= "Content-Length:" . strlen($post_data) . "\r\n";
        $httpheader.= "Connection:close\r\n\r\n";
        $httpheader.= $post_data;

        $fd = fsockopen($url_info['host'], $url_info['port']);
        fwrite($fd, $httpheader);

        $gets = "";
        $headerFlag = true;
        while (!feof($fd)) {
            if (($header = @fgets($fd)) && ($header == "\r\n" || $header == "\n")) {
                break;
            }
        }

        while (!feof($fd)) {
            $gets.= fread($fd, 128);
        }

        fclose($fd);  
        
        return $gets;
    }

}
