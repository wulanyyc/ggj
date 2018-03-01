<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\components\SiteHelper;
use app\components\PriceHelper;
use app\modules\product\models\ProductList;
use app\filters\WechatFilter;

class VipController extends Controller
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
        $id = Yii::$app->params['vip_productid'];
        $price = PriceHelper::getProductPrice($id);

        return $this->render('index', [
            'controller' => Yii::$app->controller->id,
            'id' => $id,
            'price' => $price,
        ]);
    }
}
