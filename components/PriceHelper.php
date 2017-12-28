<?php
namespace app\components;

use Yii;
use yii\base\Component;
use app\modules\product\models\ProductList;
use app\modules\product\models\Coupon;
use app\modules\product\models\CouponUse;
use app\models\ProductOrder;
use app\models\ProductCart;

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

        if (empty($price)) {
            return 0;
        }

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

    public static function getValidCoupon() {
        $currentDate = date('Ymd', time());
        $phone = $_COOKIE['userphone'];
        $tongyong = Coupon::find()->where(['type' => 2])
            ->andWhere(['<=', 'start_date', $currentDate])
            ->andWhere(['>=', 'end_date', $currentDate])
            ->asArray()->all();

        foreach($tongyong as $key => $item) {
            $exsit = CouponUse::find()->where(['userphone' => $phone, 'use_status' => 2, 'cid' => $item['id']])->count();
            if ($exsit > 0) {
                unset($tongyong[$key]);
            }
        }

        $lingqu = Coupon::findBySql("select c.* from coupon as c, coupon_use as u where c.id = u.cid and c.start_date <= " . $currentDate . " and c.end_date >=" . $currentDate . " and u.userphone=" . $phone . " and u.use_status = 1 and c.type = 1")->asArray()->all();

        $data = array_merge($tongyong, $lingqu);

        return $data;
    }

    public static function calculateCounponFee($ids) {
        if (empty($ids)) return 0;

        $data = self::getValidCoupon();
        if (empty($data)) return 0;

        $coupons = explode(',', $ids);
        $fee = 0;
        foreach($data as $item) {
            foreach($coupons as $cid) {
                if ($item['id'] == $cid) {
                    $fee += $item['money'];
                }
            }
        }

        return $fee;
    }

    // 计算邮费 $type: 购买类型
    public static function calculateExpressFee($expressRule, $type, $productPrice) {
        if ($expressRule == 1) {
            return 0;
        }

        if ($type == 0) {
            if ($productPrice < Yii::$app->params['buyGod']) {
                return Yii::$app->params['expressFee'];
            } else {
                return 0;
            }
        }

        if ($type == 1) {
            if ($productPrice < Yii::$app->params['bookingGod']) {
                return Yii::$app->params['expressFee'];
            } else {
                return 0;
            }
        }
    }

    // 计算产品价格
    public static function calculateProductPrice($id) {
        $data = ProductCart::find()->where(['id' => $id])->select('cart,type')->asArray()->one();
        // 计算产品价格
        $carts = json_decode($data['cart'], true);

        $productPrice = 0;
        foreach($carts as $item) {
            $id = $item['id'];
            $productPrice += $item['num'] * PriceHelper::getProductPrice($id, $data['type']);
        }

        $productPrice = round($productPrice, 1);

        return $productPrice;
    }

    // 计算订单价格
    public static function calculateOrderPrice($id) {
        $data = ProductOrder::find()->where(['id' => $id])->asArray()->one();

        $productPrice = self::calculateProductPrice($data['cart_id']);
        $expressFee   = self::calculateExpressFee($data['express_rule'], $data['type'], $productPrice);
        $couponFee    = self::calculateCounponFee($data['coupon_ids']);
        $discountFee  = $data['discount_fee'];

        return round($productPrice + $expressFee - $couponFee - $discountFee, 1);
    }
}
