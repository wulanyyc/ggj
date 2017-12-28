<?php
// comment out the following two lines when deployed to production
defined('YII_DEBUG') || define('YII_DEBUG', true);
// defined('YII_ENV') || define('YII_ENV', 'dev');

// defined('NO_AUTH_RIGHTS') || define('NO_AUTH_RIGHTS', true);

if (YII_DEBUG) {
    defined('CACHE_PREFIX') || define('CACHE_PREFIX', 'ggj_dev');
} else {
    defined('CACHE_PREFIX') || define('CACHE_PREFIX', 'ggj_prod');
}

require(__DIR__ . '/../../yii2/vendor/autoload.php');
require(__DIR__ . '/../../yii2/vendor/aliyunsms/api_sdk/vendor/autoload.php');
require(__DIR__ . '/../../yii2/vendor/alipaysdk/AopSdk.php');
require(__DIR__ . '/../../yii2/vendor/yiisoft/yii2/Yii.php');


$config = require(__DIR__ . '/../config/web.php');
(new yii\web\Application($config))->run();

