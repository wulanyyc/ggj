<?php
namespace app\components;

use Yii;
use yii\base\Component;



/**
 * 基础帮助类
 * @author yangyuncai
 *
 */
class WechatHelper extends Component{
    public static function checkSignature() {
        $params = Yii::$app->request->get();
        $signature = $params['signature'];
        $timestamp = $params['timestamp'];
        $nonce = $params['nonce'];


        $tmp = [Yii::$app->params['token'], $timestamp, $nonce];
        sort($tmp, SORT_STRING);

        $tmpStr = implode(',', $tmp);
        $tmpStr = shal($tmpStr);

        if ($signature == $tmpStr) {
            return true;
        } else {
            return false;
        }
    }
}
