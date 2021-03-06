<?php
use yii\web\View;
use app\components\MsaView;
use app\assets\ClipAsset;

$this->title = '订购详情';

MsaView::registerJsFile($this,'/js/order/index.js?v=2', 
    ['position' => View::POS_END, 
        'depends' => [
            'app\assets\SiteAsset',
        ]
    ]
);

ClipAsset::register($this);

?>

<style type="text/css">
  footer {
    display: none;
  }

  .item {
    margin-bottom: 5px;
    font-size: 14px;
    padding: 0.5rem
  }

  .item button {
    margin-left: 8px;
  }

  .label {
    width: 70px;
  }

  p {
    margin: 0;
  }

  .container {
    border: 1px solid #ced4da;
    margin: 10px auto;
  }

  #info {
    padding: 5px;
  }

  #detail, #express_info {
    position: fixed;
    z-index: 100;
    bottom: 0;
    width: 100%;
    border-radius: 0;
    display: none;
    height: 60%;
    overflow-y: scroll;
  }

  #express_info {
    height: 80%;
  }

  .status-header {
    padding: 0 8px !important;
    background-color: #fff;
  }

  .status-item {
    display: inline-block;
    font-size: 14px;
    height: 45px;
    line-height: 45px;
    padding-bottom: 5px;
    padding-left: 4px;
    padding-right: 4px;
    margin-left: 2px;
    margin-right: 4px;
    cursor: pointer;
  }

  .status-item.active {
    border-bottom: 2px solid red;
  }

  #info .card {
    margin-bottom: 10px;
  }

  .card-header {
    padding: 0.65rem 1rem !important;
  }

/*  .forbid {
    overflow-y: hidden;
  }*/

  #express_main, #express_detail {
    padding: 2% 5%;
    font-size: 14px;
  }

  #express_main span {
    display: inline-block;
    width: 80px;
  }

  .step {
    margin-bottom: 15px;
  }

  #express_detail {
    height: 90%;
    overflow-y: scroll;
  }

  .step.active{
    color: red;
  }

  .step .label {
    font-size: 20px;
    width: 100%;
    /*text-align: center;*/
  }

  .step .label span{
    font-size: 16px;
  }

  .step-content {
    width: 90%;
  }

  #unknown {
    text-align: center;
    padding: 2%;
  }

</style>

<div class="status-header">
  <div class="status-item" data-type="" <?php if ($orderType == 0) { echo "id='first'";} ?>>全部</div>
  <div class="status-item" data-type="1" <?php if ($orderType == 1) { echo "id='first'";} ?>>待付款</div>
  <div class="status-item" data-type="2" <?php if ($orderType == 2) { echo "id='first'";} ?>>待收货</div>
  <div class="status-item" data-type="3" <?php if ($orderType == 3) { echo "id='first'";} ?>>已完成</div>
  <!-- <div class="status-item" data-type="4" <?php if ($orderType == 4) { echo "id='first'";} ?>>已退款</div> -->
  <div class="status-item" data-type="5" <?php if ($orderType == 5) { echo "id='first'";} ?>>已删除</div>
</div>

