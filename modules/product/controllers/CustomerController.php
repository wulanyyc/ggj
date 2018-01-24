<?php

namespace app\modules\product\controllers;

use Yii;
use app\controllers\AuthController;
use app\components\SiteHelper;
use app\components\PriceHelper;
use app\models\Customer;
use app\models\CustomerMoneyList;

class CustomerController extends AuthController
{
    public function actionIndex() {
        return $this->render('index');
    }

    /**
     * 表格
     */
    public function actionTable() {
        $params = Yii::$app->request->post();

        $sql = "select id,`nick`,phone,money,score,status,create_time from customer ";
        
        $sqlCondition = [];
        if ($params['status'] > 0) {
            $sqlCondition[] = " status = " . $params['status'];
        }

        if (!empty($params['query'])) {
            $sqlCondition[] = " (`nick` like '%" . $params['query'] . "%' or phone like '%" . $params['query'] . "%')";
        }

        if (!empty($sqlCondition)) {
            $sql .= ' where ' . implode(' and ', $sqlCondition) . ' and deleteflag =0';
        } else {
            $sql .= ' where deleteflag =0';
        }

        $totalSql = $sql;
        $sql .= " order by id desc limit " . $params['start'] . ', ' . $params['length'];

        $ret = Customer::findBySql($sql)->asArray()->all();
        $total = Customer::findBySql($totalSql)->count();

        foreach($ret as $key => $value) {
            if ($ret[$key]['status'] == 1) {
                $ret[$key]['status'] = "<span style='color:red'>手机</span>";
            } else if ($ret[$key]['status'] == 2){
                $ret[$key]['status'] = "<span style='color:green'>微信</span>";
            }

            $ret[$key]['operation'] = "
            <a data-id='{$value['id']}' data-val='{$value['phone']}' style='margin-top:5px !important;' class='customer-edit btn btn-xs btn-primary' href='javascript:void(0);'>编辑</a>
            <a data-id='{$value['id']}' data-val='{$value['phone']}' style='margin-top:5px !important;' class='customer-status btn btn-xs btn-info' href='javascript:void(0);'>状态</a>
            <a data-id='{$value['id']}' data-val='{$value['phone']}' style='margin-top:5px !important;' class='customer-money btn btn-xs btn-purple' href='javascript:void(0);'>余额设置</a>
            <a data-id='{$value['id']}' data-val='{$value['phone']}' style='margin-top:5px !important;' class='customer-del btn btn-xs btn-danger' href='javascript:void(0);'>删除</a>";
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

        $ret = Customer::find()
            ->select('nick,phone')
            ->where(['id' => $id])
            ->asArray()
            ->one();

        echo json_encode($ret);
    }

    /**
     * 添加
     * TODO 重名检测
     */
    public function actionAdd() {
        $params = Yii::$app->request->post();
        if(empty($params)){
            echo '参数不能为空';
            exit;
        }

        if (!SiteHelper::checkPhone($params['phone'])) {
            echo '手机格式不正确';
            exit;
        }


        $pl = new Customer();
        foreach($params as $key => $value){
            $pl->$key = $value;
        }

        if ($pl->save()) {
            echo 'suc';
        } else {
            echo 'fail';
        }
    }

    /**
     * 添加
     * TODO 重名检测
     */
    public function actionEdit() {
        $params = Yii::$app->request->post();
        if (empty($params)) {
            echo '参数不能为空';
            exit;
        }

        if (!SiteHelper::checkPhone($params['phone'])) {
            echo '手机格式不正确';
            exit;
        }

        $pl = Customer::findOne($params['id']);
        foreach($params as $key => $value) {
            if ($key != 'id') {
                $pl->$key = $value;
            }
        }

        if ($pl->save()) {
            echo 'suc';
        } else {
            echo 'fail';
        }
    }

    /**
     * 删除
     */
    public function actionDel() {
        $params = Yii::$app->request->post();
        if (empty($params)) {
            echo '参数不能为空';exit;
        }

        $pl = Customer::findOne($params['id']);
        $pl->deleteflag = 1;

        if ($pl->save()) {
            echo 'suc';
        } else {
            echo '删除失败';
        }
    }

    /**
     * 销售标签设置
     */
    public function actionStatus() {
        $params = Yii::$app->request->post();
        if(empty($params)){
            echo '参数不能为空';exit;
        }

        $id = $params['id'];
        $status = $params['status'];

        try {
            $pl = Customer::findOne($id);
            $pl->status = $status;
            $pl->save();

            echo 'suc';
        } catch (Exception $e) {
            echo '设置失败';
        }
    }

    public function actionMoney() {
        $params = Yii::$app->request->post();
        if (empty($params)) {
            echo '参数不能为空';exit;
        }

        PriceHelper::adjustWallet($params['id'], $params['money'], $params['operator'], $params['reason']);
        echo 'suc';
    }

}
