<?php
use yii\helpers\Html;
use app\assets\SiteAsset;

$controller = Yii::$app->controller->id;

SiteAsset::register($this);
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
    </style>
</head>
<body>
<?php $this->beginBody() ?>
<nav class="navbar navbar-expand-md fixed-top navbar-dark" style="background-color: #53a93f;opacity: 0.95;display: flex;flex-direction: row;justify-content: space-between;flex-wrap: nowrap;">
    <a href="#" id="back" style="color:white;"><i class="fa fa-chevron-left" aria-hidden="true"></i></a>
    <a class="navbar-brand" href="/" style="color:white;text-align: center;">果果佳</a>
    <a style="display: inline-block;margin-left:10px;text-decoration: none;color: #fff;" href="/order">查看订单</a>
</nav>

<main>
    <?= $content ?>
</main>

<footer>
    <hr>
    <p style="padding-left: 1%;">&copy; 成都果果佳科技 2017</p>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>