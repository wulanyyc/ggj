<?php
namespace app\widgets;

use yii\base\Widget;
use app\modules\right\models\Mod;
use app\components\CommonHelper;

/**
 * 菜单组件,配置请参考config/menu.php
 * @author yangyuncai
 *
 */
class MenuWidget extends Widget
{
    public $module;
    public $controller;
    public $menu = [];
    public $menuId;

    /**
     * 初始化变量
     */
    public function init(){
        parent::init();
        if ($this->module === null) {
            $this->module = \Yii::$app->controller->module->id;
        }

        if ($this->controller === null) {
            $this->controller = \Yii::$app->controller->id;
        }

        $this->menuId = $this->getMenuId();
    }

    /**
     * 执行
     * @return string
     */
    public function run(){
        $config = $this->getConfig();
        if(empty($config)){
            return '';
        }

        $html = '';
        foreach($config as $key => $value){
            if(isset($value['childs'])){
                $html .= $this->buildGroupNode($value);
            }else{
                $html .= $this->buildSingleNode($value);
            }
        }
        return $html;
    }

    /**
     * 单个节点
     * @param array $config
     * @return string
     */
    private function buildSingleNode($config){
        $active_flag = $this->checkVisitPath($config);
        $active_css = $active_flag ? 'active' : '';
        if(CommonHelper::checkRights($config['id'])){
            $html = <<<EOF
                <li class='{$active_css}'>
                    <a href='{$config['link']}'>
                        <i class='menu-icon {$config['css']}'></i>
                        <span class='menu-text'> {$config['text']} </span>
                    </a>
                </li>
EOF;
        }else{
            if ($this->menuId == \Yii::$app->params['hide_no_right_menu_id']) {
                $html = '';
            } else {
                $html = <<<EOF
                <li class='{$active_css}'>
                    <a href='{$config['link']}' disabled='true' onclick='return false;' class='forbid'>
                        <i class='menu-icon {$config['css']}'></i>
                        <span class='menu-text'> {$config['text']} </span>
                    </a>
                </li>
EOF;
            }
        }

        return $html;
    }

    /**
     * 组节点
     * @param array $config
     * @return string
     */
    private function buildGroupNode($config){
        $group_active_flag = $this->checkVisitGroup($config['childs']);
        $group_active_css = $group_active_flag ? 'open' : '';
        $childHtml = $this->buildGroupChildNode($config['childs']);
        $html = <<<EOF
            <li class='{$group_active_css}'>
                <a href='#' class='menu-dropdown'>
                    <i class='menu-icon {$config['css']}'></i>
                    <span class='menu-text'> {$config['text']} </span>
                    <i class='menu-expand'></i>
                </a>

                <ul class='submenu'>
EOF;
        if (!empty($childHtml)) {
            $html .= $childHtml;
            $html .='</ul></li>';
        } else {
            $html = '';
        }

        return $html;
    }

    /**
     * 组内子节点
     * @param array $config
     * @return string
     */
    private function buildGroupChildNode($config){
        $html = '';
        foreach($config as $key => $value){
            $active_flag = $this->checkVisitPath($value);
            $active_css = $active_flag ? 'active' : '';

            if(CommonHelper::checkRights($value['id'])){
                $html .= <<<EOF
                <li class='{$active_css}'>
                    <a href='{$value['link']}'>
                        <span class='menu-text'>{$value['text']}</span>
                    </a>
                </li>
EOF;
            }else{
                if ($this->menuId == \Yii::$app->params['hide_no_right_menu_id']) {
                    $html .= '';
                } else {
                    $html .= <<<EOF
                    <li class='{$active_css}'>
                        <a href='{$value['link']}' disabled='true' onclick='return false;' class='forbid'>
                            <span class='menu-text'>{$value['text']}</span>
                        </a>
                    </li>
EOF;
                }
            }

        }
        return $html;
    }

    /**
     * 是否访问该组
     * @param array $config
     * @return boolean
     */
    private function checkVisitGroup($config){
        $flag = false;
        foreach($config as $key => $value){
            $flag = $this->checkVisitPath($value);
            if($flag){
                return $flag;
            }
        }
        return $flag;
    }

