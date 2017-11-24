<?php
namespace app\assets;

use yii\web\AssetBundle;


class PopoverAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
    ];
    public $js = [
        'js/lib/popover/popover.js',
    ];
    public $depends = [
        'app\assets\BaseAsset',
    ];
}