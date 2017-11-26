<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\components\SiteHelper;
use app\modules\product\models\ProductList;
use app\components\PriceHelper;

class PackageController extends Controller
{
    public $layout = 'page';
    public $bookingDiscount = 0.9;

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

    public function actionOrder() {
        return $this->render('order', [
            'controller' => Yii::$app->controller->id,
            'fruits' => $this->getFruits(),
        ]);
    }

    private function getFruits() {
        $info = ProductList::find()->select('id,name,price,desc,slogan,link,img,unit,category')->where(['category' => '水果'])->asArray()->all();

        foreach($info as $key => $value) {
            $info[$key]['promotion_price'] = PriceHelper::getProductPrice($value['id'], $value['price']) * 0.9;
            if (empty($value['img'])) {
                $info[$key]['img'] = 'http://img.yis188.com/images/company/room.jpeg';
            }
        }

        return $info;
    }
}
