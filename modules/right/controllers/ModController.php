<?php

namespace app\modules\right\controllers;

use Yii;
use app\controllers\AuthController;
use app\modules\right\models\Mod;
use app\modules\right\models\MenuMod;
use app\modules\right\models\Role;
use app\modules\right\models\RoleMod;
use yii\helpers\Html;
use app\components\CommonHelper;

class ModController extends AuthController
{
    public function actionIndex() {
        $params = Yii::$app->request->get();

        $menuId = isset($params['menu_id']) ? $params['menu_id'] : 1;
        $ret = Mod::find()->where(['menu_id' => $menuId])->orderBy('id desc')->asArray()->all();
        $html = $this->buildTableHtml($ret);

        $ret = MenuMod::find()->orderBy('id desc')->asArray()->all();
        $menuHtml = $this->buildMenuTableHtml($ret);
        $menuSelectHtml = $this->getMenuHtml($ret, $menuId);

        $roleHtml = $this->getRoleHtml();
        $groupParentHtml = $this->getGroupParentHtml($menuId);
        $orderHtml = $this->getOrderHtml($menuId);
        $menuOrderHtml = $this->getMenuOrderHtml();
        // $orderSubHtml = $this->getSubOrderHtml($menuId);

        return $this->render('index',
            [
                'table' => $html,
                'menuTable' => $menuHtml,
                'menuSelectHtml' => $menuSelectHtml,
                'groupParentHtml' => $groupParentHtml,
                'roleHtml' => $roleHtml,
                'orderHtml' => $orderHtml,
                'menuOrderHtml' => $menuOrderHtml,
            ]);
    }

    /**
     * 信息
     */
    public function actionInfo() {
        $params = Yii::$app->request->get();
        $data = Mod::find()->where(['id' => $params['id']])->asArray()->one();
        echo json_encode($data);
    }

    /**
     * 主菜单添加
     */
    public function actionAddmenu() {
        $params = Yii::$app->request->post();
        if(empty($params)){
            echo '参数不能为空';exit;
        }

        $mod = new MenuMod();
        foreach($params as $key => $value){
            $mod->$key = $value;
        }

        $orderKey = 'menu_order';
        $maxMenuOrder = [];
        if (isset($params['menu_id'])) {
            $maxMenuOrder = MenuMod::find()->select('menu_order')->where(['menu_id' => $params['menu_id']])->orderBy('menu_order desc')->asArray()->one();
        }
        $menuOrderNum = empty($maxMenuOrder) ? 1 : $maxMenuOrder['menu_order'] + 1;
        $mod->$orderKey = $menuOrderNum;

        if($mod->save()){
            echo 'suc';
        }else{
            echo '重名';
        }
    }

    /**
     * 添加
     */
    public function actionAdd() {
        $params = Yii::$app->request->post();
        if(empty($params)){
             echo '参数不能为空';exit;
        }

        // 全屏特殊处理
        $menuId = $params['menu_id'];
        $menuInfo = MenuMod::find()->where(['id' => $menuId])->asArray()->one();
        $type = $menuInfo['type'];

        if ($type == 'full_screen') {
            $exsit = Mod::find()->where(['menu_id' => $menuId])->count();
            if ($exsit > 0){
                echo '一级菜单若布局为全屏，则只能存在一个子菜单';
                exit;
            }
        }


        $mod = new Mod();
        foreach($params as $key => $value){
            $mod->$key = $value;
        }

        if($params['type'] == 'node' || $params['type'] == 'group_parent'){
            $orderKey = 'menu_order';
            $maxMenuOrder = Mod::find()->select('menu_order')->where(['menu_id' => $params['menu_id']])->orderBy('menu_order desc')->asArray()->one();
            $menuOrderNum = empty($maxMenuOrder) ? 1 : $maxMenuOrder['menu_order'] + 1;
            $mod->$orderKey = $menuOrderNum;
        }

        if($mod->save()){
            echo 'suc';
        }else{
            echo '重名或格式有误';
        }
    }

