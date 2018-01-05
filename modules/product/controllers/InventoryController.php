<?php

namespace app\modules\product\controllers;

use Yii;
use app\controllers\AuthController;
use app\modules\product\models\ProductList;
use app\models\ProductInventory;
use yii\helpers\Html;

class InventoryController extends AuthController
{
    public function actionIndex() {
        $products = ProductList::find()->where(['!=', 'category', 'package'])->asArray()->all();
        return $this->render('index',
            [
                'products' => $products,
            ]
        );
    }

    /**
     * 表格
     */
    public function actionTable() {
        $params = Yii::$app->request->post();

        $sql = "select pi.id, pi.pid, pi.price, pi.num, pi.memo, pl.name, pl.num as current_num, pl.price as current_price, pi.operator_id, pi.operator from product_inventory as pi, product_list as pl where pi.pid = pl.id ";
        $sqlCondition = [];

        if (!empty($params['query'])) {
            $sqlCondition[] = " (`pl.name` like '%" . $params['query'] . "%' or pi.id = '" . $params['query'] . "')";
        }

        if (!empty($sqlCondition)) {
            $sql .= implode(' and ', $sqlCondition);
        }

        $totalSql = $sql;
        $sql .= " order by id desc limit " . $params['start'] . ', ' . $params['length'];

        $ret = ProductInventory::findBySql($sql)->asArray()->all();
        $total = ProductInventory::findBySql($totalSql)->count();

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
            ->select('name,price,unit,num,desc,slogan,category,buy_limit,img')
            ->where(['id' => $id])
            ->asArray()
            ->one();

        echo json_encode($ret);
    }

    /**
     * 添加
     */
    public function actionAdd() {
        $params = Yii::$app->request->post();
        if(empty($params)){
            echo '参数不能为空';
            exit;
        }

        $pl = new ProductInventory();
        foreach($params as $key => $value){
            $pl->$key = $value;
        }
        $pl->operator_id = Yii::$app->session['uid'];

        if ($pl->save()) {
            $up = ProductList::findOne($params['pid']);
            if ($params['operator'] == 1) {
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
}
