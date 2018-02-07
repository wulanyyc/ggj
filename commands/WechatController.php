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
use app\components\PriceHelper;
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
            $exsitArr = Customer::find()->where(['openid' => $value['openid']])->asArray()->one();

            if (!empty($exsitArr)) {
                $add = Customer::findOne($exsitArr['id']);
                $add->openid = $value['openid'];
                $add->unionid = $value['unionid'];
                $add->nick = $value['nickname'];
                $add->headimgurl = $value['headimgurl'];
                $add->city = $value['city'];
                $add->sex = $value['sex'];
                $add->save();
            } else {
                $add = new Customer();
                $add->openid = $value['openid'];
                $add->unionid = $value['unionid'];
                $add->nick = $value['nickname'];
                $add->headimgurl = $value['headimgurl'];
                $add->city = $value['city'];
                $add->sex = $value['sex'];
                $add->save();

                $up = CustomerWeixin::findOne($value['id']);
                $up->customer_id = $add->id;
                $up->save();

                // TODO 关注公众号，修改优惠id
                PriceHelper::createCoupon(Yii::$app->params['coupon']['subscribe'], $value['openid']);
                // TODO 首单优惠，修改优惠id
                // PriceHelper::createCoupon(Yii::$app->params['coupon']['login'], $value['openid']);
            }
        }
    }

    public function actionCoupon() {
        $qrData = WechatHelper::getCouponqrcode(8);
        $ticket = isset($qrData['ticket']) ? $qrData['ticket'] : '';
        $url = "https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=" . $ticket;

        echo $url;
    }
}
