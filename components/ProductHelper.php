<?php
namespace app\components;

use Yii;
use yii\base\Component;
use app\modules\product\models\ProductList;
use app\models\ProductPackage;
use app\components\SiteHelper;

/**
 * 基础帮助类
 * @author yangyuncai
 *
 */
class ProductHelper extends Component {
    public static $precison = 1;

    /**
     * $id 产品id
     * $type 订购类型  1: 普通  2: 预订
     */
    public static function getProductLink($id) {
        $data = ProductList::find()->where(['id' => $id])->select('id, link, booking_status, num')->asArray()->one();

        if (empty($data)) {
            return '/';
        }

        $link = $data['link'];

        if (!empty($link)) {
            return $link;
        }

        if ($data['booking_status'] == 2) {
            return '/buy/booking/?id=' . $data['id'];
        }

        if ($data['booking_status'] == 3) {
            return '/buy/?id=' . $data['id'];
        }

        if (empty($_COOKIE['order_type'])) {
            return '/buy/type/?id=' . $data['id'];
        }

        if ($_COOKIE['order_type'] == 2) {
            $link = '/buy/booking/?id=' . $data['id'];
        } else {
            if ($data['num'] > 0) {
                $link = '/buy/?id=' . $data['id'];
            } else {
                $link = '/buy/booking/?id=' . $data['id'];
            }
        }

        return $link;
    }
}
