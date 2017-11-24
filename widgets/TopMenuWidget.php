<?php
namespace app\widgets;

use yii\base\Widget;
use app\modules\right\models\Mod;
use app\modules\right\models\MenuMod;
use app\components\CommonHelper;

/**
 * 菜单组件,配置请参考config/menu.php
 * @author yangyuncai
 *
 */
class TopMenuWidget extends Widget
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
        return $this->bulidMenuHtml();
    }

    /**
     * 生成菜单html
     * @return string
     */
    private function bulidMenuHtml(){
        $menus = MenuMod::find()->orderBy('menu_order asc')->asArray()->all();
        if(empty($menus)){
            return '';
        }

        $html = '';
        $htmlHelper = '';
        foreach($menus as $value){
            $url = CommonHelper::getRightLinkByMenuId($value['id']);
            $css = ($value['id'] == $this->menuId) ? 'menu-focus' : 'menu';

            if ($value['text'] == '设置') {
                continue;
            }

            if ($value['text'] == '帮助') {
                if ($url) {
                    $htmlHelper .= <<<EOF
                    <li class="menu-item">
                        <a href='{$url}' class='{$css}'>
                            {$value['text']}
                        </a>
                    </li>
EOF;
                } else {
                    $htmlHelper .= <<<EOF
                    <li class="menu-item">
                        <a href='#' disabled='true' onclick='return false;' class='menu forbid'>
                        {$value['text']}
                        </a>
                    </li>
EOF;
                }
            } else {
                if ($url) {
                    $html .= <<<EOF
                    <li class="menu-item">
                        <a href='{$url}' class='{$css}'>
                            {$value['text']}
                        </a>
                    </li>
EOF;
                } else {
                    $html .= <<<EOF
                    <li class="menu-item">
                        <a href='#' disabled='true' onclick='return false;' class='menu forbid'>
                        {$value['text']}
                        </a>
                    </li>
EOF;
                }
            }
        }

        $html .= $htmlHelper;
        return '<ul class="nav navbar-nav">'.$html.'</ul>';
    }


    /**
     * 获取主菜单id
     * @return int
     */
    private function getMenuId(){
        $data = Mod::find()->select('menu_id')->where(['module' => $this->module, 'controller' => $this->controller])->asArray()->one();
        if (empty($data)) {
            return 1;
        }else{
            return $data['menu_id'];
        }
    }
}