<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\components\SiteHelper;
use app\modules\product\models\ProductList;
use app\components\PriceHelper;

class SearchController extends Controller
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
        $params = Yii::$app->request->get();
        $kv     = $params['keyword'];

        $products = $this->getProducts($kv);
        return $this->render('index', [
            'controller' => Yii::$app->controller->id,
            'products' => $products,
            'num' => count($products),
            'kv'  => $kv,
        ]);
    }

    private function getProducts($kv) {
        // TODO
        $data = ProductList::find()->where(['like', 'name', $kv])->orWhere(['like', 'slogan', $kv])->asArray()->all();

        foreach($data as $key => $value) {
            $data[$key]['promotion_price'] = PriceHelper::getProductPrice($value['id']);
            if (empty($value['img'])) {
                $data[$key]['img'] = '/img/alpha_4x3.png';
            }
        }

        return $data;
    }
}
