<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\components\SiteHelper;
use app\modules\product\models\ProductList;
use app\modules\product\models\ProductTags;
use app\models\ProductPackage;
use app\modules\product\models\Tags;
use app\components\PriceHelper;

class SiteController extends Controller
{
    public $layout = 'pc';

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
            'dayPromotion' => $this->getDayPromotion(),
            'tags' => $this->getTags(),
            'fruits' => $this->getFruits(),
            'packages' => $this->getPackages(),
            'newPromotion' => $this->getNewPromotion(),
            'bookingDiscount' => Yii::$app->params['bookingDiscount'] * 10,
        ]);
    }

    private function getNewPromotion() {
        $promotions = Yii::$app->params['new_promotion'];

        $info = ProductList::find()->select('name,price,unit')->where(['id' => $promotions['id']])->asArray()->one();

        $price = PriceHelper::getProductPrice($promotions['id']);
        $text = $info['name'] . ' ' . $price . '元/' . $info['unit'];

        return ['text' => $text, 'img' => Yii::$app->params['new_promotion']['img']];
    }

    private function getDayPromotion() {
        $cn = Yii::$app->params['day_cn'];
        $dayofweek = date('w', time());
        if ($dayofweek == 0) {
            $dayofweek = 7;
        }

        $promotions = Yii::$app->params['day_promotion'][$dayofweek];
        $info = ProductList::find()->select('id,name,price,img')
            ->where(['id' => $promotions['id']])->asArray()->one();

        $text = '星期' . $cn[$dayofweek] . ' ' . $info['name'];
        return ['text' => $text, 'img' => $info['img'], 'id' => $info['id']];
    }

    private function getTags() {
        $idArr = ProductList::find()->select('id')->where(['status' => 1, 'category' => ['fruit', 'nut']])->asArray()->all();

        $ids = [];
        foreach($idArr as $item) {
            $ids[] = $item['id'];
        }

        $tagIds = ProductTags::find()->select('tag_id')->where(['product_id' => $ids])->distinct(true)->asArray()->All();

        $tags = [];
        foreach($tagIds as $item) {
            $tags[] = $item['tag_id'];
        }

        $info = Tags::find()->select('name,en_name')->where(['id' => $tags])->asArray()->all();
        return $info;
    }

    private function getFruits() {
        $info = ProductList::find()->select('id,name,price,desc,slogan,img,unit,num')->where(['category' => 'fruit', 'status' => 1])->asArray()->all();

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

            $info[$key]['promotion_price'] = PriceHelper::getProductPrice($value['id']);
            if (empty($value['img'])) {
                $info[$key]['img'] = '/img/apple_4x3.png';
            }
        }

        return $info;
    }

    private function getPackages() {
        $info = ProductList::find()->select('id,name,price,desc,slogan,img,unit,num')->where(['category' => 'package', 'status' => 1])->asArray()->all();

        foreach($info as $key => $value) {
            $info[$key]['promotion_price'] = PriceHelper::getProductPrice($value['id']);
            if (empty($value['img'])) {
                $info[$key]['img'] = '/img/apple_4x3.png';
            }

            $info[$key]['index'] = $key % 3 + 1;
            $list = ProductPackage::find()->select('product_id, num')->where(['product_package_id' => $value['id']])
                ->orderBy('num desc')
                ->asArray()->all();

            $info[$key]['list'] = [];
            foreach($list as $item) {
                $tmp = ProductList::find()->select('id,name,price,desc,slogan,img,unit')->where(['id' => $item['product_id']])->asArray()->one();

                if (!empty($tmp)) {
                    $tmp['num'] = $item['num'];
                    $info[$key]['list'][] = $tmp;
                }
            }
        }

        return $info;
    }
}
