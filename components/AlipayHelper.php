<?php
namespace app\components;

use Yii;
use yii\base\Component;


/**
 * 基础帮助类
 * @author yangyuncai
 *
 */
class AlipayHelper extends Component{
    public static function getRequest($visitTerminal = 'wap') {
        $alipay = new \AopClient;
        $alipay->gatewayUrl = Yii->$app->params['alipay'][$visitTerminal]['gatewayUrl'];
        $alipay->appId = Yii->$app->params['alipay'][$visitTerminal]['appid'];
        $alipay->rsaPrivateKey = Yii->$app->params['alipay'][$visitTerminal]['merchant_private_key'];
        $alipay->format = "json";
        $alipay->charset = Yii->$app->params['alipay'][$visitTerminal]['charset'];
        $alipay->signType= "RSA2";
        $alipay->alipayrsaPublicKey = Yii->$app->params['alipay'][$visitTerminal]['alipay_public_key'];
    }
}
