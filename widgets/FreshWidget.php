<?php
namespace app\widgets;

use yii\base\Widget;

/**
 * 面包屑
 * @author yangyuncai
 *
 */
class FreshWidget extends Widget
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
        $freshs = [100, 95, 90, 85, 80, 75, 70];

        $html = '<select name="fresh_percent" class="fresh_percent" style="min-width:60px;">';
        foreach($freshs as $fresh) {
            $html .= <<<EOF
            <option value="{$fresh}">{$fresh}</option>
EOF;
        }
        $html .= '</select>';

        return $html;
    }
}