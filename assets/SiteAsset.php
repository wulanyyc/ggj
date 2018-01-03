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
class SiteAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'js/base/bootstrap4/css/bootstrap.min.css',
        'css/lib/font-awesome/css/font-awesome.min.css',
        'css/responsive.css',
    ];
    public $js = [
        'js/base/jquery.min.js',
        'js/base/bootstrap4/js/bootstrap.min.js',
        'js/base/bootstrap4/js/popper.min.js',
        'js/lib/jquery-cookie/jquery.cookie.js',
        'js/lib/bootbox/bootbox.min.js',
        'js/base/yii.js',
        'js/common.js',
    ];
    public $depends = [

    ];
}
