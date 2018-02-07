<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\components\WechatHelper;
use app\components\SiteHelper;
use app\models\CustomerWeixin;
use app\models\Customer;
use app\components\PriceHelper;

class WechatController extends Controller
{
    private $configKeys = [
        'current-skin',
    ];

    public $enableCsrfValidation = false;

    /**
     * 入口
     * @return
     */
    public function actionIndex() {
        $method = Yii::$app->request->getMethod();

        if ($method == 'GET') {
            $params = Yii::$app->request->get();
            $echostr = isset($params['echostr']) ? $params['echostr'] : '';

            if (WechatHelper::checkSignature()) {
                echo $echostr;
            } else {
                echo '';
            }
        }

        if ($method == 'POST') {
            if (!WechatHelper::checkSignature()) {
                echo '';
                Yii::$app->end();
            }

            $body = Yii::$app->request->getRawBody();
            $encrypt = $_GET['encrypt_type'];
            $config = WechatHelper::getConfig();

            if ($encrypt == 'aes') {
                $receiveMsg = '';
                $parse = new \WXBizMsgCrypt($config['token'], $config['encodingAESKey'], $config['appid']);
                $errCode = $parse->decryptMsg($_GET['msg_signature'], $_GET['timestamp'], $_GET['nonce'], $body, $receiveMsg);

                if ($errCode == 0) {
                    $data = WechatHelper::xmlToArray($receiveMsg);
                    $msgType = $data['MsgType'];

                    $content = '';
                    
                    // 文本类型
                    if ($msgType == 'text') {
                        $content = $this->handleText($data['Content']);
                    }

                    // 事件类型
                    if ($msgType == 'event') {
                        $content = $this->handleEvent($data);
                    }

                    $replyMsg = WechatHelper::renderText([
                        'user' => $data['FromUserName'],
                        'wxid' => $data['ToUserName'],
                        'msg'  => $content,
                    ]);

                    $encryptMsg = '';
                    $code = $parse->encryptMsg($replyMsg, time(), $_GET['nonce'], $encryptMsg);

                    if ($code == 0) {
                        header("Content-Type", "application/xml; charset=UTF-8");
                        echo $encryptMsg;
                        Yii::$app->end();
                    }
                }
            }
        }

        echo '';
    }

    // TODO
    private function handleText($content) {
        if (preg_match('/车厘子/', $content)) {
            return '车厘子礼品券需下单后填写地址，顺丰配送。
果果佳提供智利进口单J、双J车厘子现货销售，3J车厘子需预订，价格优惠，保质保量';
        }

        if (preg_match('/买水果|买干果|开心果|皇帝柑|蓝莓|牛油果/', $content)) {
            return '买果果，请到菜单：果果商城->逛商城';
        }

        if (preg_match('/礼品/', $content)) {
            return '礼品券需下单后填写地址，顺丰配送';
        }

        if (preg_match('/消费/', $content)) {
            return '消费券在付款的时候，可直接抵扣订单金额';
        }

        if (preg_match('/优惠/', $content)) {
            return '果果佳有各种优惠：
每周一次抽奖机会（分享抽奖，朋友成功关注公众号后享返利）。
还有各种活动优惠券、满减优惠券等
消费积累的积分还可以兑换余额';
        }

        if (preg_match('/人呢|客服|在/', $content)) {
            return '公众号客服稍慢，请稍等几分钟。如果有急事请联系13880494109（同微信号）';
        }

        return '稍等片刻，可以先玩下抽奖【菜单：聚优惠->抽奖】';
    }

    // TODO
    private function handleEvent($data) {
        $event  = $data['Event'];
        $openid = $data['FromUserName'];
        $eventKey = isset($data['EventKey']) ? $data['EventKey'] : '';

        if ($event == 'subscribe') {
            $userinfo = WechatHelper::getUserInfo($openid);
            if (!isset($userinfo['errcode'])) {
                $exsit = Customer::find()->where(['openid' => $openid])->asArray()->one();

                if (count($exsit) > 0) {
                    WechatHelper::addWxCustomer($openid);
                } else {
                    WechatHelper::addWxCustomer($openid);

                    // TODO 关注公众号，修改优惠id
                    PriceHelper::createCoupon(Yii::$app->params['coupon']['subscribe'], $openid);
                }
            }

            $str = '';

            if (!empty($eventKey)) {
                if (preg_match('/uid_/', $eventKey)) {
                    $str = PriceHelper::handleShare($eventKey, $openid);
                }
                else if (preg_match('/coupon_/', $eventKey)) {
                    $str = $eventKey;
                }
                else {
                    $keyArr = explode('_', $eventKey);
                    $key = $keyArr[1];

                    $str = PriceHelper::handlePrize($key, $openid);
                }
            }

            if (empty($str)) {
                return '欢迎关注成都果果佳，新鲜佳果，保质保量。关注享5元优惠券。

每2天一次抽奖机会，菜单【聚优惠：抽奖】，长期优惠【聚优惠：免费吃果果】。

买水果，菜单【果果商城：逛商城】。
';
            } else {
                return '欢迎关注成都果果佳，新鲜佳果，保质保量。关注享5元优惠券。

每2天一次抽奖机会，菜单【聚优惠：抽奖】，长期优惠【聚优惠：免费吃果果】。

买水果，菜单【果果商城：逛商城】。

' . $str;
            }
            
        }

        if ($event == 'unsubscribe') {
            Customer::updateAll(['is_subscribe' => 0], ['openid' => $openid]);
        }

        if ($event == 'SCAN') {
            if (!empty($eventKey)) {
                if (preg_match('/uid_/', $eventKey)) {
                    return "欢迎关注成都果果佳，新鲜佳果，保质保量";
                }

                if (preg_match('/coupon_/', $eventKey)) {
                    return $eventKey;
                }

                return PriceHelper::handlePrize($eventKey, $openid);
            } else {
                return "欢迎关注成都果果佳，新鲜佳果，保质保量";
            }
        }

        if ($event == 'CLICK') {
            $key = $data['EventKey'];
            if ($key == 'company') {
                return '成都果果佳科技有限公司成立于2017年，致力于为广大客户提供优质且价格合理的商品。

目前主营精品水果、干果等相关商品，采用零售、预约的方式服务于广大客户。

以诚为本，全心全意为大家服务';
            }

            if ($key == 'service') {
                return '客服：13880494109 (同微信号)';
            }
        }

        return '';
    }

    public function actionTest() {
        echo WechatHelper::getAccessToken();
    }
}
