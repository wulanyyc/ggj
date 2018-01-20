<?php 
use yii\web\View;
use app\components\MsaView;

$this->title = '登录平台';

MsaView::registerJsFile($this,'/js/order/login.js', 
    ['position' => View::POS_END, 
        'depends' => [
            'app\assets\SiteAsset',
        ]
    ]
);

?>

<style type="text/css">
    #userinfo {
        margin: 1%;
    }
</style>

<div class="card" id="userinfo">
  <div class="card-header bg-white" style="color: #1ba93b;border-radius: 0;border-bottom: 2px solid #92BC2C;">
      登录
  </div>
  <div style="width:94%;margin: 10px auto;" id="userinfo_form">
    <div style="display: -webkit-flex;flex-direction: -webkit-row;justify-content: -webkit-flex-start;display: flex;flex-direction: row;justify-content: flex-start;margin-bottom: 10px;">
      <input type="number" class="form-control" id="userphone" name="userphone" placeholder="手机号码">
    </div>

    <div style="display: -webkit-flex;flex-direction: -webkit-row;justify-content: -webkit-flex-start;display: flex;flex-direction: row;justify-content: flex-start;margin-bottom: 10px;">
      <input type="text" class="form-control" name="code" id="code" placeholder="4位验证码" style="width:50%;">
      <button type="button" class="btn btn-outline-danger" style="margin-left:5px;width:100px;" id="getcode">手机验证码</button>
    </div>

    <button type="button" class="btn btn-success" id="submit" style="width:50%;margin-left:25%;margin-top:10px;">
      提交
    </button>
  </div>
</div>
