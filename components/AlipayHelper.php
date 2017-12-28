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
        $termiNal = 'wap';

        $aop = new \AopClient;
        $aop->gatewayUrl = Yii::$app->params['alipay'][$termiNal]['gatewayUrl'];
        $aop->appId = Yii::$app->params['alipay'][$termiNal]['app_id'];
        $aop->rsaPrivateKey = Yii::$app->params['alipay'][$termiNal]['merchant_private_key'];
        $aop->format = "json";
        $aop->charset = Yii::$app->params['alipay'][$termiNal]['charset'];
        $aop->signType= Yii::$app->params['alipay'][$termiNal]['sign_type'];
        $aop->alipayrsaPublicKey = Yii::$app->params['alipay'][$termiNal]['alipay_public_key'];

        $request = new \AlipayTradeWapPayRequest ();
        $request->setBizContent(json_encode($params));
        $request->setNotifyUrl(Yii::$app->params['alipay'][$termiNal]['notify_url']);
        $request->setReturnUrl(Yii::$app->params['alipay'][$termiNal]['return_url']);
        $result = $aop->pageExecute ($request);

        return $result;
    }

    public static function pcpay($params) {
        $termiNal = 'pc';

        $aop = new \AopClient;
        $aop->gatewayUrl = Yii::$app->params['alipay'][$termiNal]['gatewayUrl'];
        $aop->appId = Yii::$app->params['alipay'][$termiNal]['app_id'];
        $aop->rsaPrivateKey = Yii::$app->params['alipay'][$termiNal]['merchant_private_key'];
        $aop->format = "json";
        $aop->charset = Yii::$app->params['alipay'][$termiNal]['charset'];
        $aop->signType= Yii::$app->params['alipay'][$termiNal]['sign_type'];
        $aop->alipayrsaPublicKey = Yii::$app->params['alipay'][$termiNal]['alipay_public_key'];

        $request = new \AlipayTradeWapPayRequest ();
        $request->setBizContent(json_encode($params));
        $request->setNotifyUrl(Yii::$app->params['alipay'][$termiNal]['notify_url']);
        $request->setReturnUrl(Yii::$app->params['alipay'][$termiNal]['return_url']);
        $result = $aop->pageExecute ($request);

        return $result;
    }

    public static function check($data, $terminal) {
        $aop = new \AopClient;
        $aop->alipayrsaPublicKey = Yii::$app->params['alipay'][$terminal]['alipay_public_key'];

        $result = $aop->rsaCheckV1($data, Yii::$app->params['alipay'][$terminal]['alipay_public_key'], Yii::$app->params['alipay'][$terminal]['sign_type']);

        return $result;
    }
}
