<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\components\CalculateHelper;
use app\models\DataCourier;
use app\modules\right\models\User;

class HomeController extends Controller
{
    private $isVisit = false;
    public $layout = 'full';

    private $configKeys = [
        'current-skin',
    ];

    /**
     * 入口
     * @return
     */
    public function actionIndex() {
        return $this->render('index', [
            'date' => date('Y-m-d', strtotime('-1 day'))
        ]);
    }

    /**
     * 用户配置
     * @return
     */
    public function actionConfig() {
        $params = Yii::$app->request->post();
        $sets = [];
        $uid = Yii::$app->session['uid'];
        $userInfo = User::find()->where(['id' => $uid])->one();
        $userConfig = json_decode($userInfo->user_config, true);
        if (!empty($userConfig)) {
            $sets = $userConfig;
        }
        if (!empty($params) && !empty($userInfo)) {
            foreach ($params as $k => $v) {
                if (in_array($k, $this->configKeys)) {
                    $sets[$k] = $v;
                }
            }
        }
        $userInfo->user_config = json_encode($sets);
        if ($userInfo->save()) {
            return json_encode([
                'code' => 200,
            ]);
        } else {
            return json_encode([
                'code' => 400,
            ]);
        }
    }
}
