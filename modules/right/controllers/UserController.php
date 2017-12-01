<?php

namespace app\modules\right\controllers;

use Yii;
use app\controllers\AuthController;
use app\modules\right\models\User;
use app\modules\right\models\Role;
use app\modules\right\models\UserRole;
use yii\helpers\Html;
use app\components\CommonHelper;

class UserController extends AuthController
{
    public function actionIndex() {
        $roleHtml = $this->getRoleHtml();
        return $this->render('index',
            [
                'roleHtml' => $roleHtml,
            ]
        );
    }

    /**
     * 添加
     * TODO 重名检测
     */
    public function actionAdd() {
        $params = Yii::$app->request->post();
        if(empty($params)){
            echo '参数不能为空';exit;
        }

        $password = CommonHelper::makeDefaultPassword($params['username']);
        $params['password'] = $password;

        $user = new User();
        foreach($params as $key => $value){
            $user->$key = $value;
        }

        $user->$key = $value;

        if($user->save()){
            echo 'suc';
        }else{
            echo '重名';
        }
    }

    /**
     * 表格
     */
    public function actionTable() {
        $params = Yii::$app->request->post();
        if (!empty($params['query'])) {
            $ret = User::find()->select('id,username,createtime')
                    ->where(['rootflag' => '0'])
                    ->andWhere(['like', 'username', $params['query']])
                    ->orWhere(['id' => intval($params['query'])])
                    ->asArray()->all();

            $total = User::find()
                    ->where(['rootflag' => '0'])
                    ->andWhere(['like', 'username', $params['query']])
                    ->orWhere(['id' => intval($params['query'])])
                    ->count();
        }else {
            $ret = User::find()
                ->select('id,username,createtime')
                ->where(['rootflag' => '0'])
                ->orderBy('id desc')->limit($params['length'])
                ->offset($params['start'])
                ->asArray()
                ->all();
            $total = User::find()->count();
        }

        foreach($ret as $key => $value) {
            $roleNames = $this->getUserRoleNames($value['id']);
            $ret[$key]['role'] = $roleNames;
            $ret[$key]['operation'] = "<a data-id='{$value['id']}' data-val='{$value['username']}' class='user-del btn btn-xs btn-danger' href='javascript:void(0);'>删除</a>
            <a data-id='{$value['id']}' data-val='{$value['username']}' class='user-role btn btn-xs btn-purple' href='javascript:void(0);'>角色</a>";
        }
        $output = [];
        $output['data'] = $ret;
        $output['recordsTotal'] = $total;
        $output['recordsFiltered'] = $total;

        echo json_encode($output);
    }

/*     public function actionImport() {
        $filename = 'chunhua_user.txt';
        $handle = fopen($filename, "r");

        while (!feof($handle)) {
            $line = fgets($handle);
            $name = trim($line);
            $user = new User();
            $user->username = $name;
            $user->save();

            $userRole = new UserRole();
            $userRole->user_id = $user->id;
            $userRole->role_id = 1;
            $userRole->save();
        }

        fclose($handle);
    } */


    /**
     * 删除
     */
    public function actionDel() {
        $params = Yii::$app->request->post();
        if(empty($params)){
            echo '参数不能为空';exit;
        }

        $user = User::findOne($params['id']);

        if($user->delete()){
            UserRole::deleteAll(['user_id' => $params['id']]);
            echo 'suc';
        }else{
            echo '删除失败';
        }
    }

    /**
     * 角色设置
     */
    public function actionRole() {
        $params = Yii::$app->request->post();
        if(empty($params)){
            echo '参数不能为空';exit;
        }

        $id = $params['id'];
        $roles = $params['role'];

        /* if(empty($roles)){
            echo '角色不能为空';exit;
        } */
        //删除old
        UserRole::deleteAll(['user_id' => $id]);

        try {
            if (!empty($roles)) {
                $role_key = 'role_id';
                $user_key = 'user_id';
                foreach($roles as $key => $value){
                    $userModAdd = new UserRole();
                    $userModAdd->$role_key = $value;
                    $userModAdd->$user_key = $id;
                    $userModAdd->save();
                }
            }

            echo 'suc';
        } catch (Exception $e) {
            echo '设置失败';
        }
    }

    /**
     * 已有角色权限
     */
    public function actionRoleme() {
        $params = Yii::$app->request->post();

        $id = $params['id'];
        $roles = UserRole::find()->select('role_id')->where(['user_id' => $id])->asArray()->all();

        $ret = [];
        foreach($roles as $role){
            $ret[] = $role['role_id'];
        }

        echo json_encode($ret);
    }

    /**
     * 获取角色列表
     * @return string
     */
    private function getRoleHtml(){
        $ret = Role::find()->select('id,name')->asArray()->all();

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
                $html .= Html::tag('td', Html::encode($v));
            }

            //角色列表
            $roleNames = $this->getUserRoleNames($value['id']);
            $html .= Html::tag('td', Html::encode($roleNames));

            $html .= Html::tag('td',"
                <a data-id='{$value['id']}' data-val='{$value['username']}' class='user-del btn btn-xs btn-danger' href='javascript:void(0);'>删除</a>
                <a data-id='{$value['id']}' data-val='{$value['username']}' class='user-role btn btn-xs btn-purple' href='javascript:void(0);'>角色</a>
                ");
            $html .= '</tr>';
        }
        return $html;
    }

    /**
     * 获取用户角色名称列表
     * @param int $userId
     * @return string
     */
    private function getUserRoleNames($userId){
        $roles = UserRole::find()->select('role_id')->where(['user_id' => $userId])->asArray()->all();
        if(empty($roles)){
            return '-';
        }

        $list = [];
        foreach($roles as $role){
            $list[] = $role['role_id'];
        }

        $ret = [];
        $names = Role::find()->select('name')->where(['id' => $list])->asArray()->all();
        foreach($names as $name){
            $ret[] = $name['name'];
        }

        return implode(',', $ret);
    }
}
