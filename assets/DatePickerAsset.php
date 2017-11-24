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
        'js/lib/datepicker/css/datepicker3.css',
        'js/lib/datepicker/css/daterangepicker.css',
    ];
    public $js = [
        'js/lib/datepicker/js/bootstrap-datepicker.js',
        'js/lib/datepicker/js/locales/bootstrap-datepicker.zh-CN.js',
        'js/lib/datepicker/js/moment.min.js',
        'js/lib/datepicker/js/daterangepicker.js',
    ];
    public $depends = [
        'app\assets\AppAsset',
    ];
}
