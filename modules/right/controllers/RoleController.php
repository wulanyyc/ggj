<?php

namespace app\modules\right\controllers;

use Yii;
use app\controllers\AuthController;
use app\modules\right\models\User;
use app\modules\right\models\Role;
use app\modules\right\models\Mod;
use app\modules\right\models\MenuMod;
use app\modules\right\models\RoleMod;
use app\modules\right\models\UserRole;
use yii\helpers\Html;

class RoleController extends AuthController
{
    /**
     * 首页
     * @return obj
     */
    public function actionIndex() {
        $ret = Role::find()->orderBy('id desc')->asArray()->all();
        $html = $this->buildTableHtml($ret);
        
        $moduleHtml = $this->buildAllModuleHtml();
        
        $roleSelectHtml = $this->buildRoleHtml($ret);
        return $this->render('index', 
            [
                'table' => $html,
                'module' => $moduleHtml,
                'roleSelectHtml' => $roleSelectHtml,
            ]
        );
    }
    
    /**
     * 表格
     */
    public function actionTable() {
        $params = Yii::$app->request->post();
        
        $join = 'ur.user_id = u.id';
        $where = ['ur.role_id' => $params['id']];
        
        $select = [
            'ur.id as id',
            'u.username as username',
            'ur.create_time as createtime',
        ];
        
        if (!empty($params['query'])) {
            $cmd = (new \yii\db\Query())->from('right_user_role ur')
                ->leftJoin('right_user u', $join)
                ->orderBy('u.id desc')->limit($params['length'])->offset($params['start'])
                ->where($where)->andWhere(['like', 'u.username', $params['query']]);
            
            $data = $cmd->select($select)->all(\Yii::$app->db);
            
            $total = (new \yii\db\Query())->from('right_user_role ur')
                ->leftJoin('right_user u', $join)
                ->where($where)->andWhere(['like', 'u.username', $params['query']])
                ->all(\Yii::$app->db);
             
             $total = count($total);
        }else {
            $cmd = (new \yii\db\Query())->from('right_user_role ur')
                ->leftJoin('right_user u', $join)
                ->orderBy('u.id desc')->limit($params['length'])->offset($params['start'])
                ->where($where);
            
            $data = $cmd->select($select)->all(\Yii::$app->db);
            
            $total = (new \yii\db\Query())->from('right_user_role ur')->leftJoin('right_user u', $join)
                ->where($where)->all(\Yii::$app->db);
            
            $total = count($total);
        }
        
        foreach($data as $key => $value) {
            $data[$key]['operation'] = "<a data-id='{$value['id']}' data-val='{$value['username']}' class='user-del btn btn-xs btn-danger' href='javascript:void(0);'>删除</a>";
        }
        $output = [];
        $output['data'] = $data;
        $output['recordsTotal'] = $total;
        $output['recordsFiltered'] = $total;

        echo json_encode($output);
    }
    
    /**
     * 信息
     */
    public function actionInfo() {
        $params = Yii::$app->request->get();
        $data = Role::find()->where(['id' => $params['id']])->asArray()->one();
        echo json_encode($data);
    }
    
    /**
     * 添加
     */
    public function actionAdd() {
        $params = Yii::$app->request->post();
        if(empty($params)){
            echo '参数不能为空';exit;
        }
        
        $role = new Role();
        foreach($params as $key => $value){
            $role->$key = $value;
        }
        
        if($role->save()){
            echo 'suc'; 
        }else{
            echo '重名';
        }
    }
    
    /**
     * 添加
     */
    public function actionAdduser() {
        $params = Yii::$app->request->post();
        if(empty($params)){
            echo '参数不能为空';exit;
        }
        
        $rid = $params['rid'];
        $uids = $params['uid'];
        
        $rkey = 'role_id';
        $ukey = 'user_id';
        
        foreach($uids as $key => $value){
            try {
                $userRole = new UserRole();
                $userRole->$rkey = $rid;
                $userRole->$ukey = $value;
                $userRole->save();
            } catch (Exception $e) {
                echo '添加失败';exit;
            }
        }
        
        echo 'suc';
    }
    