    /**
     * 编辑主菜单
     */
    public function actionEditmenu() {
        $params = Yii::$app->request->post();
        if(empty($params)){
            echo '参数不能为空';exit;
        }

        $mod = MenuMod::findOne($params['id']);
        foreach($params as $key => $value){
            if($key != 'id'){
                $mod->$key = $value;
            }
        }

        if($mod->save()){
            echo 'suc';
        }else{
            echo '重名';
        }
    }

    /**
     * 编辑
     */
    public function actionEdit() {
        $params = Yii::$app->request->post();
        if(empty($params)){
             echo '参数不能为空';exit;
        }
        $mod = Mod::findOne($params['id']);
        foreach($params as $key => $value){
            if($key != 'id'){
                if ($key == 'menuOrder') {
                    $key = 'menu_order';
                }
                $mod->$key = $value;

                if ($key == 'type' && $value == 'group_node') {
                    // $mod->menu_order = 0;
                    $mod->css = '';
                }

                if ($key == 'type' && $value == 'group_parent') {
                    $mod->link = '#';
                    $mod->module = '';
                    $mod->controller = '';
                }
            }
        }

        if($mod->save()){
            if (isset($params['type']) && $params['type'] == 'group_parent') {
                $modChild = Mod::findAll(['group_parent_id' => $params['id']]);
                foreach($modChild as $child) {
                    $child->menu_id = $params['menu_id'];
                    $child->save();
                }
            }
            echo 'suc';
        }else{
            echo '重名或格式有误';
        }
    }

