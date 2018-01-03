<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\components\WechatHelper;

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
                $parse = new \WXBizMsgCrypt($config['token'], $config['encodingAESKey'], $config['wxid']);
                $errCode = $parse->decryptMsg($_GET['msg_signature'], $_GET['timestamp'], $_GET['nonce'], $body, $receiveMsg);

                if ($errCode == 0) {
                    // $xmlparse = new \XMLParse();
                    // $user = $xmlparse->extract($body)[2];
                    $data = simplexml_load_string($receiveMsg);
                    Yii::error($data);
                    echo '';
                    Yii::$app->end();

                    $replyMsg = WechatHelper::renderText([
                        'user' => $user,
                        'appid' => $config['wxid'],
                        'msg' => $receiveMsg,
                    ]);

                    Yii::error($replyMsg);

                    $encryptMsg = '';
                    $code = $parse->encryptMsg($replyMsg, $_GET['timestamp'], $_GET['nonce'], $encryptMsg);

                    if ($code == 0) {
                        // header("Content-Type", "application/xml; charset=UTF-8");
                        echo $encryptMsg;
                        Yii::error($encryptMsg);
                        Yii::$app->end();
                    }
                }
            }
            echo '';
        }
    }

    public function actionTest() {
        echo WechatHelper::getAccessToken();
    }
}
