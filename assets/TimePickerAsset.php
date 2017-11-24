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
class TimePickerAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'js/lib/jquery-timepicker/jquery.timepicker.css',
    ];
    public $js = [
        'js/lib/jquery-timepicker/jquery.timepicker.min.js',
    ];
    public $depends = [
        'app\assets\AppAsset',
    ];
}
