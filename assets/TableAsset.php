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
class TableAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
       'css/lib/table/style.css'
    ];
    public $js = [
    ];
    public $depends = [
        'app\assets\OverlayAsset',
        'app\assets\AppAsset',
    ];
}