    /**
     * 编辑
     */
    public function actionEdit() {
        $params = Yii::$app->request->post();
        if(empty($params)){
            echo '参数不能为空';exit;
        }
        
        $role = Role::findOne($params['id']);
        foreach($params as $key => $value){
            if($key != 'id'){
                $role->$key = $value;
            }
        }
    
        if($role->save()){
            echo 'suc';
        }else{
            echo '格式有误，请检查';
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
        
        $role = Role::findOne($params['id']);
        
        if($role->delete()){
            RoleMod::deleteAll(['role_id' => $params['id']]);
            UserRole::deleteAll(['role_id' => $params['id']]);
            echo 'suc';
        }else{
            echo '删除失败';
        }
    }
    
    /**
     * 删除user
     */
    public function actionDeluser() {
        $params = Yii::$app->request->post();
        if(empty($params)){
            echo '参数不能为空';exit;
        }
    
        if(UserRole::deleteAll(['id' => $params['id']])){
            echo 'suc';
        }else{
            echo '删除失败';
        }
    }
    
    /**
     * 更新权限
     */
    public function actionRight() {
        $params = Yii::$app->request->post();
        if(empty($params)){
            echo '参数不能为空';exit;
        }
        
        $id = $params['id'];
        $rights = isset($params['mod']) ? $params['mod'] : [];
        
        RoleMod::deleteAll(['role_id' => $id]);
        
        if(!empty($rights)){
            $role_key = 'role_id';
            $mod_key = 'module_id';
            try {
                foreach($rights as $key => $value){
                    $roleModAdd = new RoleMod();
                    $roleModAdd->$role_key = $id;
                    $roleModAdd->$mod_key = $value;
                    $roleModAdd->save();
                }
                
                echo 'suc';
            } catch (Exception $e) {
                echo '设置失败';
            }
        }else{
            echo 'suc';
        }
    }
    
    /**
     * 历史
     */
    public function actionHistory() {
        $params = Yii::$app->request->post();
        
        $data = RoleMod::find()->select('module_id')->where(['role_id' => $params['id']])->asArray()->all();
        
        $ret = [];
        if(!empty($data)){
            foreach($data as $key => $value){
                $ret[] = $value['module_id'];
            }
        }
        
        echo json_encode($ret);
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
            $html .= Html::tag('td', 
                "<a data-id='{$value['id']}' data-val='{$value['name']}' class='role-edit btn btn-xs btn-info' href='javascript:void(0);'>修改</a>
                 <a data-id='{$value['id']}' data-val='{$value['name']}' class='role-del btn btn-xs btn-danger' href='javascript:void(0);'>删除</a>
                 <a data-id='{$value['id']}' data-val='{$value['name']}' class='role-module btn btn-xs btn-purple' href='javascript:void(0);'>模块配置</a>
                ");
            $html .= '</tr>';
        }
        return $html;
    }
    
    /**
     * 模块table
     * @return string
     */
    private function buildAllModuleHtml(){
        $data = MenuMod::find()->asArray()->all();
        $html = '';
        foreach($data as $value) {
            $html .= $this->buildModuleHtml($value);
        }
        return $html;
    }
    
    /**
     * 模块table
     * @param array $data
     * @return string
     */
    private function buildModuleHtml($data){
        $others = [];
        $nodes = [];
        $groups = [];
        
        $ret = Mod::find()->select('id,type,text')->where(['type' => ['other', 'group_parent', 'node'], 'menu_id' => $data['id']])->asArray()->all();
        foreach($ret as $key => $value){
            if($value['type'] == 'node'){
                $nodes[$value['id']] = $value;
            }
            
            if($value['type'] == 'group_parent'){
                $childs = Mod::find()->select('id,type,text')->where(['group_parent_id' => $value['id']])->asArray()->all();
                if(!empty($childs)){
                    $groups[$value['id']] = $value;
                    $groups[$value['id']]['child'] = $childs;
                }
            }
            
            if($value['type'] == 'other'){
                $others[$value['id']] = $value;
            }
        }
        
        $html = <<<EOF
        <div style='padding-bottom:5px;'>主菜单：<span style='color:green'>{$data['text']}</span></div>
        <table class='bordered' width='100%' cellspacing='0' style='margin-bottom:10px;'>
            <thead>
                <tr role='row'>
                    <th>类型</th>
                    <th>权限</th>
                </tr>
            </thead>
        <tbody>
EOF;
        if(!empty($nodes)){
            $html .= '<tr><td>页面</td><td>';
            foreach($nodes as $node){
                $html .=  <<<EOF
                <div style='display:inline;padding-right:10px' class='checkbox'>
                    <label>
                        <input type='checkbox' value='{$node['id']}' name='mod[]' class='colored-primary'>
                        <span class='text'>{$node['text']}</span>
                    </label>
                </div>
EOF;
            }
            $html .= '</td></tr>';
        }
        
        if(!empty($groups)){
            foreach($groups as $key => $group) {
                $html .= "<tr><td>组：{$group['text']}</td><td>";
                foreach($group['child'] as $node){
                    $html .=  <<<EOF
                    <div style='display:inline;padding-right:10px' class='checkbox'>
                        <label>
                            <input type='checkbox' value='{$node['id']}' name='mod[]' class='colored-primary'>
                            <span class='text'>{$node['text']}</span>
                        </label>
                    </div>
EOF;
                }
                $html .= '</td></tr>';
            }
        }
        
        if(!empty($others)){
            $html .= '<tr><td>特殊权限</td><td>';
            foreach($others as $node){
                $html .=  <<<EOF
                <div style='display:inline;padding-right:10px' class='checkbox'>
                    <label>
                        <input type='checkbox' value='{$node['id']}' name='mod[]' class='colored-primary'>
                        <span class='text'>{$node['text']}</span>
                    </label>
                </div>
EOF;
            }
            $html .= '</td></tr>';
        }
        $html .= '</tbody></table>';
        
        return $html;
    }
    
    /**
     * 生成角色选择html
     * @param unknown $roles
     * @return string
     */
    private function buildRoleHtml($roles) {
        $html = '';
        foreach($roles as $key => $value){
            $html .= Html::tag('option', Html::encode($value['name']), ['value' => $value['id']]);
        }
        
        return $html;
    }
    
    /**
     * 获取用户列表
     * @return string
     */
    private function getUserHtml(){
        $ret = User::find()->select('id,username')->asArray()->all();
    
        $html = '';
        foreach($ret as $key => $value){
            $html .= Html::tag('option', Html::encode($value['username']), ['value' => $value['id']]);
        }
    
        return $html;
    }
    
    /**
     * 已有用户id
     */
    public function actionUserme() {
        $params = Yii::$app->request->post();
    
        $id = $params['id'];
        $users = UserRole::find()->select('user_id')->where(['role_id' => $id])->asArray()->all();
        
        $all = User::find()->orderBy('id desc')->asArray()->all();
        
        $exsit_user = [];
        foreach($users as $user){
            $exsit_user[] = $user['user_id'];
        }
        
        $html = '';
        foreach($all as $key => $value){
            if (!in_array($value['id'], $exsit_user)){
                $html .= Html::tag('option', Html::encode($value['username']), ['value' => $value['id']]);
            }
        }
        
        echo $html;
    }
}
