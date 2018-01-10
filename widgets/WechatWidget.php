<?php
namespace app\widgets;

use Yii;
use yii\base\Widget;
use app\components\WechatHelper;
use app\models\CustomerWeixin;
use app\models\Customer;

/**
 * 面包屑
 * @author yangyuncai
 *
 */
class WechatWidget extends Widget
{
    /**
     * 初始化
     */
    public function init(){
        parent::init();
    }
    
    /**
     * 执行
     */
    public function run(){
        $wechat = WechatHelper::getPageWechatData();
        if (empty($wechat)) {
            return '';
        }

        if (empty($_COOKIE['cid']) && !empty($_COOKIE['openid'])) {
            $cid = CustomerWeixin::find()->where(['openid' => $_COOKIE['openid']])->select('customer_id')->scalar();
            if ($cid > 0) {
                Yii::error('test_log:' . $cid);
                $phone = Customer::find()->where(['id' => $cid])->select('phone')->scalar();
                setcookie('cid', $cid, 2592000, '/');
                setcookie('secret', SiteHelper::buildSecret($phone), 2592000, '/');
                Yii::error('test_log:' . $phone);
            }
        }

        $html = <<<EOF
        <input type="hidden" name="we_appid" value="{$wechat['appid']}" id="we_appid" />
        <input type="hidden" name="we_timestamp" value="{$wechat['timestamp']}" id="we_timestamp" />
        <input type="hidden" name="we_noncestr" value="{$wechat['noncestr']}" id="we_noncestr" />
        <input type="hidden" name="we_signature" value="{$wechat['signature']}" id="we_signature" />
EOF;
        
        $basePath = Yii::$app->basePath;
        $v = @filemtime($basePath.'/web/js/wechat.js');

        $script = "<script src='http://res.wx.qq.com/open/js/jweixin-1.2.0.js'></script>";
        $script .= "<script src='/js/wechat.js?v=" . $v . "'></script>";
        return $html . $script;
    }
}