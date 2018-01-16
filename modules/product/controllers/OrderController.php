<?php

namespace app\modules\product\controllers;

use Yii;
use app\controllers\AuthController;
use app\models\ProductOrder;
use app\models\Pay;
use app\models\Address;
use app\modules\product\models\ProductList;
use yii\helpers\Html;
use app\components\SiteHelper;
use app\components\PriceHelper;
use app\components\ExpressHelper;
use app\models\ProductCart;

class OrderController extends AuthController
{
    public function actionIndex() {
        $status = Yii::$app->params['order_status'];
        return $this->render('index', [
            'status' => $status,
        ]);
    }

    /**
     * 表格
     */
    public function actionTable() {
        $params = Yii::$app->request->post();

        $sql = "select id,customer_id,rec_name,rec_phone,rec_address,pay_money,status,express_company,express_num,create_time from product_order";
        
        $sqlCondition = [];
        if ($params['status'] > 0) {
            $sqlCondition[] = " status = " . $params['status'];
        }
        
        if ($params['order_type'] > 0) {
            $sqlCondition[] = " order_type = " . $params['order_type'];
        }

        if ($params['start_date'] > 0 && $params['end_date'] > 0) {
            $sqlCondition[] = " `date` >= " . $params['start_date'] . ' and  `date` <=' . $params['end_date'] ;
        }

        if (!empty($params['query'])) {
            $sqlCondition[] = " (`rec_name` like '%" . $params['query'] . "%' or id = '" . $params['query'] . "')";
        }

        if (!empty($sqlCondition)) {
            $sql .= ' where ' . implode(' and ', $sqlCondition);
        }

        $totalSql = $sql;
        $sql .= " order by id desc limit " . $params['start'] . ', ' . $params['length'];

        $ret = ProductOrder::findBySql($sql)->asArray()->all();
        $total = ProductOrder::findBySql($totalSql)->count();

        foreach($ret as $key => $value) {
            $ret[$key]['userphone'] = SiteHelper::getCustomerPhone($value['customer_id']);

            $ret[$key]['operation'] = "
            <a data-id='{$value['id']}' data-val='{$value['rec_name']}' style='margin-top:5px !important;'  class='order-edit btn btn-xs btn-primary' href='javascript:void(0);'>编辑</a>
            <a data-id='{$value['id']}' data-val='{$value['rec_name']}' style='margin-top:5px !important;'  class='order-express btn btn-xs btn-purple' href='javascript:void(0);'>获取快递号</a>
            <a data-id='{$value['id']}' data-val='{$value['rec_name']}' style='margin-top:5px !important;' class='order-status btn btn-xs btn-info' href='javascript:void(0);'>状态设置</a>";

            if ($ret[$key]['status'] == 2 || $ret[$key]['status'] == 3) {
                $orderId = $value['id'];
                $payData = Pay::find()->where(['order_id' => $orderId])->asArray()->one();

                if ($payData['pay_type'] == 0) {
                    $text = "余额退款";
                }

                if ($payData['pay_type']  == 1) {
                    $text = "支付宝退款";
                }

                if ($payData['pay_type']  == 2) {
                    $text = "微信退款";
                }

                $ret[$key]['operation'] .= "  <a data-id='{$value['id']}' data-val='{$value['pay_money']}' data-online='{$payData['online_money']}' data-wallet='{$payData['wallet_money']}' style='margin-top:5px !important;'  class='order-refund btn btn-xs btn-danger' href='javascript:void(0);'>{$text}</a>";
            }

            if ($ret[$key]['status'] == 1) {
                $payData = Pay::find()->where(['order_id' => $value['id'], 'pay_result' => 0])->asArray()->one();
                if (!empty($payData)) {
                    $ret[$key]['operation'] .= "  <a data-id='{$value['id']}' data-pid='{$payData['id']}' style='margin-top:5px !important;' class='order-refresh btn btn-xs btn-dark' href='javascript:void(0);'>更新支付状态</a>";
                }
            }

            if ($ret[$key]['status'] == 2) {
                $ret[$key]['operation'] .= "  <a style='margin-top:5px !important;' class='order-print btn btn-xs btn-secondary' href='/product/order/print?id={$value['id']}'>打印订单</a>";

                $ret[$key]['operation'] .= "  <a style='margin-top:5px !important;' class='order-express-print btn btn-xs btn-primary' href='/product/order/expressprint?id={$value['id']}'>电子面单</a>";
            }

            $ret[$key]['status'] = Yii::$app->params['order_status'][$ret[$key]['status']];
        }
        $output = [];
        $output['data'] = $ret;
        $output['recordsTotal'] = $total;
        $output['recordsFiltered'] = $total;

        echo json_encode($output);
    }

