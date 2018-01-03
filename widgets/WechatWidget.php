<?php
namespace app\widgets;

use yii\base\Widget;
use app\components\WechatHelper;

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
        $html = <<<EOF
        <input type="hidden" name="we_appid" value="{$wechat['appid']}" id="we_appid" />
        <input type="hidden" name="we_timestamp" value="{$wechat['timestamp']}" id="we_timestamp" />
        <input type="hidden" name="we_noncestr" value="{$wechat['noncestr']}" id="we_noncestr" />
        <input type="hidden" name="we_signature" value="{$wechat['signature']}" id="we_signature" />
EOF;
        return $html;
    }
}