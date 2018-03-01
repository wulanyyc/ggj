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
use app\models\ProductOrder;
use app\filters\WechatFilter;

class SiteController extends Controller
{
    public $layout = 'pc';

    private $configKeys = [
        'current-skin',
    ];

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
        $cid = isset($_COOKIE['cart_id']) ? $_COOKIE['cart_id'] : 0;

        $cartNum = 0;
        $cartLink = '/buy';

        if ($cid > 0) {
            $status = ProductOrder::find()->where(['cart_id' => $cid])->select('status')->scalar();
            if ($status <= 1) {
                $cartInfo = ProductCart::find()->select('order_type,cart')->where(['id' => $cid])->asArray()->one();
                $cartNum = count(json_decode($cartInfo['cart'], true));
                if ($cartInfo['order_type'] == 1) {
                    $cartLink = '/buy?cid=' . $cid;
                } else {
                    $cartLink = '/buy/booking?cid=' . $cid;
                }
            }
        }

        return $this->render('index', [
            'controller' => Yii::$app->controller->id,
            'dayPromotion' => $this->getDayPromotion(),
            'fruits' => $this->getFruits(),
            'homeTip' => Yii::$app->params['hometip'],
            'cartNum' => $cartNum,
            'cartLink' => $cartLink,
            'prizeLimit' => Yii::$app->params['prizeLimit'],
        ]);
    }

    private function getDayPromotion() {
        $cn = Yii::$app->params['day_cn'];
        $dayofweek = date('w', time());

        $promotions = Yii::$app->params['day_promotion'][$dayofweek];
        $info = ProductList::find()->select('id,name,img,price,unit')
            ->where(['id' => $promotions['id']])->asArray()->one();

        $text = $info['name'];

        $link = ProductHelper::getProductLink($promotions['id']);

        $promotionPrice = PriceHelper::getProductPrice($promotions['id']);

        return ['text' => $text, 'img' => $info['img'], 'id' => $info['id'], 'link' => $link, 'price' => $promotionPrice . '/' . $info['unit']];
    }

    private function getFruits() {
        $info = ProductList::find()->where(['status' => 1, 'deleteflag' =>  0])->orderBy('sale_num desc')->asArray()->all();
 
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

            $info[$key]['buy_price'] = PriceHelper::getProductPrice($value['id']);

            $info[$key]['link'] = ProductHelper::getProductLink($value['id']);
        }

        return $info;
    }
}
