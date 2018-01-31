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
        $info = ProductOrder::find()->where(['id' => $id])->asArray()->one();
        $customerInfo = Customer::find()->select('nick,phone,openid')
            ->where(['id' => $info['customer_id']])->asArray()->one();

        if (empty($customerInfo['openid'])) {
            return '';
        }

        $templateId = 'N671RZlQrYIhcFLGrh8d4NPs--cZFfOZATun9kvpado';
        $url = self::$api . WechatHelper::getAccessToken();
        $myId = 'ogtchwuVyQbfINjjodQf9Ty8d0Is';

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
                    'value' => $info['product_price'] . '_' . $info['express_fee'] . '_' . $info['coupon_fee'] . '_' . $onlineMoney,
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
            ],
            'url' => 'http://guoguojia.vip/order/handle?id=' . $info['id'] . '&uid=' . $myId . '&token=' . md5($info['id'] . Yii::$app->params['salt']),
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

    public static function prepare($id) {
        $info = ProductOrder::find()->where(['id' => $id])->asArray()->one();
        $customerInfo = Customer::find()->select('nick,phone,openid')
            ->where(['id' => $info['customer_id']])->asArray()->one();

        if (empty($customerInfo['openid'])) {
            return '';
        }

        $templateId = '_GfNXF7BF1Lpf_CJkdbZA1D8ZXSiHUPMq591AijeNJQ';
        $url = self::$api . WechatHelper::getAccessToken();
        $myId = $customerInfo['openid'];

        $data = [
            'touser' => $myId,
            'template_id' => $templateId,
            'data' => [
                'first' => [
                    'value' => '感谢您的购买，新鲜佳果积极备货中',
                    'color' => '#e83030',
                ],
                'keyword1' => [
                    'value' => '水果和干果',
                    'color' => '#173177',
                ],
                'keyword2' => [
                    'value' => date('Ymd', time()) . $id,
                    'color' => '#173177',
                ],
                'keyword3' => [
                    'value' => '果果佳',
                    'color' => '#173177',
                ],
                'keyword4' => [
                    'value' => date('Y-m-d H:i:s', time()),
                    'color' => '#173177',
                ],
                'remark' => [
                    'value' => '参加公众号抽奖活动（菜单：聚优惠->抽奖），获取更多优惠。分享抽奖活动享更多惊喜',
                    'color' => '#173177',
                ]
            ],
        ];

        WechatHelper::curlRequest($url, json_encode($data));
    }
}
