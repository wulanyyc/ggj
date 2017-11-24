<?php
use yii\helpers\Html;
use app\assets\AppAsset;
use app\widgets\MenuWidget;
use app\widgets\TopMenuWidget;
use app\widgets\BreadCrumbWidget;
use app\widgets\ColorWidget;
use app\assets\CookieAsset;
use app\widgets\DatatableDateWidget;
use app\components\CommonHelper;

AppAsset::register($this);
CookieAsset::register($this);
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
    <script src='/js/base/skins.min.js'></script>
    <?= ColorWidget::widget() ?>
</head>
<body>
<?php $this->beginBody() ?>
<!-- Loading Container -->
<!-- <div class='loading-container'>
    <div class='loader'></div>
</div> -->
<!--  /Loading Container -->
<!-- Navbar -->
<div class='navbar'>
    <div class='navbar-inner'>
        <div class='navbar-container'>
            <!-- Navbar Barnd -->
            <div class='navbar-header pull-left'>
                <a href='/' class='navbar-brand navbar-logo'>
                    果果佳
                </a>
            </div>
            <!-- /Navbar Barnd -->

            <!-- Account Area and Settings -->
            <?php if (isset(Yii::$app->session['username'])) { ?>
            <div class='navbar-header pull-right'>

                <div class='navbar-account'>
                    <ul class='account-area'>
                        <li class=''>
                            <a data-toggle='dropdown' class='login-area dropdown-toggle' aria-expanded='false'>
                                <section>
                                    <h2>
                                        <span class='profile' style='float:left;'>
                                            <?php echo Yii::$app->session['username'];?>
                                        </span>
                                        <span class='glyphicon glyphicon-chevron-down' style='float:left;padding-left:5px;margin-top:-2px;'></span>
                                    </h2>
                                </section>
                            </a>

                            <!--Login Area Dropdown-->
                            <ul class='pull-right dropdown-menu dropdown-arrow dropdown-login-area'>
                                <li class='edit'>
                                    <a class='pull-left' href='#'>主题</a>
                                </li>
                                <!--Theme Selector Area-->
                                <li class='theme-area'>
                                    <ul id='skin-changer' class='colorpicker'>
                                        <li><a rel='/css/skins/green.min.css' style='background-color:#53a93f;' href='#' class='colorpick-btn'></a></li>
                                        <li><a rel='/css/skins/blue.min.css' style='background-color:#5DB2FF;' href='#' class='colorpick-btn'></a></li>
                                        <li><a rel='/css/skins/azure.min.css' style='background-color:#2dc3e8;' href='#' class='colorpick-btn'></a></li>
                                        <li><a rel='/css/skins/teal.min.css' style='background-color:#03B3B2;' href='#' class='colorpick-btn'></a></li>
                                        <li><a rel='/css/skins/orange.min.css' style='background-color:#FF8F32;' href='#' class='colorpick-btn'></a></li>
                                        <li><a rel='/css/skins/pink.min.css' style='background-color:#cc324b;' href='#' class='colorpick-btn'></a></li>
                                        <li><a rel='/css/skins/darkred.min.css' style='background-color:#AC193D;' href='#' class='colorpick-btn'></a></li>
                                        <li><a rel='/css/skins/purple.min.css' style='background-color:#8C0095;' href='#' class='colorpick-btn'></a></li>
                                        <li><a rel='/css/skins/darkblue.min.css' style='background-color:#0072C6;' href='#' class='colorpick-btn'></a></li>
                                        <li><a rel='/css/skins/gray.min.css' style='background-color:#585858;' href='#' class='colorpick-btn'></a></li>
                                        <li><a rel='/css/skins/black.min.css' style='background-color:#474544;' href='#' class='colorpick-btn'></a></li>
                                        <li><a rel='/css/skins/deepblue.min.css' style='background-color:#001940;' href='#' class='colorpick-btn'></a></li>
                                    </ul>
                                </li>
                                <li class='edit'>
                                    <a class='pull-left' style='margin-top: -2px;' href='<?php echo CommonHelper::getRightLinkByMenuId(6); ?>'>设置</a>

                                    <a class='pull-right' style='margin-top: -2px;' href='/admin/reset'>修改密码</a>
                                </li>
                                <!--/Theme Selector Area-->
                                <li class='dropdown-footer'>
                                    <a href='/admin/logout'>退出</a>
                                </li>
                            </ul>
                            <!--/Login Area Dropdown-->
                        </li>
                        <!-- /Account Area -->
                    </ul>
                </div>
            </div>
            <!-- /Account Area and Settings -->
            <?php } ?>
        </div>
    </div>
</div>
<!-- /Navbar -->

<!-- Main Container -->
<div class='main-container container-fluid'>
    <!-- Page Container -->
    <div class='page-container'>
        <div class='page-body'>
            <?= $content ?>
        </div>

    </div>
    <!-- /Page Container -->
    <!-- Main Container -->
</div>

<?php $this->endBody() ?>
    <footer class='footer'>
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
