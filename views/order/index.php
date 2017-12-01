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

  #detail {
    position: absolute;
    z-index: 100;
    bottom: 0;
    width: 100%;
    border-radius: 0;
    display: none;
    height: 60%;
    overflow-y: scroll;
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
        <div class="item">
          <p class="label">特殊要求：</p>
          <p><?=$item['memo'] ?></p>
        </div>
        <div class="item">
          <button type="button" class="btn btn-success detail" data-id="<?=$item['id'] ?>" data-cart='<?=$item["cart"] ?>'>查看详情</button>
        </div>
      </div>
    <?php } ?>
  </div>
</div>

<div class="card" id="detail">
  <div class="card-header bg-white" style="color: #53a93f;border-radius: 0;border-bottom: 1px solid #92BC2C;display: flex;flex-direction: row;justify-content: space-between;">
      订单详情<i class="fa fa-times" aria-hidden="true" style="cursor: pointer;" id="close_detail"></i>
  </div>
  <table class="table table-bordered" style="width: 98%;margin: 1% auto;font-size: 14px;">
    <thead>
      <tr>
        <th scope="col">商品</th>
        <th scope="col">数量</th>
        <th scope="col">单价</th>
      </tr>
    </thead>
    <tbody id="table-content">

    </tbody>
  </table>
</div>

