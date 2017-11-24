<?php

namespace app\modules\product\controllers;

use Yii;
use app\controllers\AuthController;
use app\modules\product\models\Tags;
use yii\helpers\Html;
// use app\components\CommonHelper;

class TagController extends AuthController
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
            $ret = Tags::find()->select('id,name,en_name,create_time')
                    ->where(['like', 'name', $params['query']])
                    ->orWhere(['id' => intval($params['query'])])
                    ->offset($params['start'])->asArray()->all();

            $total = Tags::find()
                    ->where(['like', 'name', $params['query']])
                    ->orWhere(['id' => intval($params['query'])])
                    ->count();
        }else {
            $ret = Tags::find()
                ->select('id,name,en_name,create_time')
                ->orderBy('id desc')->limit($params['length'])
                ->offset($params['start'])
                ->asArray()
                ->all();
            $total = Tags::find()->count();
        }

        foreach($ret as $key => $value) {
            $ret[$key]['operation'] = "
            <a data-id='{$value['id']}' data-val='{$value['name']}' class='tag-edit btn btn-xs btn-primary' href='javascript:void(0);'>编辑</a>
            <a data-id='{$value['id']}' data-val='{$value['name']}' class='tag-del btn btn-xs btn-danger' href='javascript:void(0);'>删除</a>";
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

        $pl = new Tags();
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

        $pl = Tags::findOne($params['id']);
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

        $pl = Tags::findOne($params['id']);

        if($pl->delete()) {
            echo 'suc';
        }else{
            echo '删除失败';
        }
    }

    public function actionInfo() {
        $params = Yii::$app->request->get();
        $id = $params['id'];

        $pl = new Tags();
        $ret = Tags::find()
            ->select('name,en_name')
            ->where(['id' => $id])
            ->asArray()
            ->one();

        echo json_encode($ret);
    }
}
