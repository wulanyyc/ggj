<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\components\WechatHelper;
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
            $echostr = $params['echostr'];

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
        return $content;
    }

    // TODO
    private function handleEvent($data) {
        $event  = $data['Event'];
        $openid = $data['FromUserName'];

        if ($event == 'subscribe') {
            // TODO 关注公众号，修改优惠id
            PriceHelper::createCoupon(Yii::$app->params['coupon']['subscribe'], $openid);

            $userinfo = WechatHelper::getUserInfo($openid);
            if (!isset($userinfo['errcode'])) {
                $exsit = CustomerWeixin::find()->where(['openid' => $openid])->asArray()->one();

                if (count($exsit) > 0) {
                    $ar = CustomerWeixin::findOne($exsit['id']);
                } else {
                    $ar = new CustomerWeixin();
                }

                $ar->openid = $userinfo['openid'];
                $ar->sex = $userinfo['sex'];
                $ar->is_subscribe = $userinfo['subscribe'];
                $ar->headimgurl = $userinfo['headimgurl'];
                $ar->city = $userinfo['city'];
                $ar->nickname = $userinfo['nickname'];
                $ar->subscribe_time = $userinfo['subscribe_time'];
                if (isset($userinfo['unionid'])) {
                    $ar->unionid = $userinfo['unionid'];
                }
                $ar->save();

                $exsitCus = Customer::find()->where(['openid' => $openid])->asArray()->one();
                if (count($exsitCus) > 0) {
                    $cusar = Customer::findOne($exsitCus['id']);
                } else {
                    $cusar = new Customer();
                }

                $cusar->openid = $userinfo['openid'];
                $cusar->sex = $userinfo['sex'];
                $cusar->headimgurl = $userinfo['headimgurl'];
                $cusar->city = $userinfo['city'];
                $cusar->nick = $userinfo['nickname'];
                $cusar->subscribe_time = $userinfo['subscribe_time'];
                if (isset($userinfo['unionid'])) {
                    $cusar->unionid = $userinfo['unionid'];
                }
                $cusar->save();

            }

            return '欢迎关注成都果果佳，新人享5元优惠券，首单再享5元';
        }

        if ($event == 'unsubscribe') {
            CustomerWeixin::updateAll(['is_subscribe' => 0], ['openid' => $openid]);
        }

        if ($event == 'CLICK') {
            $key = $data['EventKey'];
            if ($key == 'company') {
                return '成都果果佳科技有限公司成立于2017年底，致力于为广大客户提供优质且价格合理的商品。

目前主营水果、干果等相关商品，采用预约制、零售的方式服务于广大客户。

以诚为本，全心全意为大家服务';
            }

            if ($key == 'service') {
                return '客服：18980457783 (同微信号)
公司：13880494109 (同微信号)';
            }
        }

        return '';
    }

    public function actionTest() {
        echo WechatHelper::getAccessToken();
    }
}
