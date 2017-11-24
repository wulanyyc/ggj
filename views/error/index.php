<?php

use Yii;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

$this->title = '报错 ';
?>
<style>

body:before {
    content: " ";
    background-color: #FFF;
}
.page-body {
    background-color: #FFF;
    height:100%;
}

.cont {
    width:50%;
    margin: 10% auto;
    text-align:center;
}

.bg-404 {
    background: url('/img/404.png') no-repeat center center;
    width: 128px;
    height: 128px;
}

.bg-error {
    background: url('/img/robot.gif') no-repeat center center;
    width: 128px;
    height: 128px;
}
.d-i-b {
    display: inline-block;
    vertical-align: middle;
}
</style>

<div class="cont">
    <span></span>
    <?php if ($exception->getName() == 'Not Found') {?>
    <div class="bg-404 d-i-b"></div>
    <?php } else {?>
    <div class="bg-error d-i-b"></div>
    <?php } ?>

    <div class="d-i-b">
        <p>
            <?php if ($exception->getName() == 'Not Found') {
                Yii::$app->controller->redirect('/');
            } else {?>
            <p>&nbsp;
            <?php
                echo $exception->getMessage();
            ?></p>
            <?php } ?>
            <p>&nbsp;若有疑问，请联系&nbsp;<a href="mailto:yyc@yunlaba.com">yyc@yunlaba.com</a> 谢谢！</p>
        </p>
    </div>
</div>
