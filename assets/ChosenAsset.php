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
class ChosenAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'js/lib/chosen/chosen.min.css',
    ];
    public $js = [
        'js/lib/chosen/chosen.jquery.min.js',
        'js/lib/chosen/init.js',
    ];
    public $depends = [
        'app\assets\BaseAsset',
    ];
}
