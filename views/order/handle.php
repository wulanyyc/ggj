<?php 
use yii\web\View;
use app\components\MsaView;

$this->title = '备货';

?>

<style type="text/css">
    #detail {
        margin: 1%;
    }

    .item {
        display: block;
        width: 100%;
        font-size: 20px;
        line-height: 24px;
    }

    label {
        width: 80px;
    }

    .item.address {
        width: 80%;
    }

    #info {
        border: 1px dashed #333;
        padding: 1%;
    }

    #product {
        font-size: 16px;
    }
</style>

<div class="card" id="detail">
  <div style="width:94%;margin: 10px auto;text-align: center;">
    <?php if ($info['order_type'] == 1) { ?>
    <h1>现售订单</h1>
    <?php } else { ?>
    <h1>预约订单</h1>
    <?php } ?>
    <div id="info">
        <div class="item">
            <label>收件人：</label><span><?=$info['rec_name'] ?></span>
        </div>

        <div class="item">
            <label>手机号码：</label><span><?=$info['rec_phone'] ?></span>
        </div>

        <div class="item address">
            <label>地址：</label><span><?=$info['rec_address'] ?></span>
        </div>

        <div class="item">
            <label>运输方式：</label><span><?=$info['express_rule'] ?></span>
        </div>

        <?php if (!empty($info['memo'])) { ?>
        <div class="item">
            <label>备注：</label><span><?=$info['memo'] ?></span>
        </div>
        <?php } ?>

        <div class="item">
            <label>下单ID：</label><span><?=$info['id'] ?></span>
        </div>

        <div class="item">
            <label>商品价格：</label><span><?=$info['product_price'] ?></span>
        </div>

        <div class="item">
            <label>快递费：</label><span><?=$info['express_fee'] ?></span>
        </div>

        <div class="item">
            <label>优惠券：</label><span><?=$info['coupon_fee'] ?></span>
        </div>

        <div class="item">
            <label>支付金额：</label><span><?=$info['pay_money'] ?></span>
        </div>

        <div class="item">
            <label>下单时间：</label><span><?=$info['create_time'] ?></span>
        </div>

    </div>

    <br/>

    <div id="product">
        <table class="table table-bordered">
            <thead>
            <tr>
              <th scope="col">商品</th>
              <th scope="col">数量</th>
              <th scope="col">描述</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($info['product'] as $item) { ?>
              <tr>
                <td><?=$item['name'] ?></td>
                <td><?=$info['product_cart'][$item['id']]['num'] ?><?=$item['unit'] ?></td>
                <td><?=$item['desc'] ?></td>
              </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
    <br/>
    <?php if (!empty($info['gifts'])) { ?>
    <div id="gift">
        <table class="table table-bordered">
            <thead>
            <tr>
              <th scope="col">礼品</th>
              <th scope="col">描述</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($info['gifts'] as $item) { ?>
              <tr>
                <td><?=$item['name'] ?></td>
                <td><?=$item['useflag'] ?></td>
              </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
    <?php } ?>
  </div>
</div>
