<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\components\CommonHelper;
use app\modules\right\models\User;

class AuthController extends Controller
{
    /**
     * 入口限制：浏览器检测，用户认证
     * @return array
     */
    public function behaviors() {
        return [
            'browser' => [
                'class' => 'app\filters\BrowserFilter',
            ],
            'auth' => [
                'class' => 'app\filters\AuthFilter',
            ],
            'cookie' => [
                'class' => 'app\filters\CookieFilter',
            ],
            'pageset' => [
                'class' => 'app\components\PagesetBehavior',
            ],
        ];
    }
}
