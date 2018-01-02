<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use Yii;
use yii\console\Controller;
use app\components\WechatHelper;


/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class WechatController extends Controller
{
    /**
     * 导旧表数据
     */
    public function actionGetaccesstoken()
    {
        WechatHelper::getAccessToken();
    }

    public function actionUpdatemenu()
    {
        $accessToken = WechatHelper::getAccessToken();
        $url = WechatHelper::$api . "/menu/create?access_token=" . $accessToken;
        $ret = WechatHelper::curlRequest($url, json_encode(Yii::$app->params['wechat_menu'], JSON_UNESCAPED_UNICODE));

        var_dump($ret);
    }
}
