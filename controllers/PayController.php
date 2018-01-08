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
use app\models\Pay;
use app\components\AlipayHelper;
use app\components\NoCsrf;

class PayController extends Controller
{
    public $layout = 'wap';

    private $configKeys = [
        'current-skin',
    ];

    public function init() {
        $this->layout = SiteHelper::getLayout();
    }

    public function behaviors()
    {
        return [
            'csrf' => [
                'class' => NoCsrf::className(),
                'controller' => $this,
                'actions' => [
                    'aliwap',
                    'alipc',
                ]
            ]
        ];
    }

    /**
     * 入口
     * @return
     */
    public function actionIndex() {
        if (!SiteHelper::checkSecret()) {
            Yii::$app->controller->redirect('/customer/login');
            Yii::$app->end();
        }

        $params = Yii::$app->request->get();
        $out_trade_no = isset($params['out_trade_no']) ? $params['out_trade_no'] : '';
        if (empty($out_trade_no)) {
            Yii::$app->controller->redirect('/');
            Yii::$app->end();
        }

        $data = Pay::find()->where(['out_trade_no' => $out_trade_no])->asArray()->one();

        switch ($data['pay_type']) {
            case 0:
                $data['pay_type'] = '钱包';
                break;
            case 1:
                $data['pay_type'] = '支付宝';
                break;
            case 2:
                $data['pay_type'] = '微信';
                break;
            default:
                $data['pay_type'] = '钱包';
                break;
        }

        return $this->render('index', [
            'controller' => Yii::$app->controller->id,
            'data' => $data,
        ]);
    }

    public function actionWallet() {
        if (!SiteHelper::checkSecret()) {
            Yii::$app->controller->redirect('/customer/login');
            Yii::$app->end();
        }

        $params = Yii::$app->request->get();
        $id = isset($params['id']) ? $params['id'] : '';
        if (empty($id)) {
            Yii::$app->controller->redirect('/');
            Yii::$app->end();
        }

        $data = Pay::find()->where(['id' => $id])->asArray()->one();
        $data['pay_type'] = '钱包';

        return $this->render('wallet', [
            'controller' => Yii::$app->controller->id,
            'data' => $data,
        ]);
    }

    public function actionRefresh() {
        $params = Yii::$app->request->post();
        $id = isset($params['id']) ? $params['id'] : 0;
        if ($id == 0) {
            echo '提交数据错误';
            Yii::$app->end();
        }

        $data = Pay::find()->where(['id' => $id])->asArray()->one();

        if (empty($data)) {
            echo '提交数据错误';
            Yii::$app->end();
        }

        if ($data['pay_result'] == 1) {
            echo 'suc';
            Yii::$app->end();
        }

        if ($data['pay_result'] == 2) {
            echo '支付失败，请重新支付';
            Yii::$app->end();
        }

        $response = AlipayHelper::query($data);

        // $resData = json_decode($response, true);

        echo $response;
    }

