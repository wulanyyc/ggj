<?php
namespace app\widgets;

use yii\base\Widget;

/**
 * 面包屑
 * @author yangyuncai
 *
 */
class BreadCrumbWidget extends Widget
{
    public $text;
    
    /**
     * 初始化
     */
    public function init(){
        parent::init();
        if ($this->text === null) {
            $this->text = 'dashboard';
        }
    }
    
    /**
     * 执行
     */
    public function run(){
        $html = <<<EOF
            <ul class="breadcrumb">
                <li>
                    <i class="fa fa-home"></i>
                    <a href="/">首页</a>
                </li>
                <li class="active">{$this->text}</li>
            </ul>
EOF;
        return $html;
    }
}