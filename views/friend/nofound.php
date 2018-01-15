<?php
use yii\web\View;
use app\components\MsaView;

$this->title = '好友详情';

MsaView::registerJsFile($this,'/js/friend/index.js', 
    ['position' => View::POS_END, 
        'depends' => [
            'app\assets\SiteAsset',
        ]
    ]
);

?>

<style type="text/css">
  footer {
    display: none;
  }

  .card {
      border-radius: 0;
      border: none;
      border-bottom: 1px solid #eee;
      border-top: 1px solid #eee;
  }

</style>

<div class="card" id="fail">
  <div class="card-header bg-white" style="text-align:center;position:relative;color: black;border-radius: 0;border-bottom: 1px solid #f5f5f5;">
        好友购物详情
  </div>
  <div style="display: -webkit-flex;flex-direction: -webkit-row;justify-content: -webkit-center;align-items: -webkit-center;display: flex;flex-direction: row;justify-content: center;align-items: center;padding: 10px;margin:10px;">
    <img src="/img/cry.png" />
    <div style="padding-left:10px;width:70%">没有查找到你感兴趣的信息，请检查访问链接</div>
  </div>

  <a href="/customer" class="btn btn-outline-success btn-sm" style="width:66%;margin-left:17%;margin-top:10px;">登录系统</a>
  <br/>
</div>


