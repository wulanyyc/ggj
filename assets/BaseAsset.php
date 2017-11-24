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
class BaseAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'js/base/bootstrap/css/bootstrap.min.css',
    ];
    public $js = [
        'js/base/jquery.min.js',
        'js/base/yii.js',
        'js/base/bootstrap/js/bootstrap.min.js',
    ];
    public $depends = [
        
    ];
}
