<?php
namespace app\components;

use Yii;
use yii\base\Component;
use app\models\Customer;
use app\components\SiteHelper;

// defined('API_URL') or define('API_URL', 'http://www.kdniao.com/External/PrintOrder.aspx');
//IP服务地址
// defined('IP_SERVICE_URL') or define('IP_SERVICE_URL', 'http://www.kdniao.com/External/GetIp.aspx');
//电商ID
// defined('EBusinessID') or define('EBusinessID', '请到快递鸟官网申请http://kdniao.com/reg');
//电商加密私钥，快递鸟提供，注意保管，不要泄漏
// defined('APIKey') or define('APIKey', '请到快递鸟官网申请http://kdniao.com/reg');

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
        $requestData['Receiver']['Address']      = $params['rec_address'];

        $requestData['Sender']['Name']         = Yii::$app->params['kdn']['sender_name'];
        $requestData['Sender']['Mobile']       = Yii::$app->params['kdn']['sender_phone'];
        $requestData['Sender']['ProvinceName'] = Yii::$app->params['kdn']['sender_province'];
        $requestData['Sender']['CityName']     = Yii::$app->params['kdn']['sender_city'];
        $requestData['Sender']['ExpAreaName']  = Yii::$app->params['kdn']['sender_district'];
        $requestData['Sender']['Address']      = Yii::$app->params['kdn']['sender_detail'];

        $requestData['Commodity'] = [];
        $requestData['Commodity'][] = ['GoodsName' => $params['order_name']];

        $jsonParam = json_encode($requestData, JSON_UNESCAPED_UNICODE);

        return self::submitEorder($jsonParam);
    }

    /**
        * Json方式 调用电子面单接口
    */
    public static function submitEorder($requestData) {
        $datas = array(
            'EBusinessID' => Yii::$app->params['kdn']['uid'],
            'RequestType' => '1007',
            'RequestData' => urlencode($requestData) ,
            'DataType' => '2',
        );

        $datas['DataSign'] = self::encrypt($requestData, Yii::$app->params['kdn']['apikey']);

        $result = self::sendPost(Yii::$app->params['kdn']['eorderurl'], $datas);
        
        //根据公司业务处理返回的信息
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

    /**
     * 组装POST表单用于调用快递鸟批量打印接口页面
     */
    public static function buildForm($code) {
        //OrderCode:需要打印的订单号，和调用快递鸟电子面单的订单号一致，PortName：本地打印机名称，请参考使用手册设置打印机名称。支持多打印机同时打印。
        $requestData = [
            [
                'OrderCode' => $code,
                'PortName'  => Yii::$app->params['kdn']['print'],
            ]
        ];

        $requestStr = json_encode($requestData);

        // $requestStr = '[{"OrderCode":"' . $code . '","PortName":"Lenovo M7206W Printer"}]';
        // echo $requestStr;exit;

        $request_data_encode = urlencode($requestStr);

        $data_sign = self::encrypt(self::getIp() . $request_data_encode, Yii::$app->params['kdn']['apikey']);

        //是否预览，0-不预览 1-预览
        $is_priview = '1';

        //组装表单
        $form = '<form id="form1" method="POST" action="'. Yii::$app->params['kdn']['printorderurl'] .'"><input type="text" name="RequestData" value="'. $requestStr .'"/><input type="text" name="EBusinessID" value="' . Yii::$app->params['kdn']['uid'] . '"/><input type="text" name="DataSign" value="'. $data_sign .'"/><input type="text" name="IsPriview" value="' . $is_priview . '"/></form><script>form1.submit();</script>';

        return $form;
    }

    /**
     * 判断是否为内网IP
     * @param ip IP
     * @return 是否内网IP
     */
    public static function is_private_ip($ip) {
        return !filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE);
    }

    /**
     * 获取客户端IP(非用户服务器IP)
     * @return 客户端IP
     */
    public static function getIp() {
        //获取客户端IP
        if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
            $ip = getenv('HTTP_CLIENT_IP');
        } elseif(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
            $ip = getenv('HTTP_X_FORWARDED_FOR');
        } elseif(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
            $ip = getenv('REMOTE_ADDR');
        } elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        if(!$ip || self::is_private_ip($ip)) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, Yii::$app->params['kdn']['ipServiceUrl']);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $output = curl_exec($ch);
            return $output;
        }
        else{
            return $ip;
        }
    }
}
