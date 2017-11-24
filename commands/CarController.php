<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use Yii;
use yii\console\Controller;
use app\components\LocationHelper;
use app\models\CourierBaseInfo;
use app\models\PackageOrderList;
use app\models\Couriers;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class CarController extends Controller
{
    /**
     * 导旧表数据
     */
    public function actionIndex()
    {
        $address = file(SERVER_PATH . '/tmp/car_address.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if (empty($address)) exit;

        $geohashArr = [];
        $addressMap = [];
        foreach($address as $item) {
            $item = trim($item);
            if (empty($item)) {
                continue;
            }

            $geohash = LocationHelper::getGeohashByAddress($item);
            if (!empty($geohash)) {
                // $hash = substr($geohash, 0, 6);
                $hash = $geohash;
                $geohashArr[] = $hash;
                $addressMap[$hash] = $item;
            }
        }

        $geohashArr = array_unique($geohashArr);
        $phones = CourierBaseInfo::find()->select('user_phone')->where(['geohash' => $geohashArr])
            ->distinct(true)->asArray()->all();

        $fmtPhone = [];
        foreach($phones as $value) {
            $fmtPhone[] = $value['user_phone'];
        }

        $validPhones = PackageOrderList::findBySql("select distinct phone from package_order_list where phone in (" . implode(',', $fmtPhone) . ") and month >= 201611 and flag = 1")->asArray()->all();

        $outputStr = '';
        foreach($validPhones as $value) {
            $hash = CourierBaseInfo::find()->select('geohash')->where(['user_phone' => $value['phone']])
            ->scalar();
            $value['hash'] = $hash;
            $value['address'] = $addressMap[$hash];
            $outputStr .= implode(',', $value) . PHP_EOL;
        }

        $filename = SERVER_PATH . '/runtime/car_poi_' . date('YmdHi', time()) . '.txt';
        file_put_contents($filename, $outputStr);
    }

    public function actionGeo() {
        $address = file(SERVER_PATH . '/tmp/car_xiaoqu.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if (empty($address)) exit;

        $geohashArr = [];
        foreach($address as $item) {
            $item = trim($item);
            if (empty($item)) {
                continue;
            }

            $geohash = LocationHelper::getGeohashByAddress($item);
            if (!empty($geohash)) {
                $hash = substr($geohash, 0, 5);
                $geohashArr[$hash][] = $item;
            }
        }

        // $geohashArr = array_unique($geohashArr);

        $outputStr = '';
        foreach($geohashArr as $key => $value) {
            $outputStr .= $key . ", " . implode(":", $value) . PHP_EOL;
        }

        $filename = SERVER_PATH . '/runtime/car_geohash_five_' . date('Ymd', time()) . '.txt';
        file_put_contents($filename, $outputStr);
    }

    public function actionAd() {
        $couriers = file(SERVER_PATH . '/tmp/car_address_1207.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if (empty($couriers)) exit;

        // $yesterday = date('Ymd', time() - 86400);
        // $preday = date('Ymd', time() - 8 * 86400);

        $dates = [];
        for ($i=1; $i <= 8; $i++){
            $dates[] = date('Ymd', time() - $i * 86400);
        }

        $dateStr = implode(',', $dates);
        // echo $dateStr;exit;

        $output = [];
        foreach($couriers as $info) {
            $tmp = explode(',', $info);
            $phone = array_shift($tmp);
            $hash  = array_shift($tmp);
            $address = implode(",", $tmp);

            $cid = Couriers::find()->select('id')->where(['phone' => $phone])->scalar();

            $sendNum = Couriers::findBySql("select sum(sms_success_count) from statistics_courier_deliveries where courier_id= ". $cid ." and date in (" . $dateStr . ")")->scalar();

            // echo $sendNum;exit;
            if ($sendNum > 0) {
                $output[$phone]['phone'] = $phone;
                $output[$phone]['hash'] = $hash;
                $output[$phone]['address'] = $address;
                $output[$phone]['num'] = $sendNum;
            }
        }

        // print_r($output);exit;

        $stats = [];
        foreach($output as $item) {
            $address = $item['address'];
            if (!isset($stats[$address])) {
                $stats[$address]['num'] = $item['num'];
            } else {
                $stats[$address]['num'] += $item['num'];
            }
            $stats[$address]['address'] = $address;
        }

        // $total = Couriers::findBySql("select sum(s.sms_success_count) from statistics_courier_deliveries as s, couriers as c where s.courier_id=c.id and c.phone in (" . implode(',', $couriers) . ") and date >= " . $preday . " and date <= " . $yesterday)->scalar();

        $outputStr = '';
        foreach($stats as $value) {
            $outputStr .= implode(',', $value) . PHP_EOL;
        }

        $filename = SERVER_PATH . '/runtime/car_stat_' . date('YmdHi', time()) . '.txt';
        file_put_contents($filename, $outputStr);
    }
}
