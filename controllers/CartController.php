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
use app\models\GiftUse;
use app\models\Gift;

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

        $exsit = ProductCart::find()->where(['id' => $id])->asArray()->one();
        if (empty($exsit)) {
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
            'city' => array_keys(Yii::$app->params['citymap']),
            'citymap' => Yii::$app->params['citymap']['成都市'],
            'coupon' => count(PriceHelper::getValidCartCoupon($exsit['id'])),
            'gift' => count(PriceHelper::getValidGift()),
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

    public function actionGetcitymap() {
        $params = Yii::$app->request->post();
        $city = isset($params['city']) ? $params['city'] : '';

        if (empty($city)) {
            echo '';
            Yii::$app->end();
        } else {
            $districts = Yii::$app->params['citymap'][$city];
            if (empty($districts)) {
                echo '';
            } else {
                $html = '';
                foreach($districts as $item) {
                    $html .= '<option value="' . $item .'">' . $item . '</option>';
                }
                echo $html;
            }
        }
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
        // return true;
        // 计算产品价格
        $carts = json_decode($params['cart'], true);

        $productPrice = 0;
        foreach($carts as $key => $item) {
            $id = $item['id'];
            if (isset($item['limit']) && $item['limit'] > 0 && $item['num'] > $item['limit']) {
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
        $html = '';

        $params = Yii::$app->request->post();
        $cid = !empty($params['cid']) ? $params['cid'] : 0;

        $data = PriceHelper::getValidCartCoupon($cid);
        $customerId = SiteHelper::getCustomerId();

        $price = ProductCart::find()->where(['id' => $cid, 'customer_id' => $customerId])->select('product_price')->scalar();

        if (empty($price)) {
            $price = 0;
        }

        if (empty($data)) {
            SiteHelper::render('fail', '很抱歉，账户里没有可用的券');
        } else {
            foreach($data as $key => $value) {
                $info = Coupon::find()->where(['id' => $value['cid']])->asArray()->one();

                $dayDiff = ceil((strtotime($info['end_date']) - time()) / 86400);

                $html .= <<<EOF
                    <div class="coupon_item">
                        <div class="coupon_item_content">
                            <img src="http://img.guoguojia.vip/img/icon/coupon_use.jpeg?v=1" class="coupon_item_content_img" />
                            <div class="coupon_item_label">{$info['name']}</div>
                            <div class="coupon_item_text">
                              <div class="coupon_item_money">{$info['money']}元</div>
                              <div class="coupon_item_date">剩{$dayDiff}天到期</div>
                            </div>
                        </div>
                        <div class="coupon_check" id="coupon_{$value['id']}" data-id={$value['id']} data-money={$info['money']}>
                            <i class="fa fa-square-o" aria-hidden="true"></i>
                        </div>
                    </div>
EOF;
            }
        }

        SiteHelper::render('ok', $html);
    }

    public function actionGift() {
        $html = '';
        $data = PriceHelper::getValidGift();

        if (empty($data)) {
            SiteHelper::render('fail', '很抱歉，账户里没有可用的礼品，请去抽奖领取');
        } else {
            foreach($data as $key => $value) {
                $info = Gift::find()->where(['id' => $value['gid']])->asArray()->one();
                $html .= <<<EOF
                    <div class="gift_item">
                        <div class="gift_item_content">
                            <div class="gift_item_label">
                                <i class="fa fa-gift" aria-hidden="true" style="color:red;font-size:20px;"></i>
                                {$info['name']}
                            </div>
                        </div>
                        <div class="gift_check" style="font-size:20px;" id="gift_{$value['id']}" data-id={$value['id']}>
                            <i class="fa fa-square-o" aria-hidden="true"></i>
                        </div>
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

    public function actionGetexpressrule() {
        $params = Yii::$app->request->post();
        if (empty($params)){
            SiteHelper::render('fail', '提交的参数不能为空');
        }

        $cid = $params['cid'];

        $rule = ProductOrder::find()->where(['cart_id' => $cid])->select('express_rule')->scalar();

        if (empty($rule)) $rule = 1;
        SiteHelper::render('ok', $rule);
    }
}
