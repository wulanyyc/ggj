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
use app\components\ProductHelper;
use app\models\ProductCart;

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
        $cid = isset($_COOKIE['cart_id']) ? $_COOKIE['cart_id'] : 0;

        $cartNum = 0;
        $cartLink = '/buy';

        if ($cid > 0) {
            $cartInfo = ProductCart::find()->select('order_type,cart')->where(['id' => $cid])->asArray()->one();
            $cartNum = count(json_decode($cartInfo['cart'], true));
            if ($cartInfo['order_type'] == 1) {
                $cartLink = '/buy?cid=' . $cid;
            } else {
                $cartLink = '/buy/booking?cid=' . $cid;
            }
        }

        return $this->render('index', [
            'controller' => Yii::$app->controller->id,
            'dayPromotion' => $this->getDayPromotion(),
            'tags' => $this->getTags(),
            'fruits' => $this->getFruits(),
            'packages' => $this->getPackages(),
            'newPromotion' => $this->getNewPromotion(),
            'homeTip' => Yii::$app->params['hometip'],
            'bookingDiscount' => Yii::$app->params['bookingDiscount'] * 10,
            'bookingSender' => Yii::$app->params['bookingSender'],
            'cartNum' => $cartNum,
            'cartLink' => $cartLink,
        ]);
    }

    private function getNewPromotion() {
        $promotions = Yii::$app->params['new_promotion'];

        $info = ProductList::find()->select('name,price,unit,img,price')
            ->where(['id' => $promotions['id']])->asArray()->one();

        $text  = $info['name'];

        $link = ProductHelper::getProductLink($promotions['id']);

        $promotionPrice = PriceHelper::getProductPrice($promotions['id'], 1);

        // TODO 调整图片
        return ['text' => $text, 'img' => $info['img'], 'link' => $link, 'price' => $promotionPrice . '/' . $info['unit']];
    }

    private function getDayPromotion() {
        $cn = Yii::$app->params['day_cn'];
        $dayofweek = date('w', time());

        $promotions = Yii::$app->params['day_promotion'][$dayofweek];
        $info = ProductList::find()->select('id,name,img,price,unit')
            ->where(['id' => $promotions['id']])->asArray()->one();

        $text = $info['name'];

        $link = ProductHelper::getProductLink($promotions['id']);

        $promotionPrice = PriceHelper::getProductPrice($promotions['id'], 1);

        return ['text' => $text, 'img' => $info['img'], 'id' => $info['id'], 'link' => $link, 'price' => $promotionPrice . '/' . $info['unit']];
    }

    private function getTags() {
        $idArr = ProductList::find()->select('id')->where(['status' => 1, 'category' => ['fruit']])->asArray()->all();

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
        $info = ProductList::find()->where(['category' => ['fruit', 'nut'], 'status' => 1, 'deleteflag' =>  0])->orderBy('sale_num desc')->asArray()->all();
 
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

            // if ($value['booking_status'] == 1 && $value['num'] > 0) {
            //     $info[$key]['buy_price'] = PriceHelper::getProductPrice($value['id'], 1);
            // } else if ($value['booking_status'] == 3 && $value['num'] > 0) {
            //     $info[$key]['buy_price'] = PriceHelper::getProductPrice($value['id'], 1);
            // } else {
            //     $info[$key]['booking_price'] = PriceHelper::getProductPrice($value['id'], 2);
            // }

            if ($value['booking_status'] != 2 && $value['num'] > 0) {
                $info[$key]['buy_price'] = PriceHelper::getProductPrice($value['id'], 1);
            }

            if ($value['booking_status'] != 3) {
                $info[$key]['booking_price'] = PriceHelper::getProductPrice($value['id']);
            }

            $info[$key]['link'] = ProductHelper::getProductLink($value['id']);
        }

        return $info;
    }

    private function getPackages() {
        $info = ProductList::find()->where(['category' => 'package', 'status' => 1, 'deleteflag' =>  0])->orderBy('sale_num desc')->asArray()->all();

        foreach($info as $key => $value) {
            if ($value['booking_status'] != 2) {
                $info[$key]['buy_price'] = PriceHelper::getProductPrice($value['id'], 1);
            }

            if ($value['booking_status'] != 3) {
                $info[$key]['booking_price'] = PriceHelper::getProductPrice($value['id']);
            }

            if (empty($value['img'])) {
                $info[$key]['img'] = '/img/apple_4x3.png';
            }

            $info[$key]['index'] = $key % 3 + 1;
            $info[$key]['link']  = ProductHelper::getProductLink($value['id']);

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
