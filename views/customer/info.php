<?php 
use yii\web\View;
use app\components\MsaView;
use app\assets\HashAsset;

$this->title = '个人信息';

MsaView::registerJsFile($this,'/js/customer/info.js', 
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

    #edit_phone_card {
      position: fixed;
      z-index: 100;
      bottom: 0;
      width: 100%;
      border-radius: 0;
      height: 80%;
      
      display: none;
    }
</style>

<div class="card" id="userinfo">
  <div class="card-header bg-white" style="color: #53a93f;border-radius: 0;border-bottom: 2px solid #92BC2C;">
      个人信息
  </div>
  <div style="width:94%;margin: 10px auto;">
    <div style="display: flex;flex-direction: row;justify-content: flex-start;margin-bottom: 10px;">
      <input type="text" class="form-control" id="nick" name="nick" maxlength="10" placeholder="昵称（限10字）">
    </div>
    <div style="display: flex;flex-direction: row;justify-content: flex-start;margin-bottom: 10px;align-items: center;">
      <input type="number" class="form-control" id="userphone" name="userphone" placeholder="手机号码" readonly>
      <span id='edit_phone' style="padding-left: 15px;font-size: 14px;color:#0C58B0;width: 130px;">修改号码&nbsp;<i class="fa fa-chevron-right" aria-hidden="true" style="color:#ccc;"></i></span>
    </div>

    <button type="button" class="btn btn-success" id="submit" style="width:50%;margin-left:25%;margin-top:10px;">
      提交
    </button>
  </div>
</div>

<div class="card" id="edit_phone_card">
  <div class="card-header bg-white" style="color: #53a93f;border-radius: 0;border-bottom: 1px solid #92BC2C;display: flex;flex-direction: row;justify-content: space-between;">
      修改号码<i class="fa fa-times" aria-hidden="true" style="cursor: pointer;" id="close_phone"></i>
  </div>
  <div style="width:94%;margin: 10px auto;">
    <div style="margin-bottom: 10px;">
      <input type="number" class="form-control" id="new_userphone" name="new_userphone" placeholder="手机号码" value=""/>
    </div>

    <div style="display: flex;flex-direction: row;justify-content: flex-start;margin-bottom: 10px;">
      <input type="text" class="form-control" name="code" id="code" placeholder="4位验证码" style="width:50%;" />
      <button type="button" class="btn btn-outline-danger" style="margin-left:5px;width:100px;" id="getcode">短信验证码</button>
    </div>

    <button type="button" class="btn btn-success" id="next" style="width:50%;margin-left:25%;margin-top:10px;">
      确定
    </button>
  </div>
</div>
