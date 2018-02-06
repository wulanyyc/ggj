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
<?= WechatWidget::widget() ?>

<main>
    <?= $content ?>
</main>

<div id="cover" class="cover"></div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>