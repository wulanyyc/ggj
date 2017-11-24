<?php 
use yii\web\View;
use app\components\MsaView;

$this->title = '联系我们';

MsaView::registerJsFile($this,'/js/contact/index.js', 
    ['position' => View::POS_END, 
        'depends' => [
            'app\assets\SiteAsset',
        ]
    ]
);
?>

<style type="text/css">
  .label {
    display: inline-block;
    width: 100px;
  }

  .item {
    padding-left: 20px;
    margin-bottom: 1%;
    display: flex;
    flex-direction: row;
  }
</style>

<div style="margin-top: 1%;">
    <div class="card" style="margin: 1%;">
      <div class="card-header bg-white" style="color: #53a93f;border-radius: 0;border-bottom: 2px solid #92BC2C;">
        联系我们
      </div>

      <div class="item" style="margin-top: 1%;">
          <div class="label">联系电话：</div>
          <div>13880494109 (同微信号)</div>
      </div>

      <div class="item">
        <div class="label">联系地址：</div>
        <div>成都市青羊区万科金色领域1栋816室</div>
      </div>

      <div class="item">
          <div class="label">淘宝旗舰店：</div>
          <div>
            <a href="https://shop393108277.taobao.com" target="_blank" style="color:#337ab7">
            成都果果佳&nbsp;<i class="fa fa-shopping-cart" aria-hidden="true"></i></a>
          </div>
      </div>

      <div class="item">
        <div class="label">官方微信：</div>
        <div>
          <img src="/img/qrcode.jpeg" style="width: 200px;border:1px solid #dedede;"></img>
        </div>
      </div>
    </div>

    <div class="card" style="margin: 1%;">
      <div class="card-header bg-white" style="color: #53a93f;border-radius: 0;border-bottom: 2px solid #92BC2C;">
        公司介绍
      </div>

      <div class="item" style="margin-top: 1%;">
          111111111111
      </div>
    </div>
</div>
