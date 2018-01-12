<?php
use yii\helpers\Html;
use app\assets\SiteAsset;
use app\widgets\WechatWidget;

$controller = Yii::$app->controller->id;

SiteAsset::register($this);


?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang='<?= Yii::$app->language ?>'>
<head>
    <meta charset='<?= Yii::$app->charset ?>'/>
    <meta name='viewport' content='width=device-width, minimum-scale=1.0,maximum-scale=1.0,user-scalable=no'>
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <style type="text/css">
        body {
            background-color: #f5f5f5;
        }
    </style>
</head>
<body>
<?php $this->beginBody() ?>
<nav class="navbar navbar-expand-md fixed-top navbar-dark" style="background-color: #53a93f;display: flex;flex-direction: row;justify-content: space-between;flex-wrap: nowrap;">
    <div id="back" style="color:white;cursor: pointer;display: inline;"><i class="fa fa-chevron-left" aria-hidden="true"></i></div>
    <a class="navbar-brand" href="/" style="color:white;text-align: center;width: 60%;padding-left: 15%">果果佳</a>
    <a style="display: inline-block;margin-left:10px;text-decoration: none;color: #fff;" href="/customer">个人中心</a>
</nav>

<main>
    <?= $content ?>
    <?= WechatWidget::widget() ?>
</main>

<footer>
    <hr/>
    <div style="display: flex;justify-content: center;flex-direction: row;border-top: 1px solid #f5f5f5;font-size: 14px;">
        <p style="padding:0px 3px;">蜀ICP备17043581号</p>
        <a style="text-decoration: none;color:black;padding:0px 3px;" href="/">&copy; 成都果果佳科技</a>
        <a style="text-decoration: none;color:black;padding:0px 3px;" href="/contact">联系我们</a>
    </div>
</footer>

<div id="cover" class="cover"></div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>