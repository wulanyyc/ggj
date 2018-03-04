<?php
namespace app\widgets;

use yii\base\Widget;

/**
 * 面包屑
 * @author yangyuncai
 *
 */
class UnitWidget extends Widget
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
        $units = ['个','斤','盒', '份', '把', '周', '袋', '箱'];

        $html = '<select name="unit" class="unit" style="min-width:60px;">';
        foreach($units as $unit) {
            $html .= <<<EOF
            <option value="{$unit}">{$unit}</option>
EOF;
        }
        $html .= '</select>';

        return $html;
    }
}