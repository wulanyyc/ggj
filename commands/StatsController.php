<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use Yii;
use yii\console\Controller;
use app\components\WechatHelper;
use app\components\PriceHelper;
use app\components\SiteHelper;
use app\models\CustomerWeixin;
use app\models\Customer;
use app\models\ProductOrder;
use app\models\ProductCart;
use app\modules\product\models\ProductList;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class StatsController extends Controller
{
    public function actionSaleinit()
    {
        if (empty($date)) {
            $data = ProductOrder::findBySql('select cart_id from product_order where status in (2,3)')->asArray()->all();
            if (!empty($data)) {
                // print_r($data);exit;
                foreach($data as $key => $value) {
                    $json = ProductCart::find()->select('cart')->where(['id' => $value['cart_id']])->scalar();
                    $jsonData = json_decode($json, true);

                    foreach($jsonData as $item) {
                        $sale = ProductList::find()->select('sale_num')->where(['id' => $item['id']])->scalar();
                        $sale += $item['num'];
                        ProductList::updateAll(['sale_num' => $sale], ['id' => $item['id']]);
                    }
                }
            }
        }
    }

    public function actionSale()
    {
        $date = date('Ymd', time() - 86400);

        $data = ProductOrder::findBySql('select cart_id from product_order where status in (2,3) and `date`=' . $date)->asArray()->all();

        if (!empty($data)) {
            foreach($data as $key => $value) {
                $json = ProductCart::find()->select('cart')->where(['id' => $value['cart_id']])->scalar();
                $jsonData = json_decode($json, true);

                foreach($jsonData as $item) {
                    $sale = ProductList::find()->select('sale_num')->where(['id' => $item['id']])->scalar();
                    $sale += $item['num'];
                    ProductList::updateAll(['sale_num' => $sale], ['id' => $item['id']]);
                }
            }
        }
    }

    public function actionTest() {
        echo SiteHelper::handleNick(' ..*中国..*');
    }
}
