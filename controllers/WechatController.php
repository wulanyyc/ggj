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

    /**
     * 入口
     * @return
     */
    public function actionIndex() {
        $params = Yii::$app->request->get();
        $echostr = $params['echostr'];

        if (WechatHelper::checkSignature()) {
            echo $echostr;
        } else {
            echo '';
        }
    }
}
