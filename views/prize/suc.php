<?php
use yii\web\View;
use app\components\MsaView;

$this->title = '抽奖成功';

MsaView::registerJsFile($this,'/js/prize/index.js', 
    ['position' => View::POS_END, 
        'depends' => [
            'app\assets\SiteAsset',
        ]
    ]
);

?>

<style type="text/css">
    body {
        padding-top: 0 !important;
    }

    #background {
        width:100%;
        height: 100%;
        background-image: url('/img/prize/suc2.jpeg');
        background-size: 100% 100%;
        position: fixed;
        z-index: 1;
    }
</style>

<div id="background">
    <div style="text-align: center;margin-top: 10%;">
        <img src="/img/ggj.jpg" style="width: 20%;"/>
    </div>

    <div style="text-align:center; font-size: 18px;line-height: 28px;">
        <div>恭喜您获得</div>
        <div style="color:red;font-size: 16px;"><?=$text ?>, 需领取到账户</div>
        <div style="font-size: 16px;"><?=$day ?>天后可继续抽奖</span></div>
    </div>

    <div style="text-align: center;font-size: 16px;line-height: 24px;">
        <div style="margin-top: 5px;">长按下图中二维码，识别领取</div>
        <img src="https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=<?=$ticket ?>" style="width:40%;" />
        <div>请在30天内领取，过期作废</div>
    </div>

    <div style="text-align: center;font-size: 14px;line-height: 20px;">
        <!-- <div style="width: 70%;margin-left: 10%;margin-top: 10px;">优惠券：购物中可直接减免金额</div> -->
        <div style="width: 70%;margin-left: 10%;margin-top: 5px;">备注：
            <span style="color:red;">水果礼品需在已支付订单中领取，随订单一起发货</span>
        </div>
    </div>
</div>

