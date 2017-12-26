<?php

namespace app\modules\product\controllers;

use Yii;
use app\controllers\AuthController;
use app\modules\product\models\ProductList;
use app\modules\product\models\Tags;
use app\modules\product\models\ProductTags;
use app\models\ProductInventory;
use yii\helpers\Html;

class AdminController extends AuthController
{
    public function actionIndex() {
        $tagHtml = $this->getTagsHtml();
        return $this->render('index',
            [
                'tagHtml' => $tagHtml,
            ]
        );
    }

    /**
     * 表格
     */
    public function actionTable() {
        $params = Yii::$app->request->post();

        $searchDb = ProductList::find()->select('id,name,price,unit,desc,slogan,status');
        $totalDb = ProductList::find();

        $sql = "select id,`name`,price,num,unit,`desc`,slogan,status from product_list ";
        $sqlCondition = [];

        if (!empty($params['status'])) {
            $sqlCondition[] = " status = " . $params['status'];
        }

        if (!empty($params['booking_status'])) {
            $sqlCondition[] = " booking_status = " . $params['booking_status'];
        }

        if (!empty($params['query'])) {
            $sqlCondition[] = " (name like " . $params['query'] . " or id like " . $params['query'] . ")";
        }

        if (!empty($sqlCondition)) {
            $sql .= ' where ' . implode(' and ', $sqlCondition);
        }

        $ret = ProductList::findBySql($sql)->asArray()->all();
        $total = ProductList::findBySql($sql)->count();

        foreach($ret as $key => $value) {
            if ($ret[$key]['status'] == 1) {
                $ret[$key]['status'] = "<span style='color:green'>销售中</span>";
            } else if ($ret[$key]['status'] == 2){
                $ret[$key]['status'] = "<span style='color:red'>已下线</span>";
            } else {
                $ret[$key]['status'] = "<span style='color:yellow'>待上线</span>";
            }

            $ret[$key]['operation'] = "
            <a data-id='{$value['id']}' data-val='{$value['name']}' style='margin-top:5px !important;' class='product-edit btn btn-xs btn-primary' href='javascript:void(0);'>编辑</a>
            <a data-id='{$value['id']}' data-val='{$value['name']}' style='margin-top:5px !important;' class='product-tag btn btn-xs btn-purple' href='javascript:void(0);'>标签</a>
            <a data-id='{$value['id']}' data-val='{$value['name']}' style='margin-top:5px !important;' class='product-status btn btn-xs btn-info' href='javascript:void(0);'>销售状态</a>
            <a data-id='{$value['id']}' data-val='{$value['name']}' style='margin-top:5px !important;' class='product-booking btn btn-xs btn-warning' href='javascript:void(0);'>预约设置</a>
            <a data-id='{$value['id']}' data-val='{$value['name']}' style='margin-top:5px !important;' class='product-inventory btn btn-xs btn-dark' href='javascript:void(0);'>库存管理</a>
            <a data-id='{$value['id']}' data-val='{$value['name']}' style='margin-top:5px !important;' class='product-del btn btn-xs btn-danger' href='javascript:void(0);'>删除</a>";
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

        $ret = ProductList::find()
            ->select('name,price,unit,num,desc,slogan,category,buy_limit')
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

        $pl = new ProductList();
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

        $pl = ProductList::findOne($params['id']);
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

        $pl = ProductList::findOne($params['id']);

        if ($pl->delete()) {
            echo 'suc';
        } else {
            echo '删除失败';
        }
    }

    /**
     * 标签设置
     */
    public function actionTag() {
        $params = Yii::$app->request->post();
        if(empty($params)){
            echo '参数不能为空';exit;
        }

        $id = $params['id'];
        $tags = $params['tag'];

        //删除old
        ProductTags::deleteAll(['product_id' => $id]);

        try {
            if (!empty($tags)) {
                $tag_key = 'tag_id';
                $product_key = 'product_id';
                foreach($tags as $key => $value){
                    $productTagAdd = new ProductTags();
                    $productTagAdd->$tag_key = $value;
                    $productTagAdd->$product_key = $id;
                    $productTagAdd->save();
                }
            }

            echo 'suc';
        } catch (Exception $e) {
            echo '设置失败';
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
            $pl = ProductList::findOne($id);
            $pl->status = $status;
            $pl->save();

            echo 'suc';
        } catch (Exception $e) {
            echo '设置失败';
        }
    }

    /**
     * 预约状态设置
     */
    public function actionBooking() {
        $params = Yii::$app->request->post();
        if(empty($params)){
            echo '参数不能为空';exit;
        }

        $id = $params['id'];
        $status = $params['status'];

        try {
            $pl = ProductList::findOne($id);
            $pl->booking_status = $status;
            $pl->save();

            echo 'suc';
        } catch (Exception $e) {
            echo '设置失败';
        }
    }

    /**
     * 已有标签权限
     */
    public function actionTagme() {
        $params = Yii::$app->request->post();

        $id = $params['id'];
        $tags = ProductTags::find()->select('tag_id')->where(['product_id' => $id])->asArray()->all();

        $ret = [];
        foreach($tags as $tag){
            $ret[] = $tag['tag_id'];
        }

        echo json_encode($ret);
    }

    /**
     * 已有标签权限
     */
    public function actionInventory() {
        $params = Yii::$app->request->post();

        if(empty($params)){
            echo '参数不能为空';
            exit;
        }

        $params['operator_id'] = Yii::$app->session['uid'];

        $pl = new ProductInventory();
        foreach($params as $key => $value){
            $pl->$key = $value;
        }

        if ($pl->save()) {
            $up = ProductList::findOne($params['pid']);
            if ($params['operator_func'] == 1) {
                $up->num = $up->num + $params['num'];
            } else {
                $up->num = $up->num - $params['num'];
            }
            $up->save();

            echo 'suc';
        } else {
            echo 'fail';
        }
    }

    /**
     * 获取角色列表
     * @return string
     */
    private function getTagsHtml(){
        $ret = Tags::find()->select('id,name')->asArray()->all();

        $html = '';
        foreach($ret as $key => $value){
            $html .= Html::tag('option', Html::encode($value['name']), ['value' => $value['id']]);
        }

        return $html;
    }

}