    /**
     * 是否访问该路径
     * @param array $config
     * @return boolean
     */
    private function checkVisitPath($config){
        if($config['module'] != $this->module){
            return false;
        }

        if($config['controller'] != $this->controller){
            return false;
        }

        return true;
    }

    /**
     * 获取模块配置
     * @return array
     */
    private function getConfig(){
        $nodes = [];
        $groups = [];
        $orderMenus = [];
        $orderFormatMenus = [];

        $menus = Mod::find()->select('id,text,type,link,css,module,controller,group_parent_id,menu_order')
                            ->where(['type' => ['node', 'group_parent', 'group_node'], 'menu_id' => $this->menuId])
                            ->asArray()->all();

        //菜单项分类
        foreach($menus as $key => $value){
            if($value['type'] == 'node'){
                $nodes[] = $value;
                unset($menus[$key]);
            }

            if($value['type'] == 'group_parent'){
                $groups[$value['id']] = $value;
                $groups[$value['id']]['childs'] = [];
                unset($menus[$key]);
            }

            if($value['type'] == 'group_node'){
                if(isset($groups[$value['group_parent_id']])){
                    array_push($groups[$value['group_parent_id']]['childs'] , $value);
                    unset($menus[$key]);
                }
            }
        }

        // 遗漏菜单组处理
        if(!empty($menus)){
            foreach($menus as $key => $value){
                if($value['type'] == 'group_node'){
                    if(isset($groups[$value['group_parent_id']])){
                        array_push($groups[$value['group_parent_id']]['childs'] , $value);
                        unset($menus[$key]);
                    }
                }
            }
        }

        // 组内排序
        if(!empty($groups)){
            foreach($groups as $key => $value){
                if(isset($value['childs'])) {
                    $childs = $value['childs'];
                    $newChildsConfig = [];
                    $newChildsNoConfig = [];
                    foreach($childs as $ckey => $cvalue) {
                        if ($cvalue['menu_order'] > 0) {
                            $newChildsConfig[$cvalue['menu_order']][] = $cvalue;
                        } else {
                            $newChildsNoConfig[] = $cvalue;
                        }
                    }
                    ksort($newChildsConfig);
                    $newChildsConfigFormat = [];
                    foreach($newChildsConfig as $item) {
                        foreach($item as $itemValue){
                            $newChildsConfigFormat[] = $itemValue;
                        }
                    }
                    $newChild = array_merge($newChildsConfigFormat, $newChildsNoConfig);
                    $groups[$key]['childs'] = $newChild;
                }
            }
        }

        //菜单项排序
        if(!empty($nodes)){
            foreach($nodes as $key => $value){
                if($value['menu_order'] > 0){
                    $orderMenus[$value['menu_order']][] = $value;
                    unset($nodes[$key]);
                }
            }
        }

        // menu_order > 0
        if(!empty($groups)){
            foreach($groups as $key => $value){
                if($value['menu_order'] > 0){
                    $orderMenus[$value['menu_order']][] = $value;
                    unset($groups[$key]);
                }
            }
        }

        if(!empty($orderMenus)){
            ksort($orderMenus, SORT_NUMERIC);
            foreach($orderMenus as $key => $value){
                foreach($value as $k => $v){
                    $orderFormatMenus[] = $v;
                }
            }
        }

        // menu_order不存在
        if(!empty($nodes)){
            foreach($nodes as $key => $value){
                array_push($orderFormatMenus, $value);
                unset($nodes[$key]);
            }
        }

        if(!empty($groups)){
            foreach($groups as $key => $value){
                array_push($orderFormatMenus, $value);
                unset($groups[$key]);
            }
        }

        return $orderFormatMenus;
    }

    /**
     * 获取主菜单id
     * @return int
     */
    private function getMenuId() {
        $data = Mod::find()->select('menu_id')->where(['module' => $this->module, 'controller' => $this->controller])->asArray()->one();
        if (empty($data)) {
            return 1;
        }else{
            return $data['menu_id'];
        }
    }

    private function getUserRole() {
        $uid = Yii::$app->session['uid'];

    }
}
