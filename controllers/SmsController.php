<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\components\SiteHelper;
use app\components\SmsHelper;
use app\components\PriceHelper;
use app\models\CustomerWeixin;
use app\models\Customer;
use app\modules\product\models\CouponUse;

class SmsController extends Controller
{
    public $layout = 'site';

    private $configKeys = [
        'current-skin',
    ];

    /**
     * 入口
     * @return
     */
    public function actionIndex() {
        echo '1111';
    }
    
    public function actionGetcode() {
        $params = Yii::$app->request->post();
        if (empty($params)) {
            SiteHelper::renderText('提交的数据为空');
        }

        $phone = isset($params['phone']) ? $params['phone'] : 0;

        if (SiteHelper::checkPhone($phone)) {
            $code = rand(1000, 9999);
            Yii::$app->redis->setex($phone . "_code", 120, $code);

            SmsHelper::sendVcode($phone, ['code' => $code]);

            SiteHelper::renderText(1);
        } else {
            SiteHelper::renderText('号码认证失败');
        }
    }

    public function actionVcode() {
        $params = Yii::$app->request->post();
        if (empty($params)) {
            SiteHelper::render('fail', '提交的数据为空');
        }

        if (empty($params['phone']) || empty($params['code'])) {
            SiteHelper::render('fail', '提交的数据不全');
        }

        $realCode = Yii::$app->redis->get($params['phone'] . "_code");

        if ($realCode != $params['code']) {
            SiteHelper::render('fail', '验证码有误');
        } else {
            $id = SiteHelper::addCustomer($params['phone']);

            // coupon connect
            CouponUse::updateAll(['customer_id' => $id]);

            // add customer_weixin
            if (!empty($_COOKIE['openid'])) {
                $exsitData = CustomerWeixin::find()->where(['openid' => $_COOKIE['openid']])->asArray()->one();
                if (count($exsitData) > 0) {
                    $up = CustomerWeixin::findOne($exsitData['id']);
                    $up->customer_id = $exsitData['id'];
                    $up->save();

                    $upCus = Customer::findOne($id);
                    $upCus->nick = $exsitData['nickname'];
                    $upCus->headimgurl = $exsitData['headimgurl'];
                    $upCus->save();
                } else {
                    $ar = new CustomerWeixin();
                    $ar->openid = $_COOKIE['openid'];
                    $ar->customer_id = $id;
                    $ar->save();
                }
            }

            SiteHelper::render('ok', [
                'secret' => SiteHelper::buildSecret($params['phone']), 
                'cid' => $id,
            ]);
        }
    }

    // only for change phone
    public function actionInfovcode() {
        $params = Yii::$app->request->post();
        if (empty($params)) {
            SiteHelper::render('fail', '提交的数据为空');
        }

        if (empty($params['phone']) || empty($params['code'])) {
            SiteHelper::render('fail', '提交的数据不全');
        }

        $realCode = Yii::$app->redis->get($params['phone'] . "_code");

        if ($realCode != $params['code']) {
            SiteHelper::render('fail', '验证码有误');
        } else {
            SiteHelper::render('ok');
        }
    }
}
