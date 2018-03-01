<?php
namespace app\widgets;

use yii\base\Widget;

/**
 * 面包屑
 * @author yangyuncai
 *
 */
class CategoryWidget extends Widget
{
    public static $categorys = ['fruit' => '水果', 'nut' => '干果'];

    /**
     * 初始化
     */
    public function init(){
        parent::init();
    }
    
    /**
     * 执行
     */
    public function run(){
        $html = '<select name="category" class="category" style="min-width:60px;">';
        foreach(self::$categorys as $key => $category) {
            $html .= <<<EOF
            <option value="{$key}">{$category}</option>
EOF;
        }
        $html .= '</select>';

        return $html;
    }

    public static function getCn($category) {
        return self::$categorys[$category];
    }
}