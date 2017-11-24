<?php

namespace app\controllers;

use yii\web\Controller;

class ErrorController extends Controller
{
    public $layout = 'full';
    /**
     * action配置
     * @return array
     */
    public function actions()
    {
        return [
            'index' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }
    
}
