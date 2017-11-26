<?php
namespace app\components;

use Yii;
use yii\base\Component;

/**
 * 基础帮助类
 * @author yangyuncai
 *
 */
class PriceHelper extends Component{
    public static function getProductPrice($id, $price) {
        // 天天特价
        $price = self::getDayPromotion($id, $price);

        // 店铺特价
        $price = self::getNewPromotion($id, $price);

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
