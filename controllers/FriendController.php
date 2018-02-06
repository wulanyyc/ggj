<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\components\SiteHelper;
use app\modules\product\models\ProductList;
use app\components\PriceHelper;
use app\models\ProductOrder;
use app\filters\WechatFilter;


class FriendController extends Controller
{
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
        $params = Yii::$app->request->get();
        $id = isset($params['v']) ? $params['v'] : 0;

        $data = ProductOrder::find()->where(['id' => $id])->asArray()->one();
        
        if (empty($data)) {
            return $this->render('nofound', [
                'controller' => Yii::$app->controller->id,
                'id' => $id,
            ]);
        } else {
            $phone = SiteHelper::getCustomerPhone($data['customer_id']);
            $data['userphone'] = SiteHelper::encrpytPhone($phone);
            return $this->render('index', [
                'controller' => Yii::$app->controller->id,
                'id' => $id,
                'data' => $data,
            ]);
        }
    }
}
