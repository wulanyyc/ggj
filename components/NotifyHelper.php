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

        // $myId = 'ogtchwuVyQbfINjjodQf9Ty8d0Is';

        $customer = !empty($customerInfo['nick']) ? $customerInfo['nick'] : $customerInfo['phone'];
        $payMoney = Pay::find()->where(['order_id' => $id])->select('online_money, wallet_money')->asArray()->one();

        $myIds = ['ogtchwuVyQbfINjjodQf9Ty8d0Is', 'ogtchwsLcJ0GleEw8WZsBiLPd-Ys', 'ogtchwutQFe3ZM0MTaUo62w7t-Ug', 'ogtchwoCx0ocoUq7kVSjM0V0X30g'];

        foreach($myIds as $myId) {
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
                        'value' => $info['product_price'] . '_' . $info['express_fee'] . '_' . $info['coupon_fee'] . '_' . $payMoney['wallet_money'] . '_' . $payMoney['online_money'],
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
    }

    public static function sendFriend($openid, $money, $text, $memo) {
        $templateId = 'd5z8VYqpnsWGQn0I-dq6loegLp2u2QXtrLtyibcOUOE';
        $url = self::$api . WechatHelper::getAccessToken();

        $data = [
            'touser' => $openid,
            'template_id' => $templateId,
            'data' => [
                'first' => [
                    'value' => $text,
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
                    'value' => $memo,
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

        $text = $info['order_type'] == 1 ? '现售单' : '预约单';

        $data = [
            'touser' => $myId,
            'template_id' => $templateId,
            'data' => [
                'first' => [
                    'value' => '感谢您的购买，新鲜佳果积极备货中',
                    'color' => '#e83030',
                ],
                'keyword1' => [
                    'value' => $text,
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
                    'value' => '每2天抽一次奖，好礼100%。分享抽奖活动享返利（菜单：聚优惠->抽奖）',
                    'color' => '#173177',
                ]
            ],
        ];

        WechatHelper::curlRequest($url, json_encode($data));
    }

    public static function sendExpress($id) {
        $info = ProductOrder::find()->where(['id' => $id])->asArray()->one();
        $customerInfo = Customer::find()->select('nick,phone,openid')
            ->where(['id' => $info['customer_id']])->asArray()->one();

        if (empty($customerInfo['openid']) || empty($info['express_num'])) {
            return '';
        }

        $templateId = 'uxEsaXTxajfovY4GjSBaUAh3BqITRqP6vcVB1_I_iVs';
        $url = self::$api . WechatHelper::getAccessToken();
        $myId = $customerInfo['openid'];

        $data = [
            'touser' => $myId,
            'template_id' => $templateId,
            'data' => [
                'first' => [
                    'value' => '感谢您的购买，您的新鲜水果已发货，请注意及时查收',
                    'color' => '#e83030',
                ],
                'keyword1' => [
                    'value' => '顺丰快递',
                    'color' => '#173177',
                ],
                'keyword2' => [
                    'value' => $info['express_num'],
                    'color' => '#173177',
                ],
                'keyword3' => [
                    'value' => '商品详情，请点击查看详情',
                    'color' => '#173177',
                ],
                'keyword4' => [
                    'value' => '1箱',
                    'color' => '#173177',
                ],
                'remark' => [
                    'value' => '每2天抽一次奖，好礼100%。分享抽奖活动享返利（菜单：聚优惠->抽奖）',
                    'color' => '#173177',
                ]
            ],
            'url' => 'http://guoguojia.vip/order/track?id=' . $info['id'] . '&token=' . md5($info['id'] . Yii::$app->params['salt']),
        ];

        WechatHelper::curlRequest($url, json_encode($data));
    }
}
