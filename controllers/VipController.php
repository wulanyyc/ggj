<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\components\SiteHelper;
use app\components\PriceHelper;
use app\modules\product\models\ProductList;

class VipController extends Controller
{
    public $layout = 'wap';

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
        $id = Yii::$app->params['vip_productid'];
        $price = PriceHelper::getProductPrice($id, 2);

        return $this->render('index', [
            'controller' => Yii::$app->controller->id,
            'id' => $id,
            'price' => $price,
        ]);
    }
}
