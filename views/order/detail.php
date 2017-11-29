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
</style>

<div>
    <div class="card" style="margin: 1%;">
      <div class="card-header bg-white" style="color: #53a93f;border-radius: 0;border-bottom: 2px solid #92BC2C;">
        订单明细
      </div>

      <div id="info" style="margin-top: 10px;padding: 0px 20px;">
        <div id="userinfo">
          <div class="item">
            <p class="label">联系人：</p>
            <p><?=$data['username'] ?></p>
          </div>
          <div class="item">
            <p class="label">手机号码：</p>
            <p><?=$data['cellphone'] ?></p>
          </div>
          <div class="item">
            <p class="label">地址：</p>
            <p><?=$data['address'] ?></p>
          </div>
          <div class="item">
            <p class="label">总金额：</p>
            <p><?=$data['money'] ?></p>
          </div>
        </div>

        <table class="table table-bordered" style="margin-top: 10px;font-size: 14px;">
          <thead>
            <tr>
              <th scope="col">商品</th>
              <th scope="col">数量</th>
              <th scope="col">单价</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($data['product'] as $item) { ?>
              <tr>
                <td><?=$item['name'] ?></td>
                <td><?=$data['product_cart'][$item['id']]['num'] ?></td>
                <td><?=$data['product_cart'][$item['id']]['price'] ?>/<?=$item['unit'] ?></td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
    </div>
</div>
