<?php

namespace app\modules\product\controllers;

use Yii;
use app\controllers\AuthController;
use app\modules\product\models\ProductPackage;
use app\modules\product\models\ProductList;
// use app\modules\product\models\Products;
// use app\modules\product\models\ProductProducts;
use yii\helpers\Html;

class PackageController extends AuthController
{
    public function actionIndex() {
        $productHtml = $this->getProductHtml();
        return $this->render('index',
            [
                'productHtml' => $productHtml,
            ]
        );
    }

    /**
     * 表格
     */
    public function actionTable() {
        $params = Yii::$app->request->post();
        if (!empty($params['query'])) {
            $ret = ProductPackage::find()->select('id,name,price,desc,slogan,disabled')
                ->where(['like', 'name', $params['query']])
                ->orWhere(['id' => intval($params['query'])])
                ->offset($params['start'])->asArray()->all();

            $total = ProductPackage::find()
                ->where(['like', 'name', $params['query']])
                ->orWhere(['id' => intval($params['query'])])
                ->count();
        }else {
            $ret = ProductPackage::find()
                ->select('id,name,price,desc,slogan,disabled')
                ->orderBy('id desc')->limit($params['length'])
                ->offset($params['start'])
                ->asArray()
                ->all();
            $total = ProductPackage::find()->count();
        }

        foreach($ret as $key => $value) {
            if ($ret[$key]['disabled'] == 0) {
                $ret[$key]['disabled'] = "销售中";
            } else {
                $ret[$key]['disabled'] = "<span style='color:red'>已下线</span>";
            }

            $ret[$key]['operation'] = "
            <a data-id='{$value['id']}' data-val='{$value['name']}' class='product-edit btn btn-xs btn-primary' href='javascript:void(0);'>编辑</a>
            <a data-id='{$value['id']}' data-val='{$value['name']}' class='product-set btn btn-xs btn-purple' href='javascript:void(0);'>关联产品</a>
            <a data-id='{$value['id']}' data-val='{$value['name']}' class='product-status btn btn-xs btn-info' href='javascript:void(0);'>状态</a>
            <a data-id='{$value['id']}' data-val='{$value['name']}' class='product-del btn btn-xs btn-danger' href='javascript:void(0);'>删除</a>";
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

        $pl = new ProductPackage();
        $ret = ProductPackage::find()
            ->select('name,desc,slogan')
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

        $pl = new ProductPackage();
        foreach($params as $key => $value){
            $pl ->$key = $value;
        }

        $pl ->$key = $value;

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

        // echo json_encode($params);

        $pl = ProductPackage::findOne($params['id']);
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
     * 删除
     */
    public function actionDel() {
        $params = Yii::$app->request->post();
        if(empty($params)){
            echo '参数不能为空';exit;
        }

        $pl = ProductPackage::findOne($params['id']);

        if($pl->delete()) {
            echo 'suc';
        }else{
            echo '删除失败';
        }
    }

    /**
     * 标签设置
     */
    public function actionProduct() {
        $params = Yii::$app->request->post();
        if(empty($params)){
            echo '参数不能为空';exit;
        }

        $id = $params['id'];
        $products = $params['pid'];

        // print_r($products);exit;
        try {
            if (!empty($products)) {
                $pp = ProductPackage::findOne($id);
                $pp->product_ids = implode(',', $products);
                $pp->save();
            }

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
            $pl = ProductPackage::findOne($id);
            $pl->disabled = $status;
            $pl->save();

            echo 'suc';
        } catch (Exception $e) {
            echo '设置失败';
        }
    }

    /**
     * 已有标签权限
     */
    public function actionProductme() {
        $params = Yii::$app->request->post();

        $id = $params['id'];
        $data = ProductPackage::find()->select('product_ids')->where(['id' => $id])->asArray()->one();

        $products = explode(',', $data['product_ids']);

        echo json_encode($products);
    }

    /**
     * 获取角色列表
     * @return string
     */
    private function getProductHtml(){
        $ret = ProductList::find()->select('id,name')->asArray()->all();

        $html = '';
        foreach($ret as $key => $value){
            $html .= Html::tag('option', Html::encode($value['name']), ['value' => $value['id']]);
        }

        return $html;
    }

    /**
     * 生成表格html
     * @param array $data
     * @return string
     */
    private function buildTableHtml($data){
        $html = '';
        foreach($data as $key => $value){
            $html .= '<tr>';
            foreach($value as $k => $v){
                $html .= Html::product('td', Html::encode($v));
            }

            //角色列表
            $roleNames = $this->getUserRoleNames($value['id']);
            $html .= Html::product('td', Html::encode($roleNames));

            $html .= Html::product('td',"
                <a data-id='{$value['id']}' data-val='{$value['username']}' class='user-del btn btn-xs btn-danger' href='javascript:void(0);'>删除</a>
                <a data-id='{$value['id']}' data-val='{$value['username']}' class='user-role btn btn-xs btn-purple' href='javascript:void(0);'>角色</a>
                ");
            $html .= '</tr>';
        }
        return $html;
    }
}
