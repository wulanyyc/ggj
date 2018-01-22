<?php
use yii\web\View;
use app\components\MsaView;

$this->title = '抽奖';

MsaView::registerJsFile($this,'/js/prize/index.js', 
    ['position' => View::POS_END, 
        'depends' => [
            'app\assets\SiteAsset',
        ]
    ]
);

?>

<style type="text/css">
    
</style>


<div style="display: table;width: 100%;height: 100%;background-color: #f5f5f5;">
    <div style="display: table-row;vertical-align: middle;text-align: center;">
        <img src="/img/ggj.jpg" style="width: 30%;"/>
    </div>
    <div style="display: table-row;text-align: center;vertical-align: middle;">
        <div>恭喜您获得</div>
        <div style="color:red;"><?=$text ?>, <?=$day ?>天后可继续抽奖</div>
    </div>

    <div style="display: table-row;text-align: center;vertical-align: middle;">
        <p style="width: 80%;margin-left: 10%;">优惠券：在购买中直接减免订单金额</p>
        <p style="width: 80%;margin-left: 10%;">水果礼品：<span style="color:red;">可在平台任何支付完成订单中领取，并随该订单一起发货</span></p>
    </div>
    <div style="display: table-row;text-align: center;vertical-align: middle;">
        <div>长按识别二维码领奖</div>
        <img src="https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=<?=$ticket ?>" style="width:50%;" />
        <div>请在30天内领取，过期作废</div>
    </div>
</div>

