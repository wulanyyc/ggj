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
class ErrorAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'js/base/bootstrap/css/bootstrap.min.css',
        'css/base/beyond.min.css',
        'css/skins/blue.min.css',
    ];
    public $js = [
        'js/error/jquery-1.11.2.min.js'
    ];
    public $depends = [
    ];
}
