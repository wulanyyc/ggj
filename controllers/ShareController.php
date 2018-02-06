<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\components\SiteHelper;
use app\components\WechatHelper;
use app\filters\WechatFilter;

class ShareController extends Controller
{
    public $layout = 'wap';

    private $configKeys = [
        'current-skin',
    ];

    public function init() {
        $this->layout = SiteHelper::getLayout();
    }

    public function behaviors() {
        return [
            'wechat' => [
                'class' => WechatFilter::className(),
            ]
        ];
    }

    /**
     * 入口
     * @return
     */
    public function actionIndex() {
        $cid = SiteHelper::getCustomerId();
        $params = Yii::$app->request->get();

        $customerId = SiteHelper::getCustomerId();

        if (empty($customerId)) {
            return $this->render('error', [
                'controller' => Yii::$app->controller->id,
            ]);
        }

        $qrData = WechatHelper::getShareqrcode($customerId);
        $ticket = isset($qrData['ticket']) ? $qrData['ticket'] : '';

        return $this->render('index', [
            'controller' => Yii::$app->controller->id,
            'share' => 2,
            'percent' => 3,
            'ticket' => urlencode($ticket),
        ]);
    }
}
