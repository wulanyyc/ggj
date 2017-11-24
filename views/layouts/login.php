<?php
use yii\helpers\Html;
use app\assets\AppAsset;
use app\components\CommonHelper;

AppAsset::register($this);
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
</head>
<body>
<?php $this->beginBody() ?>
    <!-- Main Container -->
    <div class='main-container container-fluid'>
        <!-- Page Container -->
        <div class='page-container'>
            <div class='page-body'>
                <?= $content ?>
            </div>

        </div>
        <!-- /Page Container -->
    </div>
    <!-- Main Container -->

<?php $this->endBody() ?>
    <footer class='footer '>
        <div class='container'>
            <hr>
            <div class='row footer-bottom'>
                <ul class='list-inline text-center'>
                    <li>©2017 成都果果佳科技 </li>
                </ul>
            </div>
        </div>
    </footer>
</body>
</html>
<?php $this->endPage() ?>
