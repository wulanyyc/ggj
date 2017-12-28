<?php
namespace app\components;

use Yii;
use yii\base\Component;
use app\components\SiteHelper;


/**
 * 基础帮助类
 * @author yangyuncai
 *
 */
class AlipayHelper extends Component{
    public static function wappay($params) {
        $visitTerminal = 'wap';

        $alipay = new \AopClient;
        $alipay->gatewayUrl = Yii::$app->params['alipay'][$visitTerminal]['gatewayUrl'];
        $alipay->appId = Yii::$app->params['alipay'][$visitTerminal]['app_id'];
        $alipay->rsaPrivateKey = Yii::$app->params['alipay'][$visitTerminal]['merchant_private_key'];
        $alipay->format = "json";
        $alipay->charset = Yii::$app->params['alipay'][$visitTerminal]['charset'];
        $alipay->signType= Yii::$app->params['alipay'][$visitTerminal]['sign_type'];;
        $alipay->alipayrsaPublicKey = Yii::$app->params['alipay'][$visitTerminal]['alipay_public_key'];

        $request = new \AlipayTradeWapPayRequest ();
        $request->setBizContent(json_encode($params));
        $result = $aop->pageExecute ( $request); 
        return $result;
    }
}
