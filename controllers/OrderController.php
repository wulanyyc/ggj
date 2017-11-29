<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\components\SiteHelper;
use app\modules\product\models\ProductList;
use app\components\PriceHelper;
use app\models\ProductOrder;

class OrderController extends Controller
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
        $phone = isset($_COOKIE['cellphone']) ? $_COOKIE['cellphone'] : '';

        if (empty($phone)) {
            return $this->render('login', [
                'controller' => Yii::$app->controller->id,
            ]);
        }

        $data = ProductOrder::find()->where(['cellphone' => $phone])->asArray()->all();

        return $this->render('index', [
            'controller' => Yii::$app->controller->id,
            'data' => $data,
        ]);
    }

    public function actionDetail() {
        $params = Yii::$app->request->get();
        $id = $params['id'];
        $data = ProductOrder::find()->where(['id' => $id])->asArray()->one();

        $phone = isset($_COOKIE['cellphone']) ? $_COOKIE['cellphone'] : '';

        if ($data['cellphone'] != $phone) {
            return $this->render('login', [
                'controller' => Yii::$app->controller->id,
            ]);
        }

        $cart = json_decode($data['cart'], true);

        $data['product'] = [];
        $data['product_cart'] = $cart;

        foreach($cart as $key => $value) {
            $data['product'][] = ProductList::find()->where(['id' => $value['id']])->asArray()->one();
        }

        return $this->render('detail', [
            'controller' => Yii::$app->controller->id,
            'data' => $data,
        ]);
    }

    public function actionLogin() {
        return $this->render('login', [
            'controller' => Yii::$app->controller->id,
        ]);
    }

}
