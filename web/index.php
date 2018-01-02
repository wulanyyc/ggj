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


// wechat
require(__DIR__ . '/../lib/sha1.php');
require(__DIR__ . '/../lib/xmlparse.php');
require(__DIR__ . '/../lib/wxBizMsgCrypt.php');
require(__DIR__ . '/../lib/pkcs7Encoder.php');
require(__DIR__ . '/../lib/errorCode.php');

require(__DIR__ . '/../../yii2/vendor/autoload.php');
require(__DIR__ . '/../../yii2/vendor/aliyunsms/api_sdk/vendor/autoload.php');
require(__DIR__ . '/../../yii2/vendor/alipaysdk/AopSdk.php');
require(__DIR__ . '/../../yii2/vendor/yiisoft/yii2/Yii.php');


$config = require(__DIR__ . '/../config/web.php');
(new yii\web\Application($config))->run();

