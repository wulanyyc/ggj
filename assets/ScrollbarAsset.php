<?php
/**
 * 普通表格
 * @author yangyuncai
 *
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class ScrollbarAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'js/lib/scrollbar/jquery.mCustomScrollbar.min.css',
    ];
    public $js = [
        'js/lib/scrollbar/jquery.mCustomScrollbar.concat.min.js',
    ];
    public $depends = [
        'app\assets\AppAsset',
    ];
}
