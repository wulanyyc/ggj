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
        if (!SiteHelper::checkSecret()) {
            return $this->render('login', [
                'controller' => Yii::$app->controller->id,
            ]);
        }

        $phone = $_COOKIE['userphone'];

        $params = Yii::$app->request->get();
        $orderType = isset($params['type']) ? $params['type'] : 0;

        $data = ProductOrder::find()->where(['userphone' => $phone])->orderBy('id desc')->asArray()->all();

        return $this->render('index', [
            'controller' => Yii::$app->controller->id,
            'data' => $data,
            'status' => Yii::$app->params['order_status'],
            'type' => Yii::$app->params['order_type'],
            'orderType' => $orderType,
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

        if (!$this->checkOrderPrice($params)) {
            echo '价格数据有误';
            Yii::$app->end();
        }

        $params['userphone'] = $_COOKIE['userphone'];

        $addressId = $params['address_id'];
        $info = Address::find()->where(['id' => $addressId])->asArray()->one();

        $params['rec_name']    = $info['rec_name'];
        $params['rec_phone']   = $info['rec_phone'];
        $params['rec_address'] = $info['rec_city'] . $info['rec_district'] . $info['rec_detail'];

        $exsitId = ProductOrder::find()->where(['cart_id' => $params['cart_id'], 'userphone' => $params['userphone']])->select('id')->scalar();

        if ($exsitId > 0) {
            $po = ProductOrder::findOne($exsitId);
        } else {
            $po = new ProductOrder();
        }
        
        foreach($params as $key => $value){
            $po->$key = $value;
        }

        if($po->save()){
            echo $po->id;
        }else{
            echo '请完善订单信息';
        }
    }

    private function checkOrderPrice($params) {
        $cid = $params['cart_id'];
        $express = $params['express_rule'];

        $productPrice = ProductCart::find()->select('product_price')->where(['id' => $cid])->scalar();

        // check 快递费
        if ($express == 1) {
            $expressFee = 0;
        } else {
            $expressFee = SiteHelper::calculateExpressFee($params['type'], $productPrice);
        }

        if ($expressFee != $params['express_fee']) {
            return false;
        }

        // check 朋友折扣
        if ($params['discount_fee'] > 0) {
            $discountPhone = $params['discount_phone'];
            $userphone = $_COOKIE['userphone'];
            $key = $userphone . '_' . $discountPhone . '_discount';
            $percent = Yii::$app->redis->get($key);
            $discountFee = round($productPrice * $percent, 1);

            if ($discountFee != $params['discount_fee']) {
                return false;
            }
        }

        // check coupon
        if ($params['coupon_fee'] > 0) {
            $couponFee = Coupon::findBySql("select sum(money) from coupon where id in (" . $params['coupon_ids'] . ")")->scalar();

            if ($couponFee != $params['coupon_fee']) {
                return false;
            }
        }

        return true;
    }

    public function actionLogin() {
        return $this->render('login', [
            'controller' => Yii::$app->controller->id,
        ]);
    }

    public function actionProduct() {
        $params = Yii::$app->request->get();
        $cid = $params['id'];
        $data = ProductCart::find()->where(['id' => $cid])->asArray()->one();

        $ret = [];
        $cart = json_decode($data['cart'], true);
        foreach($cart as $item) {
            $pid = $item['id'];
            $ret[] = ProductList::find()->select('id,name,unit')->where(['id' => $pid])->asArray()->one();
        }

        $html = '';
        foreach($ret as $item) {
            $html .= "<tr><td>" . $item['name'] . "</td><td>" . $cart[$item['id']]['num'] . "</td><td>" . $cart[$item['id']]['price'] . "/" . $item['unit'] . "</td></tr>";
        }
        
        echo $html;
    }

    public function actionDel() {
        if (!SiteHelper::checkSecret()) {
            return $this->render('login', [
                'controller' => Yii::$app->controller->id,
            ]);
        }

        $params = Yii::$app->request->get();
        $id = $params['id'];

        $ar = ProductOrder::findOne($id);
        $ar->status = 5;

        if($ar->save()){
            echo '已删除';
        }else{
            echo '删除失败';
        }
    }

    public function actionComplete() {
        if (!SiteHelper::checkSecret()) {
            return $this->render('login', [
                'controller' => Yii::$app->controller->id,
            ]);
        }

        $params = Yii::$app->request->get();
        $id = $params['id'];

        $ar = ProductOrder::findOne($id);
        $ar->status = 3;

        if($ar->save()){
            echo 'ok';
        }else{
            echo 'fail';
        }
    }

    public function actionPay() {
        if (!SiteHelper::checkSecret()) {
            return $this->render('login', [
                'controller' => Yii::$app->controller->id,
            ]);
        }

        $params = Yii::$app->request->get();
        if(empty($params)){
            echo '提交的参数不能为空';
            Yii::$app->end();
        }

        $oid = isset($params['oid']) ? $params['oid'] : '';
        if (empty($oid)) {
            echo '访问链接异常';
            Yii::$app->end();
        }

        $phone = $_COOKIE['userphone'];
        $data = ProductOrder::find()->where(['userphone' => $phone, 'id' => $oid])->orderBy('id desc')->asArray()->one();

        if (empty($data)) {
            echo '访问链接参数有误';
            Yii::$app->end();
        }

        $money = Customer::find()->select('money')->where(['phone' => $phone])->scalar();

        return $this->render('pay', [
            'controller' => Yii::$app->controller->id,
            'data' => $data,
            'money' => $money,
        ]);
    }

    public function actionExpressinfo() {
        if (!SiteHelper::checkSecret()) {
            return $this->render('login', [
                'controller' => Yii::$app->controller->id,
            ]);
        }

        $params = Yii::$app->request->get();
        if(empty($params)){
            echo '提交的参数不能为空';
            Yii::$app->end();
        }

        $id = $params['id'];

        $phone = $_COOKIE['userphone'];
        $expressNum = ProductOrder::find()->select('express_num')->where(['userphone' => $phone, 'id' => $id])->scalar();

        if (empty($expressNum)) {
            echo '访问链接有误';
            Yii::$app->end();
        }

        $data = json_decode(SiteHelper::getExpressInfo($expressNum), true);

        // $str = <<<EOF
        // {"status":"0","msg":"ok","result":{"number":"469766445769","type":"zto","list":[{"time":"2017-12-21 11:55:30","status":"[成都市] 快件已到达 成都文家场外光华,业务员 赵丹[18108229928] 正在派件"},{"time":"2017-12-21 05:37:09","status":"[成都市] 快件离开 成都中转 已发往 成都文家场外光华"},{"time":"2017-12-21 05:18:35","status":"[成都市] 快件已经到达 成都中转"},{"time":"2017-12-20 02:16:31","status":"[常州市] 快件离开 常州中转部 已发往 成都中转"},{"time":"2017-12-19 20:23:16","status":"[常州市] 快件已经到达 常州中转部"},{"time":"2017-12-19 17:44:23","status":"[常州市] 快件离开 金坛新 已发往 常州中转部"},{"time":"2017-12-19 15:31:55","status":"[常州市] 金坛新 的 樱桃饰品[18018228566] 已收件"}],"deliverystatus":"2","issign":"0"}}
// EOF;

        // $data = json_decode($str, true);

        if ($data['status'] != 0) {
            echo "<div id='unknown'>很抱歉，平台未查到物流信息，您的快递单号：<input id='express_copy_num' value='" . $expressNum . "' type='text' readonly style='display:inline-block;' />&nbsp;<button type='button' class='btn btn-danger btn-sm' id='copy' data-clipboard-target='#express_copy_num'>复制</button></div>";
            Yii::$app->end();
        } else {
            echo $this->buildExpressHtml($data);
        }
    }

    private function buildExpressHtml($data) {
        $map = [
            1 => '在途中',
            2 => '派件中',
            3 => '已签收',
            4 => '派送失败',
        ];

        $html = "<div id='express_main'>";
        $html .= "<div><span>快递公司：</span>{$data['result']['type']}</div>";
        $html .= "<div><span>运单号：</span><input id='express_copy_num' value='{$data['result']['number']}' type='text' readonly style='display:inline-block;' />&nbsp;<button type='button' class='btn btn-danger btn-sm' data-clipboard-target='#express_copy_num' id='copy'>复制</button></div>";
        $html .= "<div><span>运输状态：</span>{$map[$data['result']['deliverystatus']]}</div>";
        $html .= "</div>";
        $html .= "<div id='express_detail'>";

        foreach($data['result']['list'] as $item) {
            $html .= "<div class='step'>";
            $html .= '<div class="label"><i class="fa fa-arrow-circle-o-up" aria-hidden="true"></i></div>';
            $html .= "<div class='step-content'>";
            $html .= "<div>{$item['time']}</div>";
            $html .= "<div>{$item['status']}</div>";
            $html .= "</div>";
            $html .= "</div>";
        }
        $html .= "</div>";

        return $html;
    }
}
