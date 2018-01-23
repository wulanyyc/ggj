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
        background-image: url('/img/prize/suc3.jpeg');
        background-size: 100% 100%;
        background-repeat: no-repeat;
    }
</style>

<div style="display: table;width: 100%;height: 100%;">
    <div style="display: table-row;vertical-align: middle;text-align: center;">
        <img src="/img/ggj.jpg" style="width: 20%;"/>
    </div>

    <div style="display: table-row;text-align: center;vertical-align: middle;font-size: 18px;line-height: 28px;">
        <div>恭喜您获得</div>
        <div style="color:red;"><?=$text ?>, 需领取到账户</div>
        <div><?=$day ?>天后可继续抽奖</span></div>
    </div>

    <div style="display: table-row;text-align: center;vertical-align: middle;font-size: 16px;line-height: 24px;">
        <div style="margin-top: 10px;">长按下图中二维码，识别领取</div>
        <img src="https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=<?=$ticket ?>" style="width:40%;" />
        <div>请在30天内领取，过期作废</div>
    </div>

    <div style="display: table-row;text-align: center;vertical-align: middle;font-size: 16px;line-height: 24px;">
        <div style="width: 70%;margin-left: 10%;margin-top: 10px;">优惠券：购物中可直接减免金额</div>
        <div style="width: 70%;margin-left: 10%;margin-top: 5px;">水果礼品：
            <span style="color:red;">需在已支付成功的订单中领取，随订单一起发货</span>
        </div>
    </div>
</div>

