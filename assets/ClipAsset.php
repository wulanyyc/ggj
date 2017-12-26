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
class ClipAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
    ];
    public $js = [
        '/js/lib/clipboard.min.js'
    ];
    public $depends = [
    ];
}