    /**
     * 删除主菜单
     */
    public function actionDelmenu() {
        $params = Yii::$app->request->post();
        if(empty($params)){
            echo '参数不能为空';exit;
        }

        if(MenuMod::deleteAll(['id' => $params['id']])){
            Mod::updateAll(['menu_id' => 1], "menu_id = {$params['id']}");
            echo 'suc';
        }else{
            echo '删除失败';
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

        if ($params['id'] == 1) {
            echo '默认主菜单不能删除';exit;
        }

        if(Mod::deleteAll(['id' => $params['id']])){
            RoleMod::deleteAll(['module_id' => $params['id']]);
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

        if(empty($roles)){
            echo '角色不能为空';exit;
        }
        //删除old
        RoleMod::deleteAll(['module_id' => $id]);

        try {
            $role_key = 'role_id';
            $mod_key = 'module_id';
            foreach($roles as $key => $value){
                $roleModAdd = new RoleMod();
                $roleModAdd->$role_key = $value;
                $roleModAdd->$mod_key = $id;
                $roleModAdd->save();
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
        $roles = RoleMod::find()->select('role_id')->where(['module_id' => $id])->asArray()->all();

        $ret = [];
        foreach($roles as $role){
            $ret[] = $role['role_id'];
        }

        echo json_encode($ret);
    }

    /**
     * 已有父亲节点
     */
    public function actionParent() {
        $params = Yii::$app->request->post();

        $id = $params['id'];
        $parent = Mod::find()->select('group_parent_id')->where(['id' => $id])->asArray()->one();

        if(!empty($parent)){
            echo $parent['group_parent_id'];
        }else{
            echo '';
        }
    }

    /**
     * 显示顺序
     */
    public function actionOrderme() {
        $params = Yii::$app->request->post();

        $id = $params['id'];
        $order = Mod::find()->select('menu_order')->where(['id' => $id])->asArray()->one();

        if(!empty($order)){
            echo $order['menu_order'];
        }else{
            echo '';
        }
    }

    /**
     * 二级子菜单显示顺序
     */
    public function actionSuborderhtml() {
        $params = Yii::$app->request->post();
        $id = $params['id'];
        $parent_id = $params['parentId'];

        $html = $this->getSubOrderHtml($parent_id);
        echo $html;
    }

    /**
     * 主菜单显示顺序
     */
    public function actionMenuorderme() {
        $params = Yii::$app->request->post();

        $id = $params['id'];
        $order = MenuMod::find()->select('menu_order')->where(['id' => $id])->asArray()->one();

        if(!empty($order)){
            echo $order['menu_order'];
        }else{
            echo '';
        }
    }

    /**
     * 生成主菜单表格html
     * @param array $data
     * @return string
     */
    private function buildMenuTableHtml($data){
        $html = '';
        foreach($data as $key => $value){
            $html .= '<tr>';

            foreach($value as $k => $v){
                if(empty($v)){
                    $v = '-';
                }

                if($k == 'type'){
                    $v = ($v == 'left_menu') ? '左侧菜单' : '全屏';
                }

                $html .= Html::tag('td', Html::encode($v));
            }

            // if ($value['id'] != 1) {
                $html .= Html::tag('td', "
                    <a data-id='{$value['id']}' data-val='{$value['text']}' data-type='menu' class='mod-edit btn btn-xs btn-info' href='javascript:void(0);'>修改</a>
                    <a data-id='{$value['id']}' data-val='{$value['text']}' data-type='menu' class='mod-del btn btn-xs btn-danger' href='javascript:void(0);'>删除</a>
                    <a data-id='{$value['id']}' data-val='{$value['text']}' data-type='menu' class='mod-order btn btn-xs btn-magenta' href='javascript:void(0);'>显示顺序</a>
                    ");
            // } else {
            //     $html .= Html::tag('td', "
            //         <a data-id='{$value['id']}' data-val='{$value['text']}' data-type='menu' class='mod-edit btn btn-xs btn-info' href='javascript:void(0);'>修改</a>
            //         <a data-id='{$value['id']}' data-val='{$value['text']}' data-type='menu' class='mod-order btn btn-xs btn-magenta' href='javascript:void(0);'>显示顺序</a>
            //         ");
            // }

            $html .= '</tr>';
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
        $formatData = CommonHelper::getMenuLevelFormatData($data);

        $newData = [];
        foreach($formatData as $key => $value) {
            if (!empty($value['childs'])) {
                $childs = $value['childs'];
                unset($value['childs']);
                $newData[] = $value;
                foreach($childs as $cvalue) {
                    $newData[] = $cvalue;
                }
            } else {
                $newData[] = $value;
            }
        }
        $data = $newData;
        foreach($data as $key => $value){
            if ($value['type'] == 'group_node') {
                $html .= '<tr class="text-info">';
            }else {
                $html .= '<tr class="">';
            }

            unset($value['create_time']);
            unset($value['childs']);
            $menuId = $value['menu_id'];
            unset($value['menu_id']);
            foreach($value as $k => $v){
                if(empty($v)){
                    $v = '-';
                }

                if($k == 'type'){
                    $v = Yii::t('app_right', $v);
                }

                if($k == 'group_parent_id' && $v > 0){
                    $tmp = Mod::find()->select('text')->where(['id' => $v])->asArray()->one();
                    $v = $tmp['text'];
                }
                $html .= Html::tag('td', Html::encode($v));
            }

            switch ($value['type']) {
                case 'group_parent': $html .= Html::tag('td', "
                    <a data-id='{$value['id']}' data-val='{$value['text']}' data-menu='{$menuId}' class='btn-align mod-edit btn btn-xs btn-info' href='javascript:void(0);'>修改</a>
                    <a data-id='{$value['id']}' data-val='{$value['text']}' data-menu='{$menuId}' class='btn-align mod-del btn btn-xs btn-danger' href='javascript:void(0);'>删除</a>
                    <a data-id='{$value['id']}' data-val='{$value['text']}' data-menu='{$menuId}' class='btn-align mod-order btn btn-xs btn-magenta' href='javascript:void(0);'>显示顺序</a>
                ");break;

                case 'group_node': $html .= Html::tag('td', "
                    <a data-id='{$value['id']}' data-val='{$value['text']}' data-menu='{$menuId}' class='btn-align mod-edit btn btn-xs btn-info' href='javascript:void(0);'>修改</a>
                    <a data-id='{$value['id']}' data-val='{$value['text']}' data-menu='{$menuId}' class='btn-align mod-del btn btn-xs btn-danger' href='javascript:void(0);'>删除</a>
                    <a data-id='{$value['id']}' data-val='{$value['text']}' data-menu='{$menuId}' class='btn-align mod-parent btn btn-xs btn-azure' href='javascript:void(0);'>父节点</a>
                    <a data-id='{$value['id']}' data-val='{$value['text']}' data-menu='{$menuId}' class='btn-align mod-role btn btn-xs btn-purple' href='javascript:void(0);'>角色</a>
                    <a data-id='{$value['id']}' data-val='{$value['text']}' data-menu='{$menuId}' data-sub-menu='{$value['group_parent_id']}' class='btn-align mod-sub-order btn btn-xs btn-magenta' href='javascript:void(0);'>显示顺序</a>
                ");break;

                case 'node': $html .= Html::tag('td', "
                    <a data-id='{$value['id']}' data-val='{$value['text']}' data-menu='{$menuId}' class='btn-align mod-edit btn btn-xs btn-info' href='javascript:void(0);'>修改</a>
                    <a data-id='{$value['id']}' data-val='{$value['text']}' data-menu='{$menuId}' class='btn-align mod-del btn btn-xs btn-danger' href='javascript:void(0);'>删除</a>
                    <a data-id='{$value['id']}' data-val='{$value['text']}' data-menu='{$menuId}' class='btn-align mod-role btn btn-xs btn-purple' href='javascript:void(0);'>角色</a>
                    <a data-id='{$value['id']}' data-val='{$value['text']}' data-menu='{$menuId}' class='btn-align mod-order btn btn-xs btn-magenta' href='javascript:void(0);'>显示顺序</a>
                ");break;

                default: $html .= Html::tag('td', "
                    <a data-id='{$value['id']}' data-val='{$value['text']}' data-menu='{$menuId}' class='btn-align mod-edit btn btn-xs btn-info' href='javascript:void(0);'>修改</a>
                    <a data-id='{$value['id']}' data-val='{$value['text']}' data-menu='{$menuId}' class='btn-align mod-del btn btn-xs btn-danger' href='javascript:void(0);'>删除</a>
                    <a data-id='{$value['id']}' data-val='{$value['text']}' data-menu='{$menuId}' class='btn-align mod-role btn btn-xs btn-purple' href='javascript:void(0);'>角色</a>
                ");
            };

            $html .= '</tr>';
        }
        return $html;
    }

    /**
     * 获取父节点列表
     * @param int $menuId
     * @return string
     */
    private function getGroupParentHtml($menuId){
        $condition = [];
        $condition['type'] = 'group_parent';
        $condition['menu_id'] = $menuId;
        $ret = Mod::find()->select('id,text')->where($condition)->asArray()->all();

        $html = '';
        foreach($ret as $key => $value){
            $html .= Html::tag('option', Html::encode($value['text']), ['value' => $value['id']]);
        }

        return $html;
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
     * 获取显示顺序
     * @param int $menuId
     * @return string
     */
    private function getOrderHtml($menuId){
        $num = Mod::find()->where(['type' => ['node', 'group_parent'], 'menu_id' => $menuId])->asArray()->count();

        $html = '';
        for($i = 1; $i <= $num; $i++){
            $html .= Html::tag('option', Html::encode($i), ['value' => $i]);
        }

        return $html;
    }

    /**
     * 获取二级菜单显示顺序
     * @param int $menuId
     * @return string
     */
    private function getSubOrderHtml($subMenuId){
        $num = Mod::find()->where(['type' => ['group_node'], 'group_parent_id' => $subMenuId])->asArray()->count();

        $html = '';
        for($i = 1; $i <= $num; $i++){
            $html .= Html::tag('option', Html::encode($i), ['value' => $i]);
        }

        return $html;
    }

    /**
     * 获取主菜单显示顺序
     * @return string
     */
    private function getMenuOrderHtml(){
        $num = MenuMod::find()->asArray()->count();

        $html = '';
        for($i = 1; $i <= $num; $i++){
            $html .= Html::tag('option', Html::encode($i), ['value' => $i]);
        }

        return $html;
    }

    /**
     * 获取主菜单列表
     * @param array $data
     * @param int $menuId
     * @return string
     */
    private function getMenuHtml($data, $menuId){
        $html = '';
        foreach($data as $value){
            if ($value['id'] == $menuId) {
                $html .= Html::tag('option', Html::encode($value['text']), ['value' => $value['id'], 'selected' => 'selected']);
            } else {
                $html .= Html::tag('option', Html::encode($value['text']), ['value' => $value['id']]);
            }
        }

        return $html;
    }
}
