<?php

namespace app\modules\product\controllers;

use Yii;
use app\controllers\AuthController;
use app\modules\product\models\ProductList;
use app\modules\product\models\Tags;
use app\modules\product\models\ProductTags;
use app\models\ProductInventory;
use app\models\ProductPackage;
use yii\helpers\Html;
use app\widgets\CategoryWidget;
use app\components\PriceHelper;

class AdminController extends AuthController
{
    public function actionIndex() {
        $tagHtml = $this->getTagsHtml();
        $products = ProductList::find()->where(['!=', 'category', 'package'])->asArray()->all();
        return $this->render('index',
            [
                'tagHtml'  => $tagHtml,
                'products' => $products,
            ]
        );
    }

    /**
     * 表格
     */
    public function actionTable() {
        $params = Yii::$app->request->post();

        $sql = "select id,`name`,price,num,unit,`desc`,slogan,status,booking_status,category from product_list ";
        
        $sqlCondition = [];

        $sqlCondition[] = " deleteflag = 0";

        if ($params['status'] > 0) {
            $sqlCondition[] = " status = " . $params['status'];
        }
        
        if ($params['booking_status'] > 0) {
            $sqlCondition[] = " booking_status = " . $params['booking_status'];
        }

        if (!empty($params['query'])) {
            $sqlCondition[] = " (`name` like '%" . $params['query'] . "%' or id = '" . $params['query'] . "')";
        }

        if (!empty($sqlCondition)) {
            $sql .= ' where ' . implode(' and ', $sqlCondition);
        }

        $totalSql = $sql;
        $sql .= " order by id desc limit " . $params['start'] . ', ' . $params['length'];

        $ret = ProductList::findBySql($sql)->asArray()->all();
        $total = ProductList::findBySql($totalSql)->count();

        foreach($ret as $key => $value) {
            if ($ret[$key]['status'] == 1) {
                $ret[$key]['status'] = "<span style='color:green'>销售中</span>";
            } else if ($ret[$key]['status'] == 2){
                $ret[$key]['status'] = "<span style='color:red'>已下线</span>";
            } else {
                $ret[$key]['status'] = "<span style='color:blue'>待上线</span>";
            }

            if ($ret[$key]['booking_status'] == 1) {
                $ret[$key]['booking_status'] = "<span style='color:green'>无限制</span>";
            } else {
                $ret[$key]['booking_status'] = "<span style='color:red'>仅预约</span>";
            }

            $ret[$key]['operation'] = "
            <a data-id='{$value['id']}' data-val='{$value['name']}' style='margin-top:5px !important;' class='product-edit btn btn-xs btn-primary' href='javascript:void(0);'>编辑</a>
            <a data-id='{$value['id']}' data-val='{$value['name']}' style='margin-top:5px !important;' class='product-tag btn btn-xs btn-purple' href='javascript:void(0);'>标签</a>
            <a data-id='{$value['id']}' data-val='{$value['name']}' style='margin-top:5px !important;' class='product-status btn btn-xs btn-info' href='javascript:void(0);'>销售状态</a>
            <a data-id='{$value['id']}' data-val='{$value['name']}' style='margin-top:5px !important;' class='booking-status btn btn-xs btn-primary' href='javascript:void(0);'>预约状态</a>
            <a data-id='{$value['id']}' data-val='{$value['name']}' style='margin-top:5px !important;' class='product-del btn btn-xs btn-danger' href='javascript:void(0);'>删除</a>";

            if ($ret[$key]['category'] == 'package') {
                $ret[$key]['operation'] .= "&nbsp;<a data-id='{$value['id']}' data-val='{$value['name']}' style='margin-top:5px !important;' class='product-connect btn btn-xs btn-warning' href='javascript:void(0);'>关联商品</a>";
            }

            $ret[$key]['category'] = CategoryWidget::$categorys[$ret[$key]['category']];
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
            ->select('name,price,unit,num,desc,slogan,category,buy_limit,img,fresh_percent')
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
            //TODO 更新套餐价格
            $packages = ProductPackage::find()->where(['product_id' => $params['id']])
                ->select('product_package_id')->distinct(true)->asArray()->all();

            if (!empty($packages)) {
                foreach($packages as $package) {
                    PriceHelper::updatePackagePrice($package['product_package_id']);
                }
            }

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
        $pl->deleteflag = 1;
        //TODO 更新套餐价格

        if ($pl->save()) {
            //TODO 更新套餐价格
            $packages = ProductPackage::find()->where(['product_id' => $params['id']])
            ->select('product_package_id')->distinct(true)->asArray()->all();

            if (!empty($packages)) {
                ProductPackage::deleteAll(['product_id' => $params['id']]);
                foreach($packages as $package) {
                    PriceHelper::updatePackagePrice($package['product_package_id']);
                }
            }

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
     * 预约标签设置
     */
    public function actionBookingstatus() {
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

    public function actionPackageinfo() {
        $params = Yii::$app->request->post();
        if(empty($params)){
            echo '参数不能为空';exit;
        }

        $id = $params['id'];
        $data = ProductPackage::find()->where(['product_package_id' => $id])
            ->select('product_id,num')->asArray()->all();

        echo json_encode($data);
    }

    /**
     * 预约状态设置
     */
    public function actionConnect() {
        $params = Yii::$app->request->post();
        if(empty($params)){
            echo '参数不能为空';exit;
        }

        $id = $params['id'];
        unset($params['id']);

        $connects = [];
        foreach($params as $key => $value) {
            if ($value > 0) {
                $tmp = explode('_', $key);
                $connects[$tmp[1]] = $value;
            }
        }

        if (empty($connects)) {
            echo '没有关联任何商品';
            Yii::$app->end();
        }

        ProductPackage::deleteAll(['product_package_id' => $id]);

        foreach($connects as $key => $item) {
            $ar = new ProductPackage();
            $ar->product_id = $key;
            $ar->product_package_id = $id;
            $ar->num = $item;
            $ar->save();
        }

        PriceHelper::updatePackagePrice($id);

        echo 'suc';
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
