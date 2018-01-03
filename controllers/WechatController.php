<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\components\WechatHelper;
use app\models\CustomerWeixin;

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

                        $replyMsg = WechatHelper::renderText([
                            'user' => $data['FromUserName'],
                            'wxid' => $data['ToUserName'],
                            'msg'  => $content,
                        ]);
                    }

                    // 事件类型
                    if ($msgType == 'event') {
                        $content = $this->handleEvent($data);

                        echo '';
                        Yii::$app->end();
                    }

                    if (empty($replyMsg)) {
                        echo '';
                        Yii::$app->end();
                    }

                    $encryptMsg = '';
                    $code = $parse->encryptMsg($replyMsg, time(), $_GET['nonce'], $encryptMsg);

                    if ($code == 0) {
                        header("Content-Type", "application/xml; charset=UTF-8");
                        echo $encryptMsg;
                        Yii::$app->end();
                    }
                }
            }
            echo '';
        }
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
            $userinfo = WechatHelper::getUserInfo($openid);
            Yii::error($userinfo);

            if (!empty($userinfo)) {
                $ar = new CustomerWeixin();
                $ar->openid = $userinfo['openid'];
                $ar->sex = $userinfo['sex'];
                $ar->is_subscribe = $userinfo['subscribe'];
                $ar->headimgurl = $userinfo['headimgurl'];
                $ar->city = $userinfo['city'];
                $ar->nickname = $userinfo['nickname'];
                if (isset($userinfo['unionid'])) {
                    $ar->unionid = $userinfo['unionid'];
                }
                $ar->save();
            }
        }

        if ($event == 'unsubscribe') {
            CustomerWeixin::updateAll(['is_subscribe' => 0], ['openid' => $openid]);
        }
    }

    public function actionTest() {
        echo WechatHelper::getAccessToken();
    }
}
