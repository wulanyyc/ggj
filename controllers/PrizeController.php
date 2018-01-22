<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;

class PrizeController extends Controller
{
    public $layout = 'wap';
    
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
    
}
