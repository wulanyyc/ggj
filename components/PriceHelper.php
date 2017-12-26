<?php
namespace app\components;

use Yii;
use yii\base\Component;
use app\modules\product\models\ProductList;

/**
 * 基础帮助类
 * @author yangyuncai
 *
 */
class PriceHelper extends Component{
    /**
     * $id 产品id
     * $type 订购类型  0: 普通  1: 预订
     */
    public static function getProductPrice($id, $type = 0) {
        $price = ProductList::find()->where(['id' => $id])->select('price')->scalar();

        // 天天特价
        $price = self::getDayPromotion($id, $price);

        // 店铺特价
        $price = self::getNewPromotion($id, $price);

        if ($type == 0) {
            return round(Yii::$app->params['buyDiscount'] * $price, 1);
        }

        if ($type == 1) {
            return round(Yii::$app->params['bookingDiscount'] * $price, 1);
        }

        return $price;
    }

    public static function getNewPromotion($id, $price) {
        $promotions = Yii::$app->params['new_promotion'];
        if ($id == $promotions['id']) {
            return $promotions['price'];
        }

        return $price;
    }

    public static function getDayPromotion($id, $price) {
        $promotions = Yii::$app->params['day_promotion'];

        $dayofweek = date('w', time());
        if ($dayofweek == 0) {
            $dayofweek = 7;
        }

        if ($promotions[$dayofweek]['id'] == $id) {
            $price = round($price * $promotions[$dayofweek]['discount'], 1);
        }

        return $price;
    }
}
