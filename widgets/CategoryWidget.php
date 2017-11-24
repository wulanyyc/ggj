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
        $categorys = ['水果','套餐','干果','工具'];

        $html = '<select name="category" class="category" style="min-width:60px;">';
        foreach($categorys as $category) {
            $html .= <<<EOF
            <option value="{$category}">{$category}</option>
EOF;
        }
        $html .= '</select>';

        return $html;
    }
}