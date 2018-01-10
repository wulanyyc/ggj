<?php 
use yii\web\View;
use app\assets\SiteAsset;
use app\components\MsaView;

$this->title = '反馈建议';

MsaView::registerJsFile($this,'/js/customer/index.js', 
    ['position' => View::POS_END, 
        'depends' => [
            'app\assets\SiteAsset',
        ]
    ]
);
?>

<style type="text/css">
  .item {
    padding-left: 20px;
    margin-bottom: 1%;
    display: flex;
    flex-direction: row;
  }

  .label-text {
    display: block;
    min-width: 60px;
  }

  footer {
    display: none;
  }
</style>

<div style="margin-top: 1%;width:100%;" id="content">
    <div class="card" style="margin: 1%;">
      <div class="card-header bg-white" style="color: #53a93f;border-radius: 0;border-bottom: 2px solid #92BC2C;">
        反馈意见
      </div>
      <textarea name="advice" id="advice" style="width:96%;height:200px;margin: 2%; border:1px solid #ddd;" maxlength=200></textarea>
      <button type="button" class="btn btn-danger" id="feedback" style="width:50%;margin-left:25%;margin-bottom: 10px;margin-top:5px;">提交意见</button>
    </div>
    <div class="card" style="margin: 1%;">
      <div class="card-header bg-white" style="color: #53a93f;border-radius: 0;border-bottom: 2px solid #92BC2C;">
        联系我们
      </div>

      <div class="item" style="margin-top: 1%;">
          <div class="label-text">客服电话：</div>
          <div>18980457783 (同微信号)</div>
      </div>

      <div class="item" style="margin-top: 1%;">
          <div class="label-text">老板电话：</div>
          <div>13880494109 (同微信号)</div>
      </div>
    </div>
    <br/>
    <button type="button" class="btn btn-success" id="inner_back" style="width:50%;margin-left:25%;margin-bottom: 10px;margin-top:5px;">返回</button>
</div>
