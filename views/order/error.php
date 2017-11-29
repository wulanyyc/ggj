<?php 
use yii\web\View;
use app\components\MsaView;
use app\assets\HashAsset;

$this->title = '套餐';

MsaView::registerJsFile($this,'/js/order/index.js', 
    ['position' => View::POS_END, 
        'depends' => [
            'app\assets\SiteAsset',
        ]
    ]
);

?>

<style type="text/css">

</style>

<div style="text-align: center;margin-top: 20px;">
    <span style="color:red;">非法访问，请重新</span><a href="/order/login" style="text-decoration: none;">登录</a>
</div>
