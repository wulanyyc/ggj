<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;

class PrizeController extends Controller
{
    public $layout = 'wap';
    
    public function actionIndex() {
        $params = Yii::$app->request->get();

        print_r($params);exit;
    }
    
}