    public function actionAdd() {
        if (!SiteHelper::checkSecret()) {
            echo json_encode(['status' => 'fail', 'msg' => '验证用户失败']);
            Yii::$app->end();
        }

        $cid = $_COOKIE['cid'];
        $params = Yii::$app->request->post();
        $id = isset($params['id']) ? $params['id'] : 0;
        $payType = isset($params['type']) ? $params['type'] : 0;

        $data = ProductOrder::find()->where(['customer_id' => $cid, 'id' => $id])->asArray()->one();

        if (empty($data)) {
            echo json_encode(['status' => 'fail', 'msg' => '请求参数有误']);
            Yii::$app->end();
        }

        if ($data['status'] == 2 || $data['status'] == 3) {
            echo json_encode(['status' => 'fail', 'msg' => '该订单已支付']);
            Yii::$app->end();
        }

        // 普通购买检验库存
        if ($data['order_type'] == 1) {
            $inventory = $this->checkInventory($id);
            if (!$inventory['status']) {
                echo json_encode(['status' => 'fail', 'msg' => $inventory['msg'] . '库存数量不足，请调整商品']);
                Yii::$app->end();
            }
        }

        $payMoney = $data['pay_money'];
        $walletMoney = Customer::find()->select('money')->where(['id' => $cid])->scalar();

        $payData = [];
        $payData['order_id'] = $id;
        $payData['customer_id'] = $cid;
        $payData['out_trade_no'] = date('Ymdhis', time()) . '_' . $id;

        $terminal = SiteHelper::getTermimal();

        if ($walletMoney < $payMoney) {
            // alipay支付
            if ($payType == 1) {
                $realPayMoney = round($payMoney - $walletMoney, 2);

                $payData['wallet_money'] = $walletMoney;
                $payData['online_money'] = $realPayMoney;
                $payData['pay_type'] = 1;
                $payData['terminal'] = $terminal;

                $pid = $this->addRecord($payData);

                $alipayParams = [
                    'subject' => '果果佳订单',
                    'out_trade_no' => date('Ymdhis', time()) . '_' . $id,
                    'timeout_express' => '30m',
                    'total_amount' => $realPayMoney,
                    'product_code' => 'QUICK_WAP_WAY'
                ];

                if ($terminal == 'wap') {
                    $ret = AlipayHelper::wappay($alipayParams);
                } else {
                    $alipayParams['product_code'] = 'FAST_INSTANT_TRADE_PAY';
                    $ret = AlipayHelper::pcpay($alipayParams);
                }

                echo json_encode(['status' => 'ok', 'pay_type' => 1, 'html' => $ret]);
                Yii::$app->end();
            }

            // 微信支付
            if ($payType == 2) {
                echo json_encode(['status' => 'ok', 'pay_type' => 2, 'html' => '微信支付测试']);
                Yii::$app->end();
            }
        } else {
            // 余额支付
            $payData['online_money'] = 0;
            $payData['wallet_money'] = $payMoney;
            $payData['pay_type'] = 0;
            $payData['terminal'] = $terminal;

            $pid = $this->addRecord($payData);

            PriceHelper::adjustWallet($payMoney, 'minus', 'pay_order_' + $pid + "_" + $id);

            SiteHelper::handlePayOkOrder($pid);

            echo json_encode(['status' => 'ok', 'pay_type' => 0, 'id' => $pid]);
            Yii::$app->end();
        }
    }

    private function checkInventory($orderId) {
        $data     = ProductOrder::find()->where(['id' => $orderId])->asArray()->one();
        $cartId   = $data['cart_id'];
        $cartArr = ProductCart::find()->where(['id' => $cartId])->asArray()->one();
        $cartJsonArr = json_decode($cartArr['cart'], true);

        foreach($cartJsonArr as $item) {
            $id  = $item['id'];
            $tmp = ProductList::find()->where(['id' => $id])->asArray()->one();
            if ($item['num'] > $tmp['num']) {
                return ['status' => false, 'msg' => $tmp['name']];
            }
        }

        return ['status' => true];
    }

    private function addRecord($payData) {
        $date = date("Ymd", time());
        $ar = new Pay();
        foreach($payData as $key => $item) {
            $ar->$key = $item;
        }
        $ar->date = $date;
        $ar->save();

        return $ar->id;
    }

    public function actionAliwap() {
        $this->enableCsrfValidation = false;
        $arr = $_POST;

        $result = AlipayHelper::check($arr, 'wap');

        if ($result) {
            $out_trade_no = $_POST['out_trade_no'];

            //支付宝交易号
            $trade_no = $_POST['trade_no'];

            //交易状态
            $trade_status = $_POST['trade_status'];

            // 交易金额
            $total_amount = $_POST['total_amount'];

            if($_POST['trade_status'] == 'TRADE_FINISHED' || $_POST['trade_status'] == 'TRADE_SUCCESS') {
                $checkData = Pay::find()->where(['out_trade_no' => $out_trade_no])->asArray()->one();

                if ($total_amount == $checkData['online_money'] && $checkData['pay_result'] != 1) {
                    SiteHelper::handlePayOkOrder($checkData['id'], $trade_no);

                    echo 'success';
                    Yii::$app->end();
                }
            }
        }

        echo 'fail';
    }

    public function actionAlipc() {
        $this->enableCsrfValidation = false;
        $arr = $_POST;

        $result = AlipayHelper::check($arr, 'pc');

        if ($result) {
            $out_trade_no = $_POST['out_trade_no'];

            //支付宝交易号
            $trade_no = $_POST['trade_no'];

            //交易状态
            $trade_status = $_POST['trade_status'];

            // 交易金额
            $total_amount = $_POST['total_amount'];

            if($_POST['trade_status'] == 'TRADE_FINISHED' || $_POST['trade_status'] == 'TRADE_SUCCESS') {
                $checkData = Pay::find()->where(['out_trade_no' => $out_trade_no])->asArray()->one();

                if ($total_amount == $checkData['online_money'] && $checkData['pay_result'] != 1) {
                    SiteHelper::handlePayOkOrder($checkData['id'], $trade_no);

                    echo 'success';
                    Yii::$app->end();
                }
            }
        }

        echo 'fail';
    }
}
