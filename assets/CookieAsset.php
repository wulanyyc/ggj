<?php
namespace app\assets;

use yii\web\AssetBundle;

class CookieAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
    ];
    public $js = [
        'js/lib/jquery-cookie/jquery.cookie.js',
    ];
    public $depends = [
        'app\assets\BaseAsset',
    ];
}