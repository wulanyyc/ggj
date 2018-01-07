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
class DatePickerAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'js/lib/datepicker/css/bootstrap-datepicker3.min.css',
    ];
    public $js = [
        'js/lib/datepicker/js/bootstrap-datepicker.min.js',
        'js/lib/datepicker/locales/bootstrap-datepicker.zh-CN.min.js',
    ];
    public $depends = [
        'app\assets\AppAsset',
    ];
}
