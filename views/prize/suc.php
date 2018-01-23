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
        background-color: #fff;
    }
</style>


<div style="display: table;width: 100%;height: 100%;">
    <div style="display: table-row;vertical-align: middle;text-align: center;">
        <img src="/img/ggj.jpg" style="width: 30%;"/>
    </div>

    <div style="display: table-row;text-align: center;vertical-align: middle;font-size: 18px;line-height: 30px;">
        <div>恭喜您获得</div>
        <div style="color:red;"><?=$text ?>, <span style="color:#000;"><?=$day ?>天后可继续抽奖</span></div>
    </div>

    <div style="display: table-row;text-align: center;vertical-align: middle;font-size: 18px;line-height: 30px;">
        <br/>
        <div style="width: 80%;margin-left: 10%;">优惠券：购物中直接减免金额</div>
        <div style="width: 80%;margin-left: 10%;margin-top:5px;">水果礼品：<span style="color:red;">需在任何已支付成功的订单中领取，随该订单一起发货</span></div>
    </div>

    <div style="display: table-row;text-align: center;vertical-align: middle;">
        <br/>
        <div>长按下图中二维码，识别领奖</div>
        <img src="https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=<?=$ticket ?>" style="width:50%;" />
        <div>请在30天内领取，过期作废</div>
    </div>
</div>

