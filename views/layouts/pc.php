<?php
use yii\helpers\Html;
use app\assets\SiteAsset;
use app\widgets\WechatWidget;
// use app\assets\WechatAsset;

$controller = Yii::$app->controller->id;

SiteAsset::register($this);
// WechatAsset::register($this);

?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang='<?= Yii::$app->language ?>'>
<head>
    <meta charset='<?= Yii::$app->charset ?>'/>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <style type="text/css">
        body {
            background-color: #f5f5f5;
        }

        .navbar-toggler {
            color: rgba(255,255,255,.3);
            border-color: rgba(255,255,255,.2);
        }
    </style>
</head>
<body>
<?php $this->beginBody() ?>
<nav class="navbar navbar-expand-md fixed-top navbar-dark" style="background-color: #53a93f;">
    <a class="navbar-brand" href="/" style="color:white;">果果佳</a>

    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsDefault" aria-controls="navbarsDefault" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse w-25" id="navbarsDefault">
        <ul class="navbar-nav mr-auto">
            <?php if ($controller == 'site') { ?>
            <li class="nav-item active">
            <?php } else { ?>
            <li class="nav-item">
            <?php } ?>
                <a class="nav-link" href="/">首页</a>
            </li>

            <?php if ($controller == 'promotion') { ?>
            <li class="nav-item active">
            <?php } else { ?>
            <li class="nav-item">
            <?php } ?>
                <a class="nav-link" href="/promotion">今日特价</a>
            </li>

            <?php if ($controller == 'booking') { ?>
            <li class="nav-item active">
            <?php } else { ?>
            <li class="nav-item">
            <?php } ?>
                <a class="nav-link" href="/booking">预约套餐</a>
            </li>

            <?php if ($controller == 'contact') { ?>
            <li class="nav-item active">
            <?php } else { ?>
            <li class="nav-item">
            <?php } ?>
                <a class="nav-link" href="/contact">联系我们</a>
            </li>
        </ul>
    </div>

    <div class="d-inline search-top" style="position: relative;">
        <input class="form-control mr-sm-2" type="text" id="search_product" placeholder="美丽健康，好吃不贵" aria-label="美丽健康，新鲜不贵" style="padding-left: 30px;font-size: 14px;" />
        <div style="position: absolute;left:8px;top:5px;"><i class="fa fa-search" aria-hidden="true"></i></div>
    </div>

    <a style="display: inline-block;margin-left:10px;text-decoration: none;color: #fff;" href="/customer">个人中心</a>
</nav>

<main>
    <?= $content ?>
    <?= WechatWidget::widget() ?>
</main>

<footer>
    <hr/>
    <div style="display: flex;justify-content: center;flex-direction: row;border-top: 1px solid #f5f5f5;font-size: 15px;">
        <p style="padding:0px 5px;">&nbsp;蜀ICP备17043581号</p>
        <a style="text-decoration: none;color:black;padding:0px 5px;" href="/">&copy; 成都果果佳科技</a>
        <a style="text-decoration: none;color:black;padding:0px 5px;" href="/contact">联系我们&nbsp;</a>
    </div>
</footer>

<div id="cover" class="cover"></div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>