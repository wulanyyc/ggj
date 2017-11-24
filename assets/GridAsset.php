<?php
/**
 * 带排序功能的表格
 * @author yangyuncai
 *
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class GridAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
           'js/lib/table/css/jquery.dataTables.css',
           'js/lib/table/css/dataTables.bootstrap.css',
           'js/lib/table/css/common.css',
    ];
    public $js = [
            'js/lib/table/js/jquery.dataTables.min.js',
            'js/lib/table/js/dataTables.bootstrap.min.js',
            'js/lib/table/js/common.js',
    ];
    public $depends = [
        'app\assets\OverlayAsset',
        'app\assets\AppAsset',
    ];
}
