<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\components\SiteHelper;
use app\filters\WechatFilter;

class BookingController extends Controller
{
    public $layout = 'wap';
    public $bookingDiscount = 9;
    public $bookingLimit = 39;
    public $bookingGod = 59;
    public $expressFee = 6;

    private $configKeys = [
        'current-skin',
    ];


    public function behaviors() {
        return [
            'wechat' => [
                'class' => WechatFilter::className(),
            ]
        ];
    }

    public function init() {
        $this->layout = SiteHelper::getLayout();
        $this->bookingDiscount = Yii::$app->params['bookingDiscount'] * 10;
        $this->bookingLimit = Yii::$app->params['bookingLimit'];
        $this->bookingGod = Yii::$app->params['bookingGod'];
        $this->expressFee = Yii::$app->params['expressFee'];
    }

    /**
     * 入口
     * @return
     */
    public function actionIndex() {
        return $this->render('index', [
            'controller' => Yii::$app->controller->id,
            'bookingDiscount' => $this->bookingDiscount,
            'bookingLimit' => $this->bookingLimit,
            'bookingGod' => $this->bookingGod,
            'expressFee' => $this->expressFee,
        ]);
    }
}
