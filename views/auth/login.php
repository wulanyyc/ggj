<?php
use yii\web\View;
use app\components\MsaView;

$this->title = '登录';

MsaView::registerJsFile($this,'/js/auth/index.js',
    [
        'position' => View::POS_END,
        'depends' => [
            'app\assets\DatePickerAsset',
            'app\assets\SelectAsset',
            'app\assets\BootBoxAsset',
         ]
    ]
);
?>

<style type="text/css">
    .login{
        width: 460px;
        margin: 0 auto;
        padding: 30px;
    }

    .login-title{
        text-align: center !important;
        font-size: 20px;
    }

    .login input{
        margin-bottom: 10px;
    }

    #login_form{
        padding:20px;
    }

</style>

<div class="login">
    <div class="well with-header">
        <div class="header bordered-magenta login-title">登 录</div>
        <div class="row" >
            <form id="login_form">
                <div class="alert alert-danger fade in hide">
                    用户名或密码错误, 若无帐号请联系管理员
                </div>
                <input type="text" class="form-control input-lg" id="username" placeholder="用户名">
                <input type="password" class="form-control input-lg" id="password" placeholder="密码">
                <a href="javascript:void(0);" class="btn btn-magenta btn-lg" id="submit_login_form">提交</a>
            </form>
        </div>
    </div>
</div>

