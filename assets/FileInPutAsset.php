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
class FileInPutAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'js/lib/fileinput/css/fileinput.css',
    ];
    public $js = [
        'js/lib/fileinput/js/fileinput.min.js',
        'js/lib/fileinput/js/locales/zh.js',
        'js/lib/fileinput/js/locales/es.js',
        'js/lib/fileinput/js/plugins/canvas-to-blob.min.js',
        'js/lib/fileinput/js/plugins/purify.min.js',
        'js/lib/fileinput/js/plugins/sortable.min.js',
        // 'js/lib/fileinput/themes/fa/theme.js',
    ];
    public $depends = [
        'app\assets\AppAsset',
    ];
}
