<?php
use yii\web\View;
use app\components\MsaView;
use app\assets\HashAsset;

$this->title = '订购详情';

MsaView::registerJsFile($this,'/js/order/index.js', 
    ['position' => View::POS_END, 
        'depends' => [
            'app\assets\SiteAsset',
        ]
    ]
);

?>

<style type="text/css">
  .item {
    display: flex;
    flex-direction: row;
    justify-content: flex-start;
    margin-bottom: 5px;
    font-size: 14px;
  }

  .label {
    width: 80px;
  }

  p {
    margin: 0;
  }

  .container {
    border: 1px solid #ced4da;
    border-radius: 5px;
    margin: 10px auto;
    padding: 10px;
  }

  #info {
    padding: 5px;
  }
</style>

<div class="card" style="margin: 1%;">
  <div class="card-header bg-white" style="color: #53a93f;border-radius: 0;border-bottom: 2px solid #92BC2C;">
    订单列表
  </div>

  <div id="info">
    <?php foreach($data as $item) { ?>
      <div class="container">
        <div class="item">
          <p class="label">联系人：</p>
          <p><?=$item['username'] ?></p>
        </div>
        <div class="item">
          <p class="label">手机号码：</p>
          <p><?=$item['cellphone'] ?></p>
        </div>
        <div class="item">
          <p class="label">地址：</p>
          <p><?=$item['address'] ?></p>
        </div>
        <div class="item">
          <p class="label">总金额：</p>
          <p><?=$item['money'] ?></p>
        </div>
      </div>
    <?php } ?>
  </div>
</div>

