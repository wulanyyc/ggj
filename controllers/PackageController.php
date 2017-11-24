<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\components\SiteHelper;
use app\modules\product\models\ProductList;
use app\components\PriceHelper;

class PackageController extends Controller
{
    // private $isVisit = false;
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

    public function actionOrder() {
        return $this->render('order', [
            'controller' => Yii::$app->controller->id,
            'products' => $this->getProducts(),
        ]);
    }

    private function getProducts() {
        $info = ProductList::find()->select('id,name,price,desc,slogan,link,img,unit,category')->where(['category' => '水果'])->asArray()->all();

        foreach($info as $key => $value) {
            $info[$key]['promotion_price'] = PriceHelper::getProductPrice($value['id'], $value['price']);
            if (empty($value['img'])) {
                $info[$key]['img'] = 'http://img.yis188.com/images/company/room.jpeg';
            }
        }

        return $info;
    }
}
