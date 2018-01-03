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
            $params = Yii::$app->request->post();
            Yii::error(json_encode($params));
            echo '';
        }
    }

    public function actionTest() {
        echo WechatHelper::getAccessToken();
    }
}
