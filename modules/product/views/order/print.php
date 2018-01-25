<?php 
use yii\web\View;
use app\components\MsaView;

$this->title = '打印订单';

MsaView::registerJsFile($this,'/js/product/order/print.js', 
    [
        'position' => View::POS_END, 
        'depends' => [
            'app\assets\PrintAsset',
            'app\assets\GridAsset',
        ]
    ]
);

?>

<style type="text/css">
    .item {
        display: inline-block;
        width: 40%;
        font-size: 20px;
        line-height: 24px;
    }

    label {
        width: 70px;
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

<div class='row' style="background-color: white;padding: 3%;" id="print">
    <?php if ($info['order_type'] == 1) { ?>
    <h1>果果佳商城现售订单</h1>
    <?php } else { ?>
    <h1>果果佳商城预约订单</h1>
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

<!--         <div class="item">
            <label>商品价格：</label><span><?=$info['product_price'] ?></span>
        </div> -->

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
              <th scope="col">描述</th>
              <th scope="col">数量</th>
              <!-- <th scope="col">总价</th> -->
            </tr>
            </thead>
            <tbody>
            <?php foreach($info['product'] as $item) { ?>
              <tr>
                <td><?=$item['name'] ?></td>
                <td><?=$item['desc'] ?></td>
                <td><?=$info['product_cart'][$item['id']]['num'] ?><?=$item['unit'] ?></td>
                <!-- <td><?=$info['product_cart'][$item['id']]['price'] * $info['product_cart'][$item['id']]['num'] ?>元</td> -->
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
    <br/>
    <div id="contact" style="display: flex;flex-direction: row;align-items: center;justify-content: center;">
        <img src="/img/company.jpg" style="width: 180px;"></img>
        <div style="font-size: 24px;">
            <label>客服电话：</label>18980457783（同微信号）
            <br/>
            <label>老板电话：</label>13880494109（同微信号）
        </div>
    </div>
</div>

<button class="btn btn-success" style="width:30%;margin-left: 45%;margin-top: 5%;" id="start">打印订单</button>
