<?php
namespace app\components;

use Yii;
use yii\base\Component;
use app\components\WechatHelper;
use app\models\ProductOrder;
use app\models\Customer;

/**
 * 基础帮助类
 * @author yangyuncai
 *
 */
class NotifyHelper extends Component{
    public static $api = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=';
    public static function newOrder($id) {
        $templateId = 'N671RZlQrYIhcFLGrh8d4NPs--cZFfOZATun9kvpado';
        $url = self::$api . WechatHelper::getAccessToken();
        $myId = 'ogtchwuVyQbfINjjodQf9Ty8d0Is';

        $info = ProductOrder::find()->where(['id' => $id])->asArray()->one();
        $customerInfo = Customer::find()->select('nick,phone')
            ->where(['id' => $info['customer_id']])->asArray()->one();

        $customer = !empty($customerInfo['nick']) ? $customerInfo['nick'] : $customerInfo['phone'];

        $data = [
            'touser' => $myId,
            'template_id' => $templateId,
            'data' => [
                'first' => [
                    'value' => '大吉大利，有新订单啦！',
                    'color' => '#e83030',
                ],
                'keyword1' => [
                    'value' => $customer,
                    'color' => '#173177',
                ],
                'keyword2' => [
                    'value' => $id,
                    'color' => '#173177',
                ],
                'keyword3' => [
                    'value' => $info['pay_money'],
                    'color' => '#e83030',
                ],
                'keyword4' => [
                    'value' => $info['source'],
                    'color' => '#173177',
                ],
                'remark' => [
                    'value' => '备注：' . $info['memo'],
                    'color' => '#173177',
                ]
            ]
        ];

        WechatHelper::curlRequest($url, json_encode($data));
    }

    public static function sendExpress() {
        
    }
}
