<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\modules\product\models\ProductList;
use app\components\SiteHelper;
use app\filters\WechatFilter;


class PromotionController extends Controller
{
    public $layout = 'wap';

    private $configKeys = [
        'current-skin',
    ];

    public function init() {
        $this->layout = SiteHelper::getLayout();
    }

    public function behaviors() {
        return [
            'wechat' => [
                'class' => WechatFilter::className(),
            ]
        ];
    }

    /**
     * 入口
     * @return
     */
    public function actionIndex() {
        return $this->render('index', [
            'controller' => Yii::$app->controller->id,
            'data' => $this->getSpecialData(),
        ]);
    }

    private function getSpecialData() {
        $data = Yii::$app->params['day_promotion'];
        $cn   = Yii::$app->params['day_cn'];

        $ret = [];
        foreach($data as $key => $value) {
            $info = ProductList::find()->select('id,name,link,img')->where(['id' => $value['id']])->asArray()->one();
            $info['day'] = $key;
            $info['day_cn'] = $cn[$key];
            $ret[] = $info;
        }

        return $ret;
    }
}
