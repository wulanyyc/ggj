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

        .navbar-toggler {
            color: rgba(255,255,255,.3);
            border-color: rgba(255,255,255,.2);
        }

        .navbar {
            display: table;
        }

        .navbar-brand {
            display: table-cell;
        }

        .search-top {
            display: table-cell;
        }

        .top-customer {
            display: table-cell;
        }
    </style>
</head>
<body>
<?php $this->beginBody() ?>
<?= WechatWidget::widget() ?>

<nav class="navbar navbar-expand-md fixed-top navbar-dark" style="background-color: #1ba93b;width: 100%;">
    <a class="navbar-brand" href="/" style="color:white;padding-right: 5px;">果果佳</a>

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

            <?php if ($controller == 'contact') { ?>
            <li class="nav-item active">
            <?php } else { ?>
            <li class="nav-item">
            <?php } ?>
                <a class="nav-link" href="/contact">联系我们</a>
            </li>
        </ul>
    </div>

<!--     <div class="d-inline search-top">
        <input class="form-control mr-sm-2" type="text" id="search_product" placeholder='只卖新鲜好吃的' aria-label="只卖新鲜好吃的" style="font-size: 15px;" />
    </div> -->

    <a class="top-customer" style="text-decoration: none;color: #fff;font-size: 16px;text-align: right;width: 70px;" href="/customer">个人中心</a>
</nav>

<main>
    <?= $content ?>
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