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

        if ($cid == 0 && isset($_COOKIE['cart_id'])) {
            $orderType = ProductCart::find()->where(['id' => $_COOKIE['cart_id']])->select('order_type')->scalar();
            if ($orderType == 1) {
                $cid = $_COOKIE['cart_id'];
            }
        }

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

        $dayofweek = date('w', time());
        return $this->render('index', [
            'controller' => Yii::$app->controller->id,
            'id' => $id,
            'cid' => $cid,
            'buyGod' => $this->buyGod,
            'buyLimit' => $this->buyLimit,
            'expressFee' => $this->expressFee,
            'cart' => !empty($cart) ? json_encode($cart) : '',
            'products' => $this->getProducts($orderType, $id),
            'categorys' => $this->getCategorys($orderType, $id),
            'orderType' => $orderType,
            'special' => Yii::$app->params['new_promotion']['id'],
            'today' => Yii::$app->params['day_promotion'][$dayofweek]['id'],
        ]);
    }

    public function actionType() {
        $params = Yii::$app->request->get();
        $id = isset($params['id']) ? $params['id'] : 0;
        return $this->render('type', [
            'controller' => Yii::$app->controller->id,
            'id' => $id,
            'bookingSender' => Yii::$app->params['bookingSender'],
        ]);
    }

    public function actionBooking() {
        $params = Yii::$app->request->get();
        $id = isset($params['id']) ? $params['id'] : 0;
        $cid = isset($params['cid']) ? $params['cid'] : 0;

        if ($cid == 0 && isset($_COOKIE['cart_id'])) {
            $orderType = ProductCart::find()->where(['id' => $_COOKIE['cart_id']])->select('order_type')->scalar();
            if ($orderType == 2) {
                $cid = $_COOKIE['cart_id'];
            }
        }

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

        $dayofweek = date('w', time());
        return $this->render('booking', [
            'controller' => Yii::$app->controller->id,
            'id' => $id,
            'cid' => $cid,
            'buyGod' => $this->bookingGod,
            'buyLimit' => $this->bookingLimit,
            'expressFee' => $this->expressFee,
            'cart' => !empty($cart) ? json_encode($cart) : '',
            'products' => $this->getProducts($orderType, $id),
            'categorys' => $this->getCategorys($orderType, $id),
            'orderType' => $orderType,
            'special' => Yii::$app->params['new_promotion']['id'],
            'today' => Yii::$app->params['day_promotion'][$dayofweek]['id'],
            'bookingSender' => Yii::$app->params['bookingSender'],
        ]);
    }

    private function getCategorys($orderType, $id = 0) {
        if ($id == 0) {
            $seller = 1;
        } else {
            $seller = ProductList::find()->select('seller_id')->where(['id' => $id])->scalar();
        }

        if ($orderType == 2) {
            $info = ProductList::find()->select('category')
            ->where(['status' => 1, 'deleteflag' =>  0, 'seller_id' => $seller])->andWhere(['!=', 'booking_status', 3])->distinct(true)
            ->asArray()->all();
        }

        if ($orderType == 1) {
            $info = ProductList::find()->select('category')
            ->where(['status' => 1, 'deleteflag' =>  0, 'seller_id' => $seller])->andWhere(['>', 'num', 0])->distinct(true)
            ->asArray()->all();
        }

        $ret = [];
        foreach($info as $item) {
            $ret[$item['category']] = CategoryWidget::getCn($item['category']);
        }

        return $ret;
    }

    private function getProducts($orderType, $id = 0) {
        if ($id == 0) {
            $seller = 1;
        } else {
            $seller = ProductList::find()->select('seller_id')->where(['id' => $id])->scalar();
        }

        if ($orderType == 2) {
            $info = ProductList::find()->select('id,name,price,num,buy_limit,desc,slogan,link,img,unit,category')
            ->where(['status' => 1, 'deleteflag' =>  0, 'seller_id' => $seller])->andWhere(['!=', 'booking_status', 3])->asArray()->all();
        }

        if ($orderType == 1) {
            $info = ProductList::find()->select('id,name,price,num,buy_limit,desc,slogan,link,img,unit,category')->where(['status' => 1, 'deleteflag' =>  0, 'seller_id' => $seller])->andWhere(['>', 'num', 0])->andWhere(['!=', 'booking_status', 2])
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

    public function actionImgs() {
        $params = Yii::$app->request->post();
        $id = $params['pid'];

        $imgs = ProductList::find()->select('img,img1,img2,img3')->where(['id' => $id])->asArray()->one();

        $data = [];
        foreach($imgs as $item) {
            if (!empty($item)) {
                $data[] = ['img' => $item];
            }
        }

        $ctrHtml = '<ol class="carousel-indicators">';
        $imgHtml = '<div class="carousel-inner">';

        foreach($data as $key => $item) {
            if ($key == 0) {
                $ctrHtml .= '<li data-target="#carouselIndicators" data-slide-to="'. $key .'" class="active"></li>';

                $imgHtml .= '<div class="carousel-item active"><img src="' .$item['img']. '" style="width:100%;"/></div>';
            } else {
                $ctrHtml .= '<li data-target="#carouselIndicators" data-slide-to="'. $key .'"></li>';
                $imgHtml .= '<div class="carousel-item"><img src="' .$item['img']. '" style="width:100%;"/></div>';
            }
        }

        $ctrHtml .= '</ol>';
        $imgHtml .= '</div>';

  //       $endHtml = '<a class="carousel-control-prev" href="#carouselIndicators" role="button" data-slide="prev">
  //   <span class="carousel-control-prev-icon" aria-hidden="true"></span>
  //   <span class="sr-only">Previous</span>
  // </a>
  // <a class="carousel-control-next" href="#carouselIndicators" role="button" data-slide="next">
  //   <span class="carousel-control-next-icon" aria-hidden="true"></span>
  //   <span class="sr-only">Next</span>
  // </a>';

        echo '<div id="carouselIndicators" class="carousel slide" data-ride="carousel">' . $ctrHtml . $imgHtml . '</div>';
    }
}
