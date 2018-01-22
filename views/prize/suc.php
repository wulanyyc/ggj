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


<div style="display: table;width: 100%;height: 100%;">
    <div style="display: table-cell;vertical-align: middle;text-align: center;">
        <img src="/img/ggj.jpg" style="width: 200px;"/>
    </div>
    <div style="display: table-cell;text-align: center;vertical-align: middle;">
        <div>恭喜你</div>
        <div><?=$text ?></div>
    </div>
    <div style="display: table-cell;text-align: center;vertical-align: middle;">
        <img src="https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=<?=$ticket ?>" style="width:50%;" />
    </div>
</div>

