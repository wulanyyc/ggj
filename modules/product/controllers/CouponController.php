<?php

namespace app\modules\product\controllers;

use Yii;
use app\controllers\AuthController;
use app\modules\product\models\Coupon;
use yii\helpers\Html;

class CouponController extends AuthController
{
    public function actionIndex() {
        return $this->render('index');
    }

    /**
     * 表格
     */
    public function actionTable() {
        $params = Yii::$app->request->post();
        if (!empty($params['query'])) {
            $ret = Coupon::find()->select('id,name,type,money,day,start_date,end_date,desc')
                    ->where(['like', 'name', $params['query']])
                    ->orWhere(['id' => intval($params['query'])])
                    ->offset($params['start'])->asArray()->all();

            $total = Coupon::find()
                    ->where(['like', 'name', $params['query']])
                    ->orWhere(['id' => intval($params['query'])])
                    ->count();
        }else {
            $ret = Coupon::find()
                ->select('id,name,type,money,day,start_date,end_date,desc')
                ->orderBy('id desc')->limit($params['length'])
                ->offset($params['start'])
                ->asArray()
                ->all();
            $total = Coupon::find()->count();
        }

        foreach($ret as $key => $value) {
            $ret[$key]['operation'] = "
            <a data-id='{$value['id']}' data-val='{$value['name']}' class='coupon-edit btn btn-xs btn-primary' href='javascript:void(0);'>编辑</a>
            <a data-id='{$value['id']}' data-val='{$value['name']}' class='coupon-del btn btn-xs btn-danger' href='javascript:void(0);'>删除</a>";

            $ret[$key]['type'] = ($ret[$key]['type'] == 1) ? '限单张' : '多张可用';
        }
        $output = [];
        $output['data'] = $ret;
        $output['recordsTotal'] = $total;
        $output['recordsFiltered'] = $total;

        echo json_encode($output);
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

        $startDate = date('Ymd', time());
        $endDate = date('Ymd', time() + 86400 * $params['day']);
        $pl = new Coupon();
        foreach($params as $key => $value){
            $pl->$key = $value;
        }

        $pl->start_date = $startDate;
        $pl->end_date = $endDate;

        if($pl->save()){
            echo 'suc';
        }else{
            echo 'fail';
        }
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

        $pl = Coupon::findOne($params['id']);

        $startDate = $pl->start_date;
        $endDate = date('Ymd', strtotime($startDate) + 86400 * $params['day']);
        foreach($params as $key => $value){
            if($key != 'id'){
                $pl->$key = $value;
            }
        }

        $pl->start_date = $startDate;
        $pl->end_date = $endDate;

        if($pl->save()){
            echo 'suc';
        }else{
            echo 'fail';
        }
    }

    /**
     * 删除
     */
    public function actionDel() {
        $params = Yii::$app->request->post();
        if(empty($params)){
            echo '参数不能为空';exit;
        }

        $pl = Coupon::findOne($params['id']);

        if($pl->delete()) {
            echo 'suc';
        }else{
            echo '删除失败';
        }
    }

    public function actionInfo() {
        $params = Yii::$app->request->get();
        $id = $params['id'];

        $pl = new Coupon();
        $ret = Coupon::find()
            ->select('name,money,day,type,desc')
            ->where(['id' => $id])
            ->asArray()
            ->one();

        echo json_encode($ret);
    }
}
