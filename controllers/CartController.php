<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\components\SiteHelper;
use app\components\PriceHelper;
use app\modules\product\models\ProductList;
use app\models\ProductCart;
use app\models\ProductOrder;
use app\models\Address;
use app\modules\product\models\Coupon;
use app\modules\product\models\CouponUse;
use app\models\Customer;
use app\filters\CustomerFilter;

class CartController extends Controller
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
            'customer' => [
                'class' => CustomerFilter::className(),
                'actions' => [
                   
                ]
            ]
        ];
    }

    /**
     * 入口
     * @return
     */
    public function actionIndex() {
        $params = Yii::$app->request->get();
        $id = $params['id'];

        if (empty($id)) {
            Yii::$app->controller->redirect('/');
            Yii::$app->end();
        }

        $exsit = ProductCart::find()->where(['id' => $id])->count();
        if ($exsit == 0) {
            Yii::$app->controller->redirect('/');
            Yii::$app->end();
        }

        // 修正购物车内为最新价格
        $data = $this->getFixedData($id);

        // 安全校验
        $cid = SiteHelper::getCustomerId();
        if ($data['customer_id'] != $cid) {
            Yii::$app->controller->redirect('/');
            Yii::$app->end();
        }

        if ($data['order_type'] == 1) {
            $buyLimit = Yii::$app->params['buyLimit'];
            $buyGod   = Yii::$app->params['buyGod'];
        } else {
            $buyLimit = Yii::$app->params['bookingLimit'];
            $buyGod   = Yii::$app->params['bookingGod'];
        }

        return $this->render('index', [
            'controller' => Yii::$app->controller->id,
            'data' => $data,
            'address' => $this->getUserAddress($cid),
            'citymap' => Yii::$app->params['citymap']['成都'],
            'coupon' => count(PriceHelper::getValidCoupon()),
            'discount_start' => Yii::$app->params['discount']['start'],
            'discount_end'   => Yii::$app->params['discount']['end'],
            'buyLimit' => $buyLimit,
            'buyGod'   => $buyGod,
            'stdExpressFee' => Yii::$app->params['expressFee'],
        ]);
    }

    private function getFixedData($id) {
        $data = ProductCart::find()->where(['id' => $id])->asArray()->one();
        $cart = json_decode($data['cart'], true);

        $data['product'] = [];
        foreach($cart as $key => $value) {
            $tmpProduct = ProductList::find()->where(['id' => $value['id']])->asArray()->one();
            $tmpProduct['price'] = PriceHelper::getProductPrice($tmpProduct['id'], $data['order_type']);
            $cart[$key]['price'] = $tmpProduct['price'];
            $data['product'][] = $tmpProduct;
        }

        if (count($data['product']) > 3) {
            $data['show_product'] = array_slice($data['product'], 0, 3);
        } else {
            $data['show_product'] = $data['product'];
        }

        $data['product_cart'] = $cart;
        $data['product_price'] = PriceHelper::calculateProductPrice($id);

        $orderData = ProductOrder::find()->select('express_rule, express_fee')->where(['cart_id' => $id])->asArray()->one();

        if (empty($orderData)) {
            $data['express_rule'] = 1;
            $data['express_fee'] = PriceHelper::calculateExpressFee($data['express_rule'], $data['order_type'], $data['product_price']);
        } else {
            $data['express_rule'] = $orderData['express_rule'];
            $data['express_fee'] = $orderData['express_fee'];
        }

        return $data;
    }

    public function actionAdd() {
        $params = Yii::$app->request->post();
        if (empty($params)){
            SiteHelper::render('fail', '提交的参数不能为空');
        }

        if (!$this->checkProductPrice($params)) {
            SiteHelper::render('fail', '提交的价格数据，验证失败');
        }

        $oid = $params['oid'];
        unset($params['oid']);

        $params['customer_id'] = SiteHelper::getCustomerId();

        if ($oid > 0) {
            $po = ProductCart::findOne($oid);
        } else {
            $po = new ProductCart();
        }

        foreach($params as $key => $value){
            $po->$key = $value;
        }

        if ($po->save()){
            SiteHelper::render('ok', $po->id);
        } else {
            SiteHelper::render('fail', '添加失败，请完善信息');
        }
    }

    private function checkProductPrice($params) {
        // 计算产品价格
        $carts = json_decode($params['cart'], true);

        $productPrice = 0;
        foreach($carts as $key => $item) {
            $id = $item['id'];
            if ($item['limit'] > 0 && $item['num'] > $item['limit']) {
                $orignalPrice = ProductList::find()->where(['id' => $id])->select('price')->scalar();
                $productPrice += ($item['num'] - $item['limit']) * $orignalPrice + $item['limit'] * PriceHelper::getProductPrice($id, $params['order_type']);
            } else {
                $productPrice += $item['num'] * PriceHelper::getProductPrice($id, $params['order_type']);
            }
        }

        $productPrice = round($productPrice, PriceHelper::$precison);

        if ($productPrice == $params['product_price']) {
            return true;
        } else {
            return false;
        }
    }

    public function actionCoupon() {
        $data = PriceHelper::getValidCoupon();
        $html = '';

        if (empty($data)) {
            SiteHelper::render('fail', '很抱歉，您账户里没有可用的优惠券');
        } else {
            foreach($data as $key => $value) {
                $info = Coupon::find()->where(['id' => $value['cid']])->asArray()->one();
                $info['start_date'] = date('Y.m.d', strtotime($info['start_date']));
                $info['end_date']   = date('Y.m.d', strtotime($info['end_date']));

                $html .= <<<EOF
                <div class="coupon_item">
        <p class="coupon_item_label">{$info['name']}</p>
        <div class="coupon_item_text">
          <p class="coupon_item_money text-danger">{$info['money']}元</p>
          <p class="coupon_item_date">{$info['start_date']}～{$info['end_date']}有效</p>
        </div>
        <div class="coupon_check" id="coupon_{$info['id']}" data-id={$info['id']} data-money={$info['money']}><i class="fa fa-square-o" aria-hidden="true"></i></div>
    </div>
EOF;
            }
        }

        SiteHelper::render('ok', $html);
    }

    /*
     * 使用好友手机号码，获取折扣
    */
    public function actionDiscount() {
        $cid = SiteHelper::getCustomerId();

        $params = Yii::$app->request->post();
        $friendPhone = $params['phone'];
        $cartId = $params['cid'];

        $userphone = SiteHelper::getCustomerPhone($cid);
        if (!SiteHelper::checkPhone($friendPhone)) {
            SiteHelper::render('fail', '好友手机格式有误');
        }

        if ($userphone == $friendPhone) {
            SiteHelper::render('fail', '不能使用自己的手机号码');
        }

        $areas = SiteHelper::getPhoneArea($friendPhone);
        $areaArr = json_decode($areas, true);

        if ($areaArr['status'] == 0) {
            $key = $cid . '_' . $friendPhone . '_discount';
            $percent = Yii::$app->redis->get($key);

            $productPrice = ProductCart::find()->where(['id' => $cartId])->select('product_price')->scalar();

            if ($percent > 0) {
                SiteHelper::render('ok', round($percent * $productPrice, PriceHelper::$precison));
            } else {
                $percent = rand(Yii::$app->params['discount']['start'], Yii::$app->params['discount']['end']) / 100;
                Yii::$app->redis->setex($key, 3600, $percent);
                SiteHelper::render('ok', round($percent * $productPrice, PriceHelper::$precison));
            }
        } else {
            SiteHelper::render('fail', '好友手机号码错误, 请检查');
        }
    }

    private function getUserAddress($cid) {
        return Address::find()->where(['customer_id' => $cid])->orderBy('id desc')->asArray()->all();
    }
}
