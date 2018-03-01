<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\components\SiteHelper;
use app\modules\product\models\ProductList;
use app\components\PriceHelper;
use app\components\ProductHelper;
use app\filters\WechatFilter;

class SearchController extends Controller
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
            $data[$key]['link'] = ProductHelper::getProductLink($value['id']);

            if ($value['booking_status'] != 2) {
                $data[$key]['buy_price'] = PriceHelper::getProductPrice($value['id']);
            }

            if ($value['booking_status'] != 3) {
                $data[$key]['booking_price'] = PriceHelper::getProductPrice($value['id']);
            }

            if (empty($value['img'])) {
                $data[$key]['img'] = '/img/alpha_4x3.png';
            }
        }

        return $data;
    }
}
