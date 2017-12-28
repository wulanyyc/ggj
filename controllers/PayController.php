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

class PayController extends Controller
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
        // if (!SiteHelper::checkSecret()) {
        //     return $this->render('login', [
        //         'controller' => Yii::$app->controller->id,
        //     ]);
        // }

        // $phone = $_COOKIE['userphone'];

        // $params = Yii::$app->request->get();
        // $id = isset($params['id']) ? $params['id'] : 0;

        // $data = ProductOrder::find()->where(['userphone' => $phone])->orderBy('id desc')->asArray()->all();

        return $this->render('index', [
            'controller' => Yii::$app->controller->id,
        ]);
    }

    public function actionAdd() {
        if (!SiteHelper::checkSecret()) {
            echo '验证用户失败';
            Yii::$app->end();
        }

        $phone = $_COOKIE['userphone'];
        $params = Yii::$app->request->post();
        $id = isset($params['id']) ? $params['id'] : 0;

        $data = ProductOrder::find()->where(['userphone' => $phone, 'id' => $id])->asArray()->one();
        // print_r($data);exit;
        if (empty($data)) {
            echo '请求参数有误';
            Yii::$app->end();
        }

        $payMoney = $data['pay_money'];
        $walletMoney = Customer::find()->select('money')->where(['phone' => $phone])->scalar();

        $payData = [];
        $payData['order_id'] = $id;
        $payData['userphone'] = $phone;
        $payData['out_trade_no'] = date('Ymdhis', time()) . '_' . $id;

        if ($walletMoney < $payMoney) {
            $payRealMoney = round($payMoney - $walletMoney, 1);
            $payData['wallet_money'] = $walletMoney;
            $payData['online_money'] = $payRealMoney;
            $payData['pay_type'] = 1; // alipay支付
            $pid = $this->addRecord($payData);

            $payType = 1;

            $alipayParams = [
                'subject' => 'order_' . $id,
                'out_trade_no' => date('Ymdhis', time()) . '_' . $id,
                'timeout_express' => '90m',
                'total_amount' => 1,
                'product_code' => 'QUICK_WAP_WAY'
            ];

            $ret = AlipayHelper::wappay($alipayParams);
        } else {
            $payData['online_money'] = 0;
            $payData['wallet_money'] = $payMoney;
            $payData['pay_type'] = 0; // 余额支付
            $pid = $this->addRecord($payData);

            $payType = 0;
            $ret = [];
        }

        echo json_encode(['pay_type' => $payType, 'data' => $ret]);
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

    public function actionAlicallback() {
        $text = file_get_contents("php://input");
        Yii::error($text);
        echo "test callback";
        Yii::$app->end();
    }
}
