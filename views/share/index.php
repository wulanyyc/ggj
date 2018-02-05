<?php
use yii\web\View;
use app\components\MsaView;

$this->title = '免费吃水果';

MsaView::registerJsFile($this,'/js/share/index.js', 
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
</style>

<div id="background">
    <div style="text-align: center;margin-top: 20%;">
        <img src="/img/logo.png" style="width: 20%;"/>
    </div>

    <div style="text-align:center; font-size: 18px;line-height: 28px;margin-top: 5px;">
        <h5>免费吃水果</h5>
        <div style="color:red;font-size: 16px;">1: 分享朋友圈或群，每一个好友关注享返利<?=$share ?>元。</div>
        <div style="font-size: 16px;">2: 好友下单后享每单金额的<?=$percent ?>%作为返利。</span></div>
    </div>

    <div style="text-align: center;font-size: 16px;line-height: 24px;">
        <div style="margin-top: 10px;font-size: 16px;color:red;">我的分享二维码，已绑定个人信息</div>
        <img src="https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=<?=$ticket ?>" style="width:45%;" />
    </div>
</div>

