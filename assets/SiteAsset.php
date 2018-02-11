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
        'http://css.guoguojia.vip/js/base/bootstrap4/css/bootstrap.min.css',
        '/css/lib/font-awesome/css/font-awesome.min.css',
        '/css/responsive.css?v=117',
    ];
    public $js = [
        'http://js.guoguojia.vip/js/base/jquery.min.js',
        'http://js.guoguojia.vip/js/base/bootstrap4/js/popper.min.js',
        'http://js.guoguojia.vip/js/base/bootstrap4/js/bootstrap.min.js',
        'http://js.guoguojia.vip/js/lib/jquery-cookie/jquery.cookie.js',
        'http://js.guoguojia.vip/js/lib/bootbox/bootbox.min.js',
        'http://js.guoguojia.vip/js/base/yii.js',
        '/js/common.js?v=9',
    ];
    public $depends = [

    ];
}
