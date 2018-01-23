<?php
use yii\web\View;
use app\components\MsaView;

$this->title = '抽奖提示';

?>

<style type="text/css">
    body {
        background-color: #fff;
    }
</style>


<div style="display: table;width: 100%;height: 100%;text-align: center;">
    <p style="color:red;font-size: 20px;margin-top:50px;">本周已达到抽奖最大限制，请<?=$day ?>天后再抽奖</p>
</div>

