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
use app\models\CustomerWeixin;
use app\models\Customer;


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
    public function actionAccesstoken()
    {
        WechatHelper::getAccessToken();
    }

    public function actionMenu()
    {
        $accessToken = WechatHelper::getAccessToken();
        $url = WechatHelper::$api . "/cgi-bin/menu/create?access_token=" . $accessToken;
        $ret = WechatHelper::curlRequest($url, json_encode(Yii::$app->params['wechat_menu'], JSON_UNESCAPED_UNICODE));

        var_dump($ret);
    }

    public function actionMaterial()
    {
        $accessToken = WechatHelper::getAccessToken();
        $url = WechatHelper::$api . "/cgi-bin/material/batchget_material?access_token=" . $accessToken;
        $params = [
            'type' => 'news',
            'offset' => 0,
            'count' => 10,
        ];

        $ret = WechatHelper::curlRequest($url, http_build_query($params));

        var_dump($ret);
    }

    public function actionSync() {
        $arr = CustomerWeixin::find()->asArray()->all();
        foreach($arr as $key => $value) {
            if ($value['customer_id'] > 0) {
                $up = Customer::findOne($value['customer_id']);
                $up->openid = $value['openid'];
                $up->unionid = $value['unionid'];
                $up->save();
            } else {
                $add = new Customer();
                $up->openid = $value['openid'];
                $up->unionid = $value['unionid'];
                $up->nick = $value['nickname'];
                $up->headimgurl = $value['headimgurl'];
                $up->city = $value['city'];
                $up->sex = $value['sex'];
                $up->save();
            }
        }
    }
}
