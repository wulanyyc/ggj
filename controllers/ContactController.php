<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\components\SiteHelper;

class ContactController extends Controller
{
    public $layout = 'page';

    private $configKeys = [
        'current-skin',
    ];

    public function init() {
        $this->layout = SiteHelper::getLayout();
    }

    /**
     * 入口
     * @return
     */
    public function actionIndex() {
        return $this->render('index', [
            'controller' => Yii::$app->controller->id,
        ]);
    }
}
