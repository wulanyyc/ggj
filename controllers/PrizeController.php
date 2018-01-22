<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;

class PrizeController extends Controller
{
    public $layout = 'blank';
    
    public function actionIndex() {
        $params = Yii::$app->request->get();

        $sid  = 0;
        $from = 0;

        if (!empty($params)) {
            $sid = isset($params['share_id']) ? $params['share_id'] : 0;
            $from = isset($params['from']) ? $params['from'] : 0;
        }
        
        return $this->render('index', [
            'controller' => Yii::$app->controller->id,
        ]);
    }

    public function actionGetrotate() {
        $rotato = 360 + rand(0, 360);

        if (empty($_COOKIE['uniqid'])) {
            $uniq = uniqid();
            setcookie('uniqid', $uniq, time() + 86400, '/');
        } else {
            $uniq = $_COOKIE['uniqid'];
        }

        if ($rotato / 45 == 0) {
            $rotato += 22;
        }

        $cntKey = $uniq . '_cnt';
        Yii::$app->redis->setex($uniq, 86400 * 7, $rotato);

        $cnt = Yii::$app->redis->get($cntKey);

        if ($cnt >= 20) {
            echo 0;
            Yii::$app->end();
        }

        if ($cnt > 0) {
            Yii::$app->redis->setex($cntKey, 86400 * 7, $cnt + 1);
        } else {
            Yii::$app->redis->setex($cntKey, 86400 * 7, 1);
        }

        echo $rotato;
    }
}
