<?php

namespace app\modules\product\controllers;

use Yii;
use app\controllers\AuthController;
use app\models\ProductOrder;
use app\models\Pay;
use app\modules\product\models\ProductList;
use yii\helpers\Html;
use app\components\SiteHelper;
use app\components\PriceHelper;

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

        $sql = "select id,customer_id,rec_name,rec_phone,rec_address,pay_money,status,create_time from product_order ";
        
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
            <a data-id='{$value['id']}' data-val='{$value['rec_name']}' style='margin-top:5px !important;'  class='order-express btn btn-xs btn-purple' href='javascript:void(0);'>关联快递号</a>
            <a data-id='{$value['id']}' data-val='{$value['rec_name']}' style='margin-top:5px !important;' class='order-status btn btn-xs btn-info' href='javascript:void(0);'>状态</a>";

            if ($ret[$key]['status'] == 2 || $ret[$key]['status'] == 3) {
                $ret[$key]['operation'] .= "  <a data-id='{$value['id']}' style='margin-top:5px !important;'  class='order-refund btn btn-xs btn-danger' href='javascript:void(0);'>退款</a>";
            }

            if ($ret[$key]['status'] == 1) {
                $payData = Pay::find()->where(['order_id' => $value['id'], 'pay_result' => 0])->asArray()->one();
                if (!empty($payData)) {
                    $ret[$key]['operation'] .= "  <a data-id='{$value['id']}' data-pid='{$payData['id']}' style='margin-top:5px !important;' class='order-refresh btn btn-xs btn-light' href='javascript:void(0);'>刷新状态</a>";
                }
            }
            $ret[$key]['status'] = Yii::$app->params['order_status'][$ret[$key]['status']];
        }
        $output = [];
        $output['data'] = $ret;
        $output['recordsTotal'] = $total;
        $output['recordsFiltered'] = $total;

        echo json_encode($output);
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
        $num = $params['express_num'];

        try {
            $po = ProductOrder::findOne($id);
            $po->express_num = $num;
            $po->save();

            echo 'suc';
        } catch (Exception $e) {
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

}
