<?php
/**
 * 用于model,prompt,alert
 * http://bootboxjs.com/
 * @author yangyuncai
 *
 */

namespace app\assets;

use yii\web\AssetBundle;

class BootBoxAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
    ];
    public $js = [
        'js/lib/bootbox/bootbox.min.js',
    ];
    public $depends = [
        'app\assets\AppAsset',
    ];
}
