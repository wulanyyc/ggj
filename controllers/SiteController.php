<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\modules\product\models\ProductList;
use app\modules\product\models\ProductTags;
use app\modules\product\models\Tags;
use app\components\PriceHelper;

class SiteController extends Controller
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
        return $this->render('index', [
            'controller' => Yii::$app->controller->id,
            'dayPromotionText' => $this->getDayPromotion(),
            'tags' => $this->getTags(),
            'products' => $this->getProducts(),
            'newPromotionText' => $this->getNewPromotion(),
        ]);
    }

    public function actionLayout() {
        $params = Yii::$app->request->post();
        print_r($params);
        // $width = $params['width'];
        // if ($width <= 991) {
        //     Yii::$app->params['layout'] = 'page';
        // } else {
        //     Yii::$app->params['layout'] = 'site';
        // }

        // echo Yii::$app->params['layout'];
    }

    private function getNewPromotion() {
        $promotions = Yii::$app->params['new_promotion'];

        $info = ProductList::find()->select('name,price,unit')->where(['id' => $promotions['id']])->asArray()->one();

        $price = PriceHelper::getProductPrice($promotions['id'], $info['price']);
        $text = $info['name'] . ' ' . $price . '元/' . $info['unit'];
        return $text;
    }

    private function getDayPromotion() {
        $cn = Yii::$app->params['day_cn'];
        $dayofweek = date('w', time());
        if ($dayofweek == 0) {
            $dayofweek = 7;
        }

        $promotions = Yii::$app->params['day_promotion'][$dayofweek];
        $info = ProductList::find()->select('name')->where(['id' => $promotions['id']])->asArray()->one();

        $text = '星期' . $cn[$dayofweek] . ' ' . $info['name'] . ' 特价';
        return $text;
    }

    private function getTags() {
        $info = Tags::find()->select('name,en_name')->asArray()->all();
        return $info;
    }

    private function getProducts() {
        $info = ProductList::find()->select('id,name,price,desc,slogan,link,img,unit')->where(['category' => '水果'])->asArray()->all();

        foreach($info as $key => $value) {
            $tagArr = [];
            $tags = ProductTags::find()->select('tag_id')->where(['product_id' => $value['id']])->asArray()->all();
            if (!empty($tags)) {
                foreach($tags as $tag) {
                    $tagInfo = Tags::find()->select('en_name')->where(['id' => $tag['tag_id']])->asArray()->one();
                    if (!empty($tagInfo)) {
                        $tagArr[] = $tagInfo['en_name'];
                    }
                }
            }
            $info[$key]['tag'] = implode(' ', $tagArr);

            $info[$key]['promotion_price'] = PriceHelper::getProductPrice($value['id'], $value['price']);
            if (empty($value['img'])) {
                $info[$key]['img'] = 'http://img.yis188.com/images/company/room.jpeg';
            }
        }

        return $info;
    }
}
