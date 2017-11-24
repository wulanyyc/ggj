<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/base/beyond.min.css',
        'css/skins/green.min.css',
        'css/lib/font-awesome/css/font-awesome.css',
        'css/base/animate.min.css',
        'css/common.css',
    ];
    public $js = [
        'js/base/jquery.slimscroll.min.js',
        'js/lib/jquery-cookie/jquery.cookie.js',
        'js/base/beyond.js',
    ];
    public $depends = [
        'app\assets\BaseAsset',
    ];
}
