<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\components\SiteHelper;
use app\components\OrderHelper;
use app\modules\product\models\ProductList;
use app\components\PriceHelper;
use app\models\ProductCart;
use app\models\ProductOrder;
use app\models\Address;
use app\modules\product\models\Coupon;
use app\modules\product\models\CouponUse;
use app\models\Customer;
use app\models\Gift;
use app\models\GiftUse;
use app\components\NotifyHelper;
use app\filters\CustomerFilter;
use app\filters\WechatFilter;


class OrderController extends Controller
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
                   'login',
                   'handle',
                   'track',
                ]
            ],
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
        $cid = SiteHelper::getCustomerId();

        $params = Yii::$app->request->get();
        $orderType = isset($params['type']) ? $params['type'] : 1;

        $data = ProductOrder::find()->where(['customer_id' => $cid, 'deleteflag' => 0])->orderBy('id desc')->asArray()->all();

        foreach($data as $key => $item) {
            if ($item['status'] == 1) {
                $data[$key]['product_price'] = PriceHelper::calculateProductPrice($item['cart_id']);
            }

            if (!empty($item['gift_ids'])) {
                $gids = explode(',', $item['gift_ids']);
                $gidInfos = GiftUse::find()->select('gid')->where(['id' => $gids])->asArray()->all();

                $giftIds = [];
                foreach($gidInfos as $item) {
                    $giftIds[] = $item['gid'];
                }

                $data[$key]['gifts'] = Gift::findBySql("select * from gift where id in (" . implode(',', $giftIds) . ")")->asArray()->all();
            }
        }

        return $this->render('index', [
            'controller' => Yii::$app->controller->id,
            'data'   => $data,
            'status' => Yii::$app->params['order_status'],
            'type'   => Yii::$app->params['order_type'],
            'orderType' => $orderType,
        ]);
    }

    public function actionAdd() {
        $params = Yii::$app->request->post();
        if(empty($params)){
            SiteHelper::render('fail', '提交的参数不能为空');
        }

        $params['customer_id'] = SiteHelper::getCustomerId();

        $params = $this->checkParams($params);

        $addressId = $params['address_id'];
        $info = Address::find()->where(['id' => $addressId])->asArray()->one();

        $params['rec_name']    = $info['rec_name'];
        $params['rec_phone']   = $info['rec_phone'];
        $params['rec_city']    = $info['rec_city'];
        $params['rec_district'] = $info['rec_district'];
        $params['rec_detail']  = $info['rec_detail'];
        $params['rec_address'] = $info['rec_city'] . $info['rec_district'] . $info['rec_detail'];
        $params['source'] = SiteHelper::getSource();

        $exsitId = ProductOrder::find()->where(['cart_id' => $params['cart_id'], 'customer_id' => $params['customer_id']])->select('id')->scalar();

        if ($exsitId > 0) {
            $po = ProductOrder::findOne($exsitId);
        } else {
            $po = new ProductOrder();
            $params['date'] = date('Ymd', time());
        }
        
        foreach($params as $key => $value){
            $po->$key = $value;
        }

        if($po->save()){
            SiteHelper::render('ok', $po->id);
        }else{
            SiteHelper::render('fail', '请完善订单信息');
        }
    }

    private function checkParams($params) {
        $cartid = $params['cart_id'];

        $productPrice = PriceHelper::calculateProductPrice($cartid);
        $expressFee   = PriceHelper::calculateExpressFee($cartid);

        $params['product_price'] = $productPrice;
        $params['express_fee'] = $expressFee;

        // check coupon
        if ($params['coupon_fee'] > 0) {
            $couponFee = PriceHelper::calculateCounponFee($params['coupon_ids']);
            $params['coupon_fee'] = $couponFee;
        }

        $params['pay_money'] = round($productPrice + $expressFee - $params['coupon_fee'], PriceHelper::$precison);

        return $params;
    }

    public function actionLogin() {
        return $this->render('login', [
            'controller' => Yii::$app->controller->id,
        ]);
    }

    public function actionProduct() {
        $params = Yii::$app->request->get();
        $cid = $params['cid'];
        $id  = $params['id'];

        $data = ProductCart::find()->where(['id' => $cid])->asArray()->one();
        $orderStatus = ProductOrder::find()->where(['id' => $id])->select('status')->scalar();

        $ret = [];
        $cart = json_decode($data['cart'], true);
        foreach($cart as $item) {
            $pid = $item['id'];
            $tmp = ProductList::find()->select('id,name,unit,price,desc')->where(['id' => $pid])->asArray()->one();
            $ret[] = $tmp;
        }

        $html = '';
        foreach($ret as $item) {
            $html .= "<tr><td>" . $item['name'] . "</td><td>" . $item['desc'] . "</td><td>" . $cart[$item['id']]['num'] .$item['unit'] . "</td></tr>";
        }
        
        SiteHelper::render('ok', $html);
    }

    public function actionDel() {
        $params = Yii::$app->request->post();
        $id = $params['id'];

        $ar = ProductOrder::findOne($id);
        $ar->status = 5;

        if($ar->save()){
            SiteHelper::render('ok');
        }else{
            SiteHelper::render('fail', '删除失败');
        }
    }


    public function actionDelforever() {
        $params = Yii::$app->request->post();
        $id = $params['id'];

        $ar = ProductOrder::findOne($id);
        $ar->deleteflag = 1;
        $ar->save();
        
        SiteHelper::render('ok');
    }

    public function actionComplete() {
        $params = Yii::$app->request->post();
        $id = $params['id'];

        $ar = ProductOrder::findOne($id);
        $ar->status = 3;

        if($ar->save()){
            SiteHelper::render('ok');
        }else{
            SiteHelper::render('fail', '设置失败');
        }
    }

    public function actionPay() {
        $params = Yii::$app->request->get();
        $oid = isset($params['oid']) ? $params['oid'] : '';
        if (empty($oid)) {
            Yii::$app->controller->redirect('/');
            Yii::$app->end();
        }

        $cid = SiteHelper::getCustomerId();
        $data = ProductOrder::find()->where(['customer_id' => $cid, 'id' => $oid])
            ->orderBy('id desc')->asArray()->one();

        if (empty($data)) {
            Yii::$app->controller->redirect('/');
            Yii::$app->end();
        }

        $openid = SiteHelper::getOpenid();
        $isWechat = !empty($openid) ? 1 : 0;

        $money = Customer::find()->select('money')->where(['id' => $cid])->scalar();

        return $this->render('pay', [
            'controller' => Yii::$app->controller->id,
            'data' => $data,
            'money' => $money,
            'isWechat' => $isWechat,
        ]);
    }

    public function actionExpressinfo() {
        $params = Yii::$app->request->post();
        if(empty($params)){
            SiteHelper::render('fail', '提交的参数不能为空');
        }

        $id = $params['id'];

        $cid = SiteHelper::getCustomerId();
        $expressNum = ProductOrder::find()->select('express_num')->where(['customer_id' => $cid, 'id' => $id])->scalar();

        if (empty($expressNum)) {
            SiteHelper::render('fail', '商家还未发货, 非预约单下单后24小时内发货');
        }

        $data = json_decode(OrderHelper::getExpressInfo($expressNum), true);

        // $str = <<<EOF
        // {"status":"0","msg":"ok","result":{"number":"469766445769","type":"zto","list":[{"time":"2017-12-21 11:55:30","status":"[成都市] 快件已到达 成都文家场外光华,业务员 赵丹[18108229928] 正在派件"},{"time":"2017-12-21 05:37:09","status":"[成都市] 快件离开 成都中转 已发往 成都文家场外光华"},{"time":"2017-12-21 05:18:35","status":"[成都市] 快件已经到达 成都中转"},{"time":"2017-12-20 02:16:31","status":"[常州市] 快件离开 常州中转部 已发往 成都中转"},{"time":"2017-12-19 20:23:16","status":"[常州市] 快件已经到达 常州中转部"},{"time":"2017-12-19 17:44:23","status":"[常州市] 快件离开 金坛新 已发往 常州中转部"},{"time":"2017-12-19 15:31:55","status":"[常州市] 金坛新 的 樱桃饰品[18018228566] 已收件"}],"deliverystatus":"2","issign":"0"}}
// EOF;
        // $data = json_decode($str, true);

        if ($data['status'] != 0) {
            $html =  "<div id='unknown'>很抱歉，平台未查到物流信息，您的快递单号：<input id='express_copy_num' value='" . $expressNum . "' type='text' readonly style='display:inline-block;' />&nbsp;<button type='button' class='btn btn-danger btn-sm' id='copy' data-clipboard-target='#express_copy_num'>复制</button></div>";
            SiteHelper::render('ok', $html);
        } else {
            SiteHelper::render('ok', $this->buildExpressHtml($data));
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
            $html .= '<div class="label"><i class="fa fa-arrow-circle-o-up" aria-hidden="true"></i>&nbsp;<span>' . $item["time"] . '</span></div>';
            $html .= "<div class='step-content'>";
            $html .= "<div>{$item['status']}</div>";
            $html .= "</div>";
            $html .= "</div>";
        }
        $html .= "</div>";

        return $html;
    }

    public function actionHandle() {
        $params = Yii::$app->request->get();
        $id  = isset($params['id']) ? $params['id'] : '';
        $uid = isset($params['uid']) ? $params['uid'] : '';
        $token = isset($params['token']) ? $params['token'] : '';

        $checkToken = md5($id . Yii::$app->params['salt']);

        if ($token == $checkToken) {
            $info = ProductOrder::find()->where(['id' => $id])->asArray()->one();
            $cart = ProductCart::find()->where(['id' => $info['cart_id']])->asArray()->one();

            $product = json_decode($cart['cart'], true);

            foreach($product as $key => $value) {
                $tmpProduct = ProductList::find()->where(['id' => $value['id']])->asArray()->one();
                $info['product'][] = $tmpProduct;
            }

            $info['product_cart'] = $product;
            $info['express_rule'] = ($info['express_rule'] == 1) ? '快递' : '自提';
            $info['gifts'] = [];

            $gifts = $info['gift_ids'];
            if (!empty($gifts)) {
                $giftIds = explode(',', $gifts);
                foreach($giftIds as $item) {
                    $tmpStatus = GiftUse::find()->where(['id' => $item, 'customer_id' => $info['customer_id']])->asArray()->one();

                    $tmpInfo = Gift::find()->where(['id' => $tmpStatus['gid']])->asArray()->one();

                    $tmp = [
                        'name' => $tmpInfo['name'],
                        'useflag' => '本次配送',
                    ];

                    $info['gifts'][] = $tmp;
                }
            }

            return $this->render('handle', [
                'controller' => Yii::$app->controller->id,
                'id' => $id,
                'uid' => $uid,
                'info' => $info,
                'token' => $token,
            ]);
        } else {
            return $this->render('error', [
                'controller' => Yii::$app->controller->id,
                'tip' => '验证失败',
            ]);
        }
    }

    public function actionPrepare() {
        $params = Yii::$app->request->post();
        $id  = isset($params['id']) ? $params['id'] : '';
        $token = isset($params['token']) ? $params['token'] : '';

        $checkToken = md5($id . Yii::$app->params['salt']);

        if ($token == $checkToken) {
            NotifyHelper::prepare($id);
            SiteHelper::render('ok');
        } else {
            SiteHelper::render('fail', '验证失败');
        }
    }


    public function actionTrack() {
        $params = Yii::$app->request->get();
        $id    = isset($params['id']) ? $params['id'] : '';
        $token = isset($params['token']) ? $params['token'] : '';

        $checkToken = md5($id . Yii::$app->params['salt']);

        if ($checkToken != $token) {
            echo '验证失败';
            Yii::$app->end();
        }

        $info = ProductOrder::find()->where(['id' => $id])->asArray()->one();
        $expressNum = $info['express_num'];

        $data = json_decode(OrderHelper::getExpressInfo($expressNum), true);

        $expressHtml = '';
        if ($data['status'] != 0) {
            $expressHtml =  "<div id='unknown'>很抱歉，平台未查到物流信息，请在顺丰中重新查询，您的快递单号：" . $expressNum . "</div>";
        } else {
            $expressHtml = $this->buildExpressHtml($data);
        }

        $cartId   = $info['cart_id'];
        $cartData = ProductCart::find()->where(['id' => $cartId])->asArray()->one();

        $ret = [];
        $cart = json_decode($cartData['cart'], true);
        foreach($cart as $item) {
            $pid = $item['id'];
            $tmp = ProductList::find()->select('id,name,unit,price,desc')->where(['id' => $pid])->asArray()->one();
            $ret[] = $tmp;
        }

        $productHtml = '';
        foreach($ret as $item) {
            $productHtml .= "<tr><td>" . $item['name'] . "</td><td>" . $item['desc'] . "</td><td>" . $cart[$item['id']]['num'] .$item['unit'] . "</td></tr>";
        }

        $gifts = $info['gift_ids'];

        if (!empty($gifts)) {
            $gids  = explode(',', $gifts);
            $gidInfos = GiftUse::find()->select('gid')->where(['id' => $gids])->asArray()->all();

            $giftIds = [];
            foreach($gidInfos as $item) {
                $giftIds[] = $item['gid'];
            }

            $giftInfos = Gift::findBySql("select * from gift where id in (" . implode(',', $giftIds) . ")")->asArray()->all();
        } else {
            $giftInfos = [];
        }

        return $this->render('track', [
            'controller' => Yii::$app->controller->id,
            'express'    => $expressHtml,
            'product'    => $productHtml,
            'giftInfos'  => $giftInfos,
        ]);
    }
}
