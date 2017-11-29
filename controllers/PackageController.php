<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\components\SiteHelper;
use app\modules\product\models\ProductList;
use app\components\PriceHelper;
use app\models\ProductOrder;

class PackageController extends Controller
{
    public $layout = 'page';
    public $bookingDiscount = 0.9;

    private $configKeys = [
        'current-skin',
    ];

    public function init() {
        $this->layout = SiteHelper::getLayout();
    }

    /**
     * 入口
     * @return
     */
    public function actionIndex() {
        return $this->render('index', [
            'controller' => Yii::$app->controller->id,
        ]);
    }

    public function actionOrder() {
        return $this->render('order', [
            'controller' => Yii::$app->controller->id,
            'fruits' => $this->getFruits(),
            'packages' => $this->getPackages(),
            'tools' => $this->getTools(),
        ]);
    }

    public function actionPay() {
        $params = Yii::$app->request->post();
        if(empty($params)){
            echo '参数不能为空';
            exit;
        }

        unset($params['code']);
        $po = new ProductOrder();
        foreach($params as $key => $value){
            $po->$key = $value;
        }

        $po->$key = $value;

        if($po->save()){
            echo $po->id;
        }else{
            echo '参数有误';
        }
    }

    private function getFruits() {
        $info = ProductList::find()->select('id,name,price,desc,slogan,link,img,unit,category')->where(['category' => '水果'])->asArray()->all();

        foreach($info as $key => $value) {
            $info[$key]['promotion_price'] = PriceHelper::getProductPrice($value['id'], $value['price']) * 0.9;
            if (empty($value['img'])) {
                $info[$key]['img'] = 'http://img.yis188.com/images/company/room.jpeg';
            }
        }

        return $info;
    }

    private function getPackages() {
        $info = ProductList::find()->select('id,name,price,desc,slogan,link,img,unit,category')->where(['category' => '套餐'])->asArray()->all();

        foreach($info as $key => $value) {
            $info[$key]['promotion_price'] = PriceHelper::getProductPrice($value['id'], $value['price']) * 0.9;
            if (empty($value['img'])) {
                $info[$key]['img'] = 'http://img.yis188.com/images/company/room.jpeg';
            }
        }

        return $info;
    }

    private function getTools() {
        $info = ProductList::find()->select('id,name,price,desc,slogan,link,img,unit,category')->where(['category' => '工具'])->asArray()->all();

        foreach($info as $key => $value) {
            $info[$key]['promotion_price'] = PriceHelper::getProductPrice($value['id'], $value['price']) * 0.9;
            if (empty($value['img'])) {
                $info[$key]['img'] = 'http://img.yis188.com/images/company/room.jpeg';
            }
        }

        return $info;
    }
}