<div id="info">
  <?php foreach($data as $item) { ?>
  <div class="card order-item" data-type="<?=$item['status'] ?>">
    <div class="card-header bg-white" style="border-bottom: 1px solid #ced4da;position: relative;">
      <div style="display: inline-block;width:20%;">果果佳</div>
      <div style="font-size: 14px;color:#aaa;line-height: 20px;display: inline-block;position: absolute;right: 10px;">
        <span style="font-size: 12px;margin-right: 6px;padding:6px;color:red;">
          <?=$status[$item['status']] ?>
        </span>
        <?php if ($item['status'] == 5) { ?>
        <span data-id=<?=$item['id'] ?> class="del_forever" style="font-size: 20px;"><i class="fa fa-trash" aria-hidden="true"></i></span>
        <?php } ?>
      </div>
    </div>
    <div style="padding: 0.5rem 1rem;">
        <div class="item" style="display: table;">
          <p class="label" style="display: table-cell;">收件人：</p>
          <div style="display: table-cell;">
            <p><?=$item['rec_name'] ?>&nbsp;&nbsp;<?=$item['rec_phone'] ?></p>
            <p><?=$item['rec_address'] ?></p>
          </div>
        </div>
        <div class="item" style="display: table;">
          <p class="label" style="display: table-cell;">下单时间：</p>
          <p style="display: table-cell;"><?=$item['create_time'] ?></p>
        </div>
        
        <?php if (!empty($item['memo'])) { ?>
        <div class="item">
          <p class="label">备注：</p>
          <p><?=$item['memo'] ?></p>
        </div>
        <?php } ?>

        <div class="show_gifts">
          <?php if (!empty($item['gifts'])) { ?>
            <?php foreach($item['gifts'] as $v) { ?>
            <div class="item" style="display: table;">
              <p style="display: table-cell;font-size: 16px;">
                <i class="fa fa-gift" aria-hidden="true" style="color:red;font-size: 20px;"></i>
                <?=$v['name'] ?>
              </p>
            </div>
            <?php } ?> 
          <?php } ?>
        </div>

        <div style="font-size:14px;margin-top:12px;display: table;text-align: right;width: 100%">
          <p style="display: table-cell;text-align: left;">
            共<span class="text-danger"><?=$item['cart_num'] ?></span>件
          </p>
          <p style="display: table-cell;">
            商品额：<span class="text-danger"><?=$item['product_price'] ?></span>元
          </p>
          <?php if ($item['status'] == 2 || $item['status'] == 3) { ?>
          <p style="display: table-cell;">
            支付额：<span class="text-danger"><?=$item['pay_money'] ?></span>元
          </p>
          <?php } ?>
        </div>
    </div>

    <div class="item" style="border-top: 1px solid #ced4da;display: table-cell;text-align: right;">
        <?php if ($item['status'] == 1) { ?>
        <button type="button" class="btn btn-outline-warning btn-sm del" data-id="<?=$item['id'] ?>">删除</button>
        <button type="button" class="btn btn-outline-info btn-sm edit" data-type="<?=$item['order_type'] ?>" data-cid="<?=$item['cart_id'] ?>">修改订单</button>
        <button type="button" class="btn btn-outline-primary btn-sm show_detail" data-id="<?=$item['id'] ?>" data-cid="<?=$item['cart_id'] ?>">商品详情</button>
        <button type="button" class="btn btn-outline-danger btn-sm pay" data-type="<?=$item['order_type'] ?>" data-cid="<?=$item['cart_id'] ?>">去支付</button>
        <?php } ?>

        <?php if ($item['status'] == 2) { ?>
        <button type="button" class="btn btn-outline-primary btn-sm show_detail" data-id="<?=$item['id'] ?>" data-cid="<?=$item['cart_id'] ?>">商品详情</button>
        <button type="button" class="btn btn-outline-danger btn-sm express" data-id="<?=$item['id'] ?>" data-express-num="<?=$item['express_num'] ?>">查看物流</button>
        <button type="button" class="btn btn-outline-success btn-sm ok" data-id="<?=$item['id'] ?>">确认收货</button>
        <?php } ?>

        <?php if ($item['status'] == 3) { ?>
        <button type="button" class="btn btn-outline-primary btn-sm show_detail" data-id="<?=$item['id'] ?>" data-cid="<?=$item['cart_id'] ?>">商品详情</button>
        <button type="button" class="btn btn-outline-danger btn-sm edit" data-type="<?=$item['order_type'] ?>" data-cid="<?=$item['cart_id'] ?>">再次购买</button>
        <?php } ?>

        <?php if ($item['status'] == 4) { ?>
        <button type="button" class="btn btn-outline-primary btn-sm show_detail" data-id="<?=$item['id'] ?>" data-cid="<?=$item['cart_id'] ?>">商品详情</button>
        <button type="button" class="btn btn-outline-danger btn-sm edit" data-type="<?=$item['order_type'] ?>" data-cid="<?=$item['cart_id'] ?>">再次购买</button>
        <?php } ?>

        <?php if ($item['status'] == 5) { ?>
        <button type="button" class="btn btn-outline-primary btn-sm show_detail" data-id="<?=$item['id'] ?>" data-cid="<?=$item['cart_id'] ?>">商品详情</button>
        <button type="button" class="btn btn-outline-danger btn-sm edit" data-type="<?=$item['order_type'] ?>" data-cid="<?=$item['cart_id'] ?>">重新购买</button>
        <?php } ?>
    </div>
  </div>
  <?php } ?>
  <div style="text-align: center;color: #ccc;font-size: 14px;margin-top: 10px;">没有更多订单了</div>
</div>

<div class="card" id="detail">
  <div class="card-header bg-white" style="color: #1ba93b;border-radius: 0;border-bottom: 1px solid #92BC2C;">
      商品详情
      <i class="fa fa-times" aria-hidden="true" style="cursor: pointer;position: absolute;right:15px;" id="close_detail"></i>
  </div>
  <table class="table table-bordered" style="width: 98%;margin: 1% auto;font-size: 14px;">
    <thead>
      <tr>
        <th scope="col">商品</th>
        <th scope="col">描述</th>
        <th scope="col">数量</th>
      </tr>
    </thead>
    <tbody id="table-content">

    </tbody>
  </table>

  <div id="inner-gifts"></div>
</div>


<div class="card" id="express_info">
  <div class="card-header bg-white" style="color: #1ba93b;border-radius: 0;border-bottom: 1px solid #92BC2C;">
      物流信息
      <i class="fa fa-times" aria-hidden="true" style="cursor: pointer;position: absolute;right:15px;" id="close_express"></i>
  </div>
  <!-- <input type='hidden' value='' id='express_copy_num' /> -->
  <div id="express_info_content" style="height: 80%;">
    <div id="unknown">查询中，请稍候...</div>
  </div>
</div>
