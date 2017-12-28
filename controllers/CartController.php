<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\components\SiteHelper;
use app\modules\product\models\ProductList;
use app\components\PriceHelper;
use app\models\ProductCart;
use app\models\ProductOrder;
use app\models\Address;
use app\modules\product\models\Coupon;
use app\modules\product\models\CouponUse;
use app\models\Customer;

class CartController extends Controller
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
        if (!SiteHelper::checkSecret()) {
            Yii::$app->controller->redirect('/customer/login');
        }

        $params = Yii::$app->request->get();
        $id = $params['id'];
        $data = ProductCart::find()->where(['id' => $id])->asArray()->one();

        $data['express_fee'] = SiteHelper::calculateExpressFee($data['type'], $data['product_price']);

        $phone = $_COOKIE['userphone'];

        // 安全校验
        if ($data['userphone'] != $phone) {
            Yii::$app->controller->redirect('/');
        }

        $cart = json_decode($data['cart'], true);

        $data['product'] = [];
        $data['product_cart'] = $cart;

        foreach($cart as $key => $value) {
            $data['product'][] = ProductList::find()->where(['id' => $value['id']])->asArray()->one();
        }

        $money = Customer::find()->select('money')->where(['phone' => $phone])->scalar();

        return $this->render('index', [
            'controller' => Yii::$app->controller->id,
            'data' => $data,
            'address' => $this->getUserAddress($phone),
            'citymap' => Yii::$app->params['citymap']['成都'],
            'coupon' => count(SiteHelper::getValidCoupon()),
        ]);
    }

    public function actionAdd() {
        if (!SiteHelper::checkSecret()) {
            echo '验证用户失败';
            Yii::$app->end();
        }

        $params = Yii::$app->request->post();
        if(empty($params)){
            echo '提交的参数不能为空';
            Yii::$app->end();
        }

        if (!$this->checkProductPrice($params)) {
            echo '提交的价格数据，验证失败';
            Yii::$app->end();
        }

        $oid = $params['oid'];
        unset($params['oid']);

        $params['userphone'] = $_COOKIE['userphone'];
        // $params['express_fee'] = SiteHelper::calculateExpressFee($params['type'], $params['product_price']);

        if ($oid > 0) {
            $po = ProductCart::findOne($oid);
            foreach($params as $key => $value){
                $po->$key = $value;
            }

        } else {
            $po = new ProductCart();
            foreach($params as $key => $value){
                $po->$key = $value;
            }
        }

        if($po->save()){
            echo $po->id;
        }else{
            echo '请完善订单信息';
        }
    }

    private function checkProductPrice($params) {
        // 计算产品价格
        $carts = json_decode($params['cart'], true);

        $productPrice = 0;
        foreach($carts as $item) {
            $id = $item['id'];
            $price = ProductList::find()->where(['id' => $id])->select('price')->scalar();
            $productPrice += $item['num'] * PriceHelper::getProductPrice($id, $params['type']);
        }

        $productPrice = round($productPrice, 1);

        if ($productPrice == $params['product_price']) {
            return true;
        } else {
            return false;
        }
    }

    public function actionCoupon() {
        $data = SiteHelper::getValidCoupon();
        $html = '';

        if (empty($data)) {
            $html = '很抱歉，您账户里没有可用的优惠券';
        } else {
            foreach($data as $key => $value) {
                $value['start_date'] = date('Y.m.d', strtotime($value['start_date']));
                $value['end_date']   = date('Y.m.d', strtotime($value['end_date']));

                $html .= <<<EOF
                <div class="coupon_item">
        <p class="coupon_item_label">{$value['name']}</p>
        <div class="coupon_item_text">
          <p class="coupon_item_money text-danger">{$value['money']}元</p>
          <p class="coupon_item_date">{$value['start_date']}～{$value['end_date']}有效</p>
        </div>
        <div class="coupon_check" id="coupon_{$value['id']}" data-id={$value['id']} data-money={$value['money']}><i class="fa fa-square-o" aria-hidden="true"></i></div>
    </div>
EOF;
            }
        }

        echo $html;
    }

    /*
     * 使用好友手机号码，获取折扣
    */
    public function actionDiscount() {
        $userphone = $_COOKIE['userphone'];

        $params = Yii::$app->request->post();
        $friendPhone = $params['phone'];

        $key = $userphone . '_' . $friendPhone . '_discount';
        $percent = Yii::$app->redis->get($key);

        if ($percent > 0) {
            echo $percent;
        } else {
            $percent = rand(1, 5) / 100;
            Yii::$app->redis->setex($key, 3600, $percent);
            echo $percent;
        }
    }

    private function getUserAddress($userphone) {
        return Address::find()->where(['userphone' => $userphone])->orderBy('id desc')->asArray()->all();
    }
}
