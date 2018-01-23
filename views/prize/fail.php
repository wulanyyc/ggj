<?php
use yii\web\View;
use app\components\MsaView;

$this->title = '抽奖失败';

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
        <br/>
        <div>很遗憾</div>

        <div style="color:red;">未抽到您想要的奖品, <span style="color:#000;"><?=$day ?>天后可继续抽奖</span></div>
    </div>

    <div style="display: table-row;text-align: center;vertical-align: middle;">
        <br/>
        <a href="/prize/suc" class="btn btn-success" style="width: 50%;">去领了吧</a>
    </div>
</div>

