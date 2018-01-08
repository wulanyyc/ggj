<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\components\SiteHelper;
use app\modules\product\models\ProductList;
use app\components\PriceHelper;
use app\models\ProductCart;
use app\widgets\CategoryWidget;

class BuyController extends Controller
{
    public $layout = 'wap';
    public $bookingDiscount = 0.9; // 预订优惠折扣
    public $bookingLimit = 39; // 预订最低消费
    public $bookingGod = 59; // 预订免运费限额
    public $buyDiscount = 1; // 普通购买优惠折扣
    public $buyGod = 49; // 普通免运费限额
    public $buyLimit = 19; // 普通最低消费
    public $expressFee = 6; // 快递费

    private $configKeys = [
        'current-skin',
    ];

    public function init() {
        $this->layout = SiteHelper::getLayout();
        $this->bookingDiscount = Yii::$app->params['bookingDiscount'];
        $this->bookingLimit = Yii::$app->params['bookingLimit'];
        $this->bookingGod = Yii::$app->params['bookingGod'];
        $this->buyDiscount = Yii::$app->params['buyDiscount'];
        $this->buyGod = Yii::$app->params['buyGod'];
        $this->buyLimit = Yii::$app->params['buyLimit'];
        $this->expressFee = Yii::$app->params['expressFee'];
    }

    /**
     * 入口
     * @return
     */
    public function actionIndex() {
        $params = Yii::$app->request->get();
        $id = isset($params['id']) ? $params['id'] : 0;
        $cid = isset($params['cid']) ? $params['cid'] : 0;
        $orderType = 1;

        if ($cid != 0) {
            $exsit = ProductCart::find()->where(['id' => $cid])->count();
            if ($exsit == 0) {
                Yii::$app->controller->redirect('/buy');
                Yii::$app->end();
            }
            
            $cart = PriceHelper::getUpdateCart($cid);
        } else {
            $cart = '';
        }

        return $this->render('index', [
            'controller' => Yii::$app->controller->id,
            'id' => $id,
            'cid' => $cid,
            'buyGod' => $this->buyGod,
            'buyLimit' => $this->buyLimit,
            'expressFee' => $this->expressFee,
            'cart' => !empty($cart) ? json_encode($cart) : '',
            'products' => $this->getProducts($orderType),
            'categorys' => $this->getCategorys(),
            'orderType' => $orderType,
        ]);
    }

    public function actionType() {
        $params = Yii::$app->request->get();
        $id = isset($params['id']) ? $params['id'] : 0;
        return $this->render('type', [
            'controller' => Yii::$app->controller->id,
            'id' => $id,
        ]);
    }

    public function actionBooking() {
        $params = Yii::$app->request->get();
        $id = isset($params['id']) ? $params['id'] : 0;
        $cid = isset($params['cid']) ? $params['cid'] : 0;
        $orderType = 2;

        if ($cid != 0) {
            $exsit = ProductCart::find()->where(['id' => $cid])->count();
            if ($exsit == 0) {
                Yii::$app->controller->redirect('/buy/booking');
                Yii::$app->end();
            }

            $cart = PriceHelper::getUpdateCart($cid);
        } else {
            $cart = [];
        }

        return $this->render('booking', [
            'controller' => Yii::$app->controller->id,
            'id' => $id,
            'cid' => $cid,
            'buyGod' => $this->bookingGod,
            'buyLimit' => $this->bookingLimit,
            'expressFee' => $this->expressFee,
            'cart' => !empty($cart) ? json_encode($cart) : '',
            'products' => $this->getProducts($orderType),
            'categorys' => $this->getCategorys(),
            'orderType' => $orderType,
        ]);
    }

    private function getCategorys() {
        $info = ProductList::find()->select('category')->where(['status' => 1])->distinct(true)
            ->asArray()->all();

        $ret = [];
        foreach($info as $item) {
            $ret[$item['category']] = CategoryWidget::getCn($item['category']);
        }

        return $ret;
    }

    private function getProducts($orderType) {
        if ($orderType == 2) {
            $info = ProductList::find()->select('id,name,price,num,buy_limit,desc,slogan,link,img,unit,category')
            ->where(['status' => 1])->asArray()->all();
        }

        if ($orderType == 1) {
            $info = ProductList::find()->select('id,name,price,num,buy_limit,desc,slogan,link,img,unit,category')->where(['status' => 1])->andWhere(['>', 'num', 0])
            ->asArray()->all();
        }


        $ret = [];
        foreach($info as $key => $value) {
            $info[$key]['promotion_price'] = PriceHelper::getProductPrice($value['id'], $orderType);
            if (empty($value['img'])) {
                $info[$key]['img'] = '/img/apple_4x3.png';
            }

            $ret[$value['category']][] = $info[$key];
        }

        return $ret;
    }
}
