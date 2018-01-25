<?php
namespace app\components;

use Yii;
use yii\base\Component;
use app\components\WechatHelper;
use app\components\PriceHelper;
use app\components\SiteHelper;
use app\models\ProductOrder;
use app\models\Customer;
use app\models\Pay;

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
        $onlineMoney = Pay::find()->where(['order_id' => $id])->select('online_money')->scalar();


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
                    'value' => $info['pay_money'] . '_' . $onlineMoney,
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

    public static function sendFanli($openid, $fromOpenid, $money) {
        $userinfo = Customer::find()->where(['openid' => $openid])->asArray()->one();
        $date = date('Ymd', strtotime($userinfo['create_time']));
        $currentDate = date('Ymd', time());

        if ($date == $currentDate) {
            $cid = SiteHelper::getCustomerId($fromOpenid);
            PriceHelper::adjustWallet($cid, $money, 'plus', '返利');

            $templateId = 'd5z8VYqpnsWGQn0I-dq6loegLp2u2QXtrLtyibcOUOE';
            $url = self::$api . WechatHelper::getAccessToken();

            $data = [
                'touser' => $fromOpenid,
                'template_id' => $templateId,
                'data' => [
                    'first' => [
                        'value' => '好友"' . $userinfo['nick'] . '"通过你关注了我们, 特此奖励',
                        'color' => '#e83030',
                    ],
                    'keyword1' => [
                        'value' => $money . '元',
                        'color' => '#173177',
                    ],
                    'keyword2' => [
                        'value' => date('Y-m-d H:i:s', time()),
                        'color' => '#173177',
                    ],
                    'remark' => [
                        'value' => '感谢您对果果佳的支持, 奖励请在钱包中查看',
                        'color' => '#173177',
                    ]
                ]
            ];

            WechatHelper::curlRequest($url, json_encode($data));
        }
    }
}
