<?php

namespace app\modules\product\controllers;

use Yii;
use app\controllers\AuthController;
use app\modules\product\models\ProductOrder;
use app\modules\product\models\ProductList;
use yii\helpers\Html;

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
        $status = $params['status'];
        if ($status == '') {
            $status = array_keys(Yii::$app->params['order_status']);
        }

        if (!empty($params['query'])) {
            $ret = ProductOrder::find()->select('id,username,cellphone,address,money,status,create_time')
                ->where(['like', 'username', $params['query']])
                ->orWhere(['id' => intval($params['query'])])
                ->andWhere(['status' => $status])
                ->asArray()->all();

            $total = ProductOrder::find()
                ->where(['like', 'username', $params['query']])
                ->orWhere(['id' => intval($params['query'])])
                ->andWhere(['status' => $status])
                ->count();
        }else {
            $ret = ProductOrder::find()
                ->select('id,username,cellphone,address,money,status,create_time')
                ->where(['status' => $status])
                ->orderBy('id desc')->limit($params['length'])
                ->offset($params['start'])
                ->asArray()
                ->all();
            $total = ProductOrder::find()->count();
        }

        foreach($ret as $key => $value) {
            $ret[$key]['status'] = Yii::$app->params['order_status'][$ret[$key]['status']];

            $ret[$key]['operation'] = "
            <a data-id='{$value['id']}' data-val='{$value['username']}' style='margin-top:5px !important;'  class='order-edit btn btn-xs btn-primary' href='javascript:void(0);'>编辑</a>
            <a data-id='{$value['id']}' data-val='{$value['username']}' style='margin-top:5px !important;'  class='order-express btn btn-xs btn-purple' href='javascript:void(0);'>关联快递号</a>
            <a data-id='{$value['id']}' data-val='{$value['username']}' style='margin-top:5px !important;' class='order-status btn btn-xs btn-info' href='javascript:void(0);'>状态</a>";
        }
        $output = [];
        $output['data'] = $ret;
        $output['recordsTotal'] = $total;
        $output['recordsFiltered'] = $total;

        echo json_encode($output);
    }

    public function actionInfo() {
        $params = Yii::$app->request->get();
        $id = $params['id'];

        $pl = new ProductOrder();
        $ret = ProductOrder::find()
            ->select('username,address,cellphone')
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