    public function actionPrint() {
        $params = Yii::$app->request->get();
        $id = $params['id'];

        if (empty($id)) {
            echo '参数有误';
            exit;
        }

        $info = ProductOrder::find()->where(['id' => $id])->asArray()->one();
        $cart = ProductCart::find()->where(['id' => $info['cart_id']])->asArray()->one();

        $product = json_decode($cart['cart'], true);

        foreach($product as $key => $value) {
            $tmpProduct = ProductList::find()->where(['id' => $value['id']])->asArray()->one();
            $info['product'][] = $tmpProduct;
        }

        $info['product_cart'] = $product;
        $info['express_rule'] = ($info['express_rule'] == 1) ? '快递' : '自提';

        // TODO 私人定制订单处理
        // TODO 发票

        return $this->render('print', [
            'info' => $info,
        ]);
    }

    public function actionRefund() {
        $params = Yii::$app->request->post();
        $id = $params['id'];

        $payData = Pay::find()->where(['order_id' => $id, 'pay_result' => 1])->asArray()->one();

        if (empty($payData)) {
            echo '未找到支付相关数据';
        } else {
            echo PriceHelper::refund($payData);
        }
    }

    public function actionInfo() {
        $params = Yii::$app->request->get();
        $id = $params['id'];

        $pl = new ProductOrder();
        $ret = ProductOrder::find()
            ->select('rec_name,rec_address,rec_phone')
            ->where(['id' => $id])
            ->asArray()
            ->one();

        echo json_encode($ret);
    }

    /**
     * 添加
     * TODO 重名检测
     */
    public function actionEdit() {
        $params = Yii::$app->request->post();
        if(empty($params)){
            echo '参数不能为空';
            exit;
        }

        $pl = ProductOrder::findOne($params['id']);
        foreach($params as $key => $value){
            if($key != 'id'){
                $pl->$key = $value;
            }
        }

        if($pl->save()){
            echo 'suc';
        }else{
            echo 'fail';
        }
    }

    /**
     * 快递号设置
     */
    public function actionExpress() {
        $params = Yii::$app->request->post();
        if(empty($params)){
            echo '参数不能为空';exit;
        }

        $id = $params['id'];
        $company = $params['express_company'];

        $up = ProductOrder::findOne($id);
        $up->express_company = $company;
        $up->save();

        $num = $this->getExpressnum($id);

        if ($num != 0) {
            $po = ProductOrder::findOne($id);
            $po->express_num = $num;
            $po->save();

            echo 'suc';
        } else {
            echo '设置失败';
        }
    }

    /**
     * 标签设置
     */
    public function actionStatus() {
        $params = Yii::$app->request->post();
        if(empty($params)){
            echo '参数不能为空';exit;
        }

        $id = $params['id'];
        $status = $params['status'];

        try {
            $pl = ProductOrder::findOne($id);
            $pl->status = $status;
            $pl->save();

            echo 'suc';
        } catch (Exception $e) {
            echo '设置失败';
        }
    }

    /**
     * 已有状态
     */
    public function actionStatusme() {
        $params = Yii::$app->request->post();

        $id = $params['id'];
        $data = ProductOrder::find()->select('status')->where(['id' => $id])->asArray()->one();

        echo json_encode($data);
    }

    /**
     * 已有快递号
     */
    public function actionExpressme() {
        $params = Yii::$app->request->post();

        $id = $params['id'];
        $data = ProductOrder::find()->select('express_num')->where(['id' => $id])->asArray()->one();

        echo json_encode($data);
    }

    public function actionExpressprint() {
        $params = Yii::$app->request->get();
        $id = $params['id'];

        $data = ProductOrder::find()->where(['id' => $id])->asArray()->one();

        if (!empty($data['express_num'])) {
            $form = ExpressHelper::buildForm($data['express_num']);
        } else {
            $form = '没有快递单号';
        }

        return $this->render('exprint', [
            'form' => $form,
        ]);
    }

    private function getExpressnum($id) {
        $info   = ProductOrder::find()->where(['id' => $id])->asArray()->one();

        $data = [];
        $data['id'] = date('Ymd', time()) . '_' . $info['id'];
        $data['rec_name'] = $info['rec_name'];
        $data['rec_phone'] = $info['rec_phone'];
        $data['rec_province'] = '四川省';
        $data['rec_city'] = $info['rec_city'];
        $data['rec_district'] = $info['rec_district'];
        $data['rec_address']   = $info['rec_detail'];

        $data['order_name'] = '水果';

        $ret  = ExpressHelper::getEorder($data, $info['express_company']);

        $data = json_decode($ret, true);

        if (isset($data['Success']) && $data['Success'] == true) {
            $order = $data['Order']['LogisticCode'];

            return $order;
        }

        return 0;
    }
}
