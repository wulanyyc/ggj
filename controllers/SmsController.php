<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\components\SiteHelper;
use app\components\SmsHelper;

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
            echo '提交的数据为空';
            Yii::$app->end();
        }

        $phone = isset($params['phone']) ? $params['phone'] : 0;

        if (SiteHelper::checkPhone($phone)) {
            $code = rand(1000, 9999);
            Yii::$app->redis->setex($phone . "_code", 120, $code);

            SmsHelper::sendVcode($phone, ['code' => $code]);
            //TODO send code
            echo 1;
        } else {
            echo '号码认证失败';
        }

        Yii::$app->end();
    }

    public function actionVcode() {
        $params = Yii::$app->request->post();
        if (empty($params)) {
            echo json_encode(['status' => 'fail', 'msg' => '提交的数据为空']);
            Yii::$app->end();
        }

        if (empty($params['phone']) || empty($params['code'])) {
            echo json_encode(['status' => 'fail', 'msg' => '提交的数据不全']);
            Yii::$app->end();
        }

        $realCode = Yii::$app->redis->get($params['phone'] . "_code");

        if ($realCode != $params['code']) {
            echo json_encode(['status' => 'fail', 'msg' => '验证失败']);
        } else {
            SiteHelper::addCustomer($params['phone']);
            echo json_encode(['status' => 'ok', 'secret' => SiteHelper::buildSecret($params['phone'])]);
        }

        Yii::$app->end();
    }
}
