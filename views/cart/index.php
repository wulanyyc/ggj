<?php
use yii\web\View;
use app\components\MsaView;

$this->title = '购物车';

MsaView::registerJsFile($this,'/js/cart/index.js', 
    ['position' => View::POS_END, 
        'depends' => [
            'app\assets\SiteAsset',
        ]
    ]
);

?>

<style type="text/css">
  .card {
    border-radius: 0;
    border: none;
    border-bottom: 1px solid #eee;
    border-top: 1px solid #eee;
    margin: 2px 0%;
  }

  #fee .item {
    margin-bottom: 4px;
    font-size: 16px;
    width: 100%;
    position: relative;
  }

  #fee .item .label {
    width: 90px;
    display: inline-block;
  }

  #fee .price {
    color:red;position: absolute;right:5px;display: inline-block;
  }

  p {
    margin: 0;
  }

  #question, #detail, #address_info, #all_address_info, #coupon, #express_info {
    position: fixed;
    z-index: 100;
    bottom: 0;
    width: 100%;
    border-radius: 0;
    
    display: none;
  }

  #question {
    height: 60%;
  }

  #detail {
    height: 72%;
  }

  #coupon {
    height: 80%;
  }

  #express_info {
    height: 72%;
  }

  footer {
    display: none;
  }

  #tongji {
    position: fixed;
    height: 55px;
    line-height: 55px;
    width: 100%;
    bottom: 0px;
    background-color: #fff;
    z-index: 10;
    border-top: 1px solid #f5f5f5;
    display: table-cell;
    text-align: right;
  }

  .card-content {
    padding: 3px 15px;
  }

  .forbid {
    overflow-y: hidden;
  }

  .no_address, .show_address, .all_address_item {
    height: 100%;
  }

  .show_address {
    margin: 10px 0px;
  }

  .address {

  }

  .all_address_item {
    padding: 2% 3%; 
    border-bottom: 1px solid #eee;
    height:auto;
    display: table;width: 100%;
  }

  #address_info, #all_address_info {
    height: 85%;
    overflow-y: scroll;
  }

  #address_form label {
    width: 25%;
  }

  #address_form input {
    width: 72%;
  }

  .edit_address_item, .del_address_item {
    text-align: center;
    width: 10%;display: table-cell;vertical-align: middle;
  }

  .hide_address {
    display: none;
  }

  .address-status {
    width: 10%;display: table-cell;vertical-align: middle;
  }

  .address-content {
    width: 72%;display: table-cell;vertical-align: middle;
  }

  .address-content-title {
    font-weight: bold;color:#333;
  }

  .address-status.active {
    color: red;
    font-size: 18px;
  }

  .label_choose {
    border: 1px solid #ccc;
    text-align: center;
    font-size: 14px;
    padding: 5px;
    width: 30%;
    display: inline-block;
    margin-right: 5px;
    margin-bottom: 5px;
  }

  .label_choose.active {
    border: 1px solid red;
    color: red;
  }

  #label_add {
    border: 1px dashed #ccc;
    text-align: center;
    font-size: 14px;
    padding: 5px;
    width: 80px;
    display: inline-block;
    margin-right: 5px;
    margin-bottom: 5px;
  }

  #show_label {
    font-size: 14px;padding:2px 10px;
  }

  #coupon_items {
    width: 100%;
    padding:2%;
    height: 80%;
    overflow-y: scroll;
  }

  .coupon_item {
    margin-bottom: 2%;
    display: table;
    width: 100%;
  }

  .coupon_item_content {
    display: table-cell;
    position: relative;
    width: 85%;
    vertical-align: middle;
  }

  .coupon_item_content_img {
    width: 100%;
    z-index: 1;
  }

  .coupon_item_label {
    font-size: 24px;
    line-height: 28px;
    width: 60%;
    position: absolute;
    left: 20%;
    top: 50%;
    overflow-x: hidden;
    text-align: center;
    color: #1ba93b;
  }

  .coupon_item_text {
    position: absolute;
    width: 100%;
    height: 100%;
    text-align: center;
    z-index: 2;
    top: 0;
  }

  .coupon_item_money {
    text-align: center;
    font-size: 24px;
    position: absolute;
    top: 20%;
    left: 44%;
    color: #1ba93b;
  }


  .coupon_check {
    width: 10%;
    font-size: 22px;
    text-align: center;
    display: table-cell;
    vertical-align: middle;
  }

  .coupon_item_date {
    position: absolute;
    text-align: center;
    width: 100%;
    bottom: 5%;
    font-size: 12px;
    letter-spacing: 1px;
    color: #89b0da;
  }

  #address_form .form-group {
    margin-top:10px;
  }

  .show_address {
    width: 100%;
    display: table;
  }

  .right-arrow {
    width: 18%;
    display: table-cell;
    vertical-align: middle;
    text-align: right;
  }

  .left-content {
    display: table-cell;
    vertical-align: middle;
    text-align: left;
  }

  .icon {
    display: inline-block;
    width: 15px;
  }

  #coupon_detail {
    font-size: 13px;
    cursor: pointer;
    width: 28%;
    position: absolute;
    right: 0px;
    text-align: right;
    top: 0px;
    /*letter-spacing: 1px;*/
  }
</style>

<input type="hidden" name="order_type" id="order_type" value="<?=$data['order_type'] ?>" />
<input type="hidden" name="cart_id" id="cart_id" value="<?=$data['id'] ?>" />
<input type="hidden" name="history_express_rule" id="history_express_rule" value="<?=$data['express_rule'] ?>" />
<input type="hidden" name="express_fee" id="express_fee" value="<?=$data['express_fee'] ?>" />
<input type="hidden" name="cart_num" id="cart_num" value="<?=count($data['product']) ?>" />
<input type="hidden" name="std_express_fee" id="std_express_fee" value="<?=$stdExpressFee ?>" />
<input type="hidden" name="buy_god" id="buy_god" value="<?=$buyGod ?>" />
<input type="hidden" name="history_product_price" id="history_product_price" value="<?=$data['product_price'] ?>" />

<!-- address start -->
<div class="card">
  <div class="card-content" id="show_address_content">
      <?php if (empty($address)) { ?>
      <div class="no_address" style="text-align: center;height:60px;position: relative;">
        <button id="add_address" type="button" class="btn btn-outline-danger" style="width:50%;font-size: 16px;margin: 10px auto;"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;添加收货地址</button>
      </div>

      <div class="show_address" data-id="" style="display: none;">
        <div class="left-content">
          <div style="display: table-cell;font-size: 30px;width:30px;text-align: center;color:#28a745;vertical-align: middle;">
            <span><i class="fa fa-map-marker" aria-hidden="true"></i>&nbsp;</span>
          </div>

          <div style="display: table-cell;vertical-align: top;">
            <div style="font-weight: bold;color:#333;">
              <span id="show_rec_name"></span>
              <span id="show_rec_phone" style="padding-left: 5px;"></span>&nbsp;
              <span id="show_label" class="border border-success text-success"></span>
            </div>
            <div style="color:#a3a3a3;font-size: 16px;">
              <span id="show_address"></span>
            </div>
          </div>
        </div>

        <div class="right-arrow" style="width: 5%;">
          <i class="fa fa-chevron-right" aria-hidden="true" style="color:#ccc;"></i>
        </div>
      </div>
      <?php } else { ?>
      <div class="show_address" data-id="<?=$address[0]['id'] ?>">
        <div class="left-content">
          <div style="display: table-cell;font-size: 30px;width:30px;text-align: center;color:#28a745;vertical-align: middle;">
            <span><i class="fa fa-map-marker" aria-hidden="true"></i>&nbsp;</span>
          </div>

          <div style="display: table-cell;vertical-align: top;">
            <div style="font-weight: bold;color:#333;">
              <span id="show_rec_name"><?=$address[0]['rec_name'] ?></span>
              <span id="show_rec_phone" style="padding-left: 5px;"><?=$address[0]['rec_phone'] ?></span>&nbsp;
              <span id="show_label" class="border border-success text-success"><?=$address[0]['label'] ?></span>
            </div>
            <div style="color:#a3a3a3;font-size: 16px;">
              <span id="show_address"><?=$address[0]['rec_city'] ?><?=$address[0]['rec_district'] ?><?=$address[0]['rec_detail'] ?></span>
            </div>
          </div>
        </div>

        <div class="right-arrow" style="width:5%;">
          <i class="fa fa-chevron-right" aria-hidden="true" style="color:#ccc;"></i>
        </div>
      </div>

      <div class="no_address" style="display: none;">
        <br/>
        <button id="add_address" type="button" class="btn btn-outline-danger btn-sm" style="width:140px;margin-bottom: 10px;font-size: 16px;height:30px;margin-top: 10px;"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;添加收货地址</button>
      </div>
      <?php } ?>
    </div>
  </div>
</div>

<!-- product start -->
<div class="card">
  <div id="products" class="card-content" style="width: 100%;">
    <div id="product_detail" style="width: 100%;">
      <div style="position: relative;overflow: hidden;height:70px;display: inline-block;width:80%;">
        <?php foreach($data['show_product'] as $item) { ?>
        <div class="product" style="height:70px;display: table-cell;vertical-align: middle;">
          <img src="<?=$item['img'] ?>" style="height:80%;padding: 5%;" />
        </div>
        <?php } ?>
      </div>

      <div style="height: 70px;line-height: 70px;text-align: right;width:18%;display: inline-block;position: absolute;right:16px;">
        <span style="font-size: 13px;cursor: pointer;text-align: right;">共<?=count($data['product']) ?>件&nbsp;&nbsp;
          <i class="fa fa-chevron-right" aria-hidden="true" style="color:#ccc;"></i>
        </span>
      </div>
    </div>

    <hr style="margin-top:0px;margin-bottom: 3px;" />
    <div class="item" style="width: 100%;height: 40px;line-height: 40px;position: relative;">
        <div style="display: inline-block;width:90%;">
          <p class="label" style="display: inline-block;">配送：</p>
          <div style="display: inline-block;margin-right: 5px;" data-id="1" class="express_rule" id="express_rule_1">
            <div class="icon" data-id="1"><i class="fa fa-check-square-o" aria-hidden="true"></i></div>
            <span class="badge badge-success" style="font-size: 13px;line-height: 16px;font-weight: normal;">顺丰快递</span>
          </div>

          <div style="display: inline-block;" data-id="2" class="express_rule" id="express_rule_2">
            <span class="icon" data-id="2"><i class="fa fa-square-o" aria-hidden="true"></i></span>
            <span class="badge badge-info" style="font-size: 13px;line-height: 16px;font-weight: normal;">自提</span>
          </div>
        </div>

        <div id="express_time" style="width: 18%;position: absolute;right: 0px;top: 0px;text-align: right;">
          <span style="font-size: 13px;cursor: pointer;">发货
            <i class="fa fa-chevron-right" aria-hidden="true" style="color:#ccc;"></i>
          </span>
        </div>
    </div>
  </div>
</div>

<div class="card">
  <div id="discount" class="card-content">
<!--     <div style="margin-top:5px;display: table;">
      <div class="left-content">
        <input type="text" class="form-control input-sm" name="code" id="code" placeholder="好友的手机号码" maxlength=11 style="width: 128px;font-size: 14px;height: 40px;display: inline-block;" />
        <button data-id="<?=$data['id'] ?>" type="button" id="use_discount" class="btn btn-outline-success btn-sm" style="height:30px;margin-left:5px;display: inline-block;">优惠码</button>
      </div>
      <div id="ask" class="right-arrow" style="color:red;cursor: pointer;font-size: 13px;width:20%;">减<?=$discount_start ?>%-<?=$discount_end ?>% <i class="fa fa-chevron-right" aria-hidden="true" style="color:#ccc;"></i></div>
    </div>
    <hr style="margin-top: 8px;margin-bottom: 8px;"/> -->
    <div style="margin-bottom:3px;display: table;height: 40px;position: relative;width: 100%;line-height: 40px;" id="choose_coupon">
      <div class="label" style="width:68%;display: inline-block;">优惠券</div>
      <div id="coupon_detail">
        <?php if ($coupon > 0) { ?>
        可用<span class="text-danger" style="font-weight: bold;font-size: 14px;"><?=$coupon ?></span>张&nbsp;&nbsp;&nbsp;<i class="fa fa-chevron-right" aria-hidden="true" style="color:#ccc;"></i>
        <?php } else { ?>
        可用<span class="text-danger">0</span>张&nbsp;&nbsp;&nbsp;<i class="fa fa-chevron-right" aria-hidden="true" style="color:#ccc;"></i>
        <?php } ?>
      </div>
    </div>
  </div>
</div>

<div class="card">
  <div id="fee" class="card-content">
    <div class="item">
        <p class="label">商品金额：</p>
        <p class="price">¥<span id="product_price"><?=$data['product_price'] ?></span></p>
    </div>
    <div class="item">
        <p class="label">运费：</p>
        <p class="price">+ ¥<span id="express_fee_show"><?=$data['express_fee'] ?></span></p>
    </div>
    <div class="item">
        <p class="label">优惠码：</p>
        <p class="price">- ¥<span id="discount_fee">0</span></p>
    </div>
    <div class="item">
        <p class="label">优惠券：</p>
        <p class="price">- ¥<span id="coupon_fee">0</span></p>
    </div>
  </div>
</div>

<div class="card">
  <div id="memo" class="card-content">
    <input type='text' placeholder='选填:给商家留言(45字以内)' name='memo' id="memo" class='input-sm' maxlength=45 style="width:100%;border:none;line-height: 35px;"></input>
  </div>
</div>
<br/>
<br/>
<br/>

<div id="tongji">
  <div id="tips" style="color:red;margin-left: 5px;margin-right: 2px;font-size: 14px;display: inline-block;">实付款:</div>
  <div id="total" style="margin-left:5px;margin-right: 5px;opacity: 1;display: inline-block;">
    <div style="display: inline-block;color:red;width:70px;text-align: left;">
      <span class="money" style="font-size: 16px;font-weight: normal;">¥</span>
      <span class="realprice" style="font-size:20px;font-weight: normal;" id="realprice"></span>
    </div>
  </div>
  <div class='btn btn-outline-secondary btn-sm' id='edit' style="margin-right: 8px;display: inline-block;"><i class="fa fa-cart-plus" aria-hidden="true"></i>&nbsp;购物车</div>
  <div class='btn btn-success' id='order' style="margin-right: 10px;display: inline-block;">去下单</div>
</div>

<div class="card" id="question">
  <div class="card-header bg-white" style="color: #1ba93b;border-radius: 0;border-bottom: 1px solid #92BC2C;">
      优惠码
      <i class="fa fa-times" aria-hidden="true" style="cursor: pointer;position: absolute;right: 10px;" id="close_question"></i>
  </div>
  <div style="padding: 5%;">
    <p style="text-align: center;">
      <span>好友的手机号码即为优惠码</span>
    </p>
    <div class='btn btn-success btn-sm' id='close_question_bottom' style="width:40%;margin-left:30%;position: absolute;bottom: 20px;">关闭</div>
  </div>
</div>

<div class="card" id="detail">
  <div class="card-header bg-white" style="color: #1ba93b;border-radius: 0;border-bottom: 1px solid #92BC2C;">
      商品详情<i class="fa fa-times" aria-hidden="true" style="cursor: pointer;position: absolute;right:15px;" id="close_detail"></i>
  </div>
  <div style="margin-top: 10px;font-size: 14px;width:98%;margin:1% auto;height: 72%;overflow-y: scroll;">
    <table class="table table-bordered">
      <thead>
        <tr>
          <th scope="col">商品</th>
          <th scope="col">描述</th>
          <th scope="col">数量</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($data['product'] as $item) { ?>
          <tr>
            <td><?=$item['name'] ?></td>
            <td><?=$item['desc'] ?></td>
            <td><?=$data['product_cart'][$item['id']]['num'] ?><?=$item['unit'] ?></td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
  <div class='btn btn-success btn-sm' id='close_detail_bottom' style="width:40%;margin-left:30%;margin-top: 3%;">关闭</div>
</div>

<div class="card" id="all_address_info">
    <div class="card-header bg-white" style="color: #1ba93b;border-radius: 0;border-bottom: 1px solid #92BC2C;">
      收货地址<i class="fa fa-times" aria-hidden="true" style="cursor: pointer;position: absolute;right:15px;" id="close_all_address"></i>
    </div>

    <div style="height:80%;overflow-y: scroll;" id="all_address_items">
      <?php foreach($address as $item) { ?>
      <div class="all_address_item">
          <div class="address-status" data-id="<?=$item['id'] ?>">
            <i class="fa fa-check-square-o" aria-hidden="true"></i>
          </div>
          <div class="address-content" data-id="<?=$item['id'] ?>">
            <p class="address-content-title">
              <?=$item['rec_name'] ?>
              <span id="cipher_phone" style="padding-left: 5px;"><?=$item['rec_phone'] ?></span>&nbsp;
              <span class="border border-success text-success" style="font-size: 14px;padding:0px 10px;"><?=$item['label'] ?></span>
            </p>
            <p style="color:#a3a3a3;" class="address-content-desc">
              <span><i class="fa fa-map-marker" aria-hidden="true"></i></span>
              <span style="font-size: 16px;">&nbsp;<?=$item['rec_city'] ?><?=$item['rec_district'] ?><?=$item['rec_detail'] ?></span>
            </p>
          </div>

          <div data-id="<?=$item['id'] ?>" class="edit_address_item">
            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
          </div>

          <div data-id="<?=$item['id'] ?>" class="del_address_item">
            <i class="fa fa-trash-o" aria-hidden="true"></i>
          </div>
      </div>
      <?php } ?>
    </div>

    <button id="inner_add_address" type="button" class="btn btn-danger btn-sm" style="width:40%;margin-left:25%;font-size: 14px;"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;添加地址并使用</button>
    <span id='close_all_address_bottom' style="padding-left: 22px;font-size: 16px;color:#0C58B0;">关闭</span>
</div>

<div class="card" id="address_info">
  <div class="card-header bg-white" style="color: #1ba93b;border-radius: 0;border-bottom: 1px solid #92BC2C;">
      收货地址<i class="fa fa-times" aria-hidden="true" style="cursor: pointer;position: absolute;right: 15px;" id="close_address"></i>
  </div>
  <form id="address_form" name='address_form' style='margin:5%;'>
    <input type="hidden" name="id" value='' id="edit_address_id" />
    <div class='form-group' style="display: table;width: 100%;margin-bottom: 5px;">
        <label style="width:30%;display: table-cell;">收货人：</label>
        <input style="display: table-cell;" type='text' placeholder='' name='rec_name' class='form-control input-sm' id='rec_name' maxlength=45 value='' />
    </div>
    <div class='form-group' style="display: table;width: 100%;margin-bottom: 5px;">
        <label style="width:30%;display: table-cell;">手机号码：</label>
        <input style="display: table-cell;" type='text' placeholder='' name='rec_phone' class='form-control input-sm' id='phone' size=11 value='' />
    </div>
    <div class='form-group' style="display: table;width: 100%;margin-bottom: 5px;">
        <label style="width:30%;display: table-cell;">所在城市：</label>
        <select class="form-control" style="display: table-cell;" name="rec_city" id="rec_city">
          <option value="成都市">成都市</option>
        </select>
    </div>
    <div class='form-group' style="display: table;width: 100%;margin-bottom: 5px;">
        <label style="width:30%;display: table-cell;">所在地区：</label>
        <select class="form-control" style="display: table-cell;" name="rec_district" id="rec_district">
        <?php foreach($citymap as $item) { ?>
          <option value="<?=$item ?>"><?=$item ?></option>
        <?php } ?>
        </select>
    </div>
    <div class='form-group' style="display: table;width: 100%;margin-bottom: 5px;">
        <label style="width:30%;display: table-cell;">详细地址：</label>
        <textarea class="form-control" style="display: table-cell;" placeholder='街道、楼牌号, 限100字' maxlength=100 name='rec_detail' id='rec_detail' class='input-sm'></textarea>
    </div>
    <div class='form-group' style='margin-top:5px;padding-bottom:5px;display: table;width: 100%;' id="label_add_group">
        <label style="width:30%;display: table-cell;">标签：</label>
        <div style="display: table-cell;">
          <div class="label_choose active">家</div>
          <div class="label_choose">公司</div>
          <div class="label_choose">学校</div>
          <div class="label_choose">其它</div>
          <div id="label_add">+</div>
          <div id="label_add_input" style="width:95%;display: none;">
            <div class="input-group">
              <input type="text" class="form-control" maxlength=5 style="font-size: 14px;" placeholder="请输入标签名字,最多5个字" id="label_add_text" value="" />
              <span class="input-group-btn">
                <button class="btn btn-secondary" type="button" style="font-size: 14px;" id="label_add_input_ok">确定</button>
              </span>
            </div>
          </div>
        </div>
    </div>

    <div style="width: 100%;">
      <button type="button" class="btn btn-success btn-sm" id="save_address" style="width:140px;margin-left:30%;">保存并使用</button>
      <span id='close_address_bottom' style="padding-left: 25px;font-size: 16px;color:#0C58B0;line-height: 31px;">关闭</span>
    </div>
  </form>
</div>

<div class="card" id="coupon">
  <div class="card-header bg-white" style="color: #1ba93b;border-radius: 0;border-bottom: 1px solid #92BC2C;">
      优惠券
      <i class="fa fa-times" aria-hidden="true" style="cursor: pointer;position: absolute;right:15px;" id="close_coupon"></i>
  </div>
  <div id="coupon_items" data-ids="">
  </div>
  <button id="ok_coupon" type="button" class="btn btn-success btn-sm" style="position: absolute;bottom:3%;width:40%;left:30%;">选中并使用</button>
  <span id='close_coupon_bottom' style="position: absolute;bottom:4%;left:78%;font-size: 14px;color:#0C58B0;">关闭</span>
</div>


<div class="card" id="express_info">
  <div class="card-header bg-white" style="color: #1ba93b;border-radius: 0;border-bottom: 1px solid #92BC2C;">
      发货时间<i class="fa fa-times" aria-hidden="true" style="cursor: pointer;position: absolute;right: 15px;" id="close_express_info"></i>
  </div>
  <div style="padding: 5%;">
    <p>
      <span style="padding-right: 5px;">普通订单: </span>
      <span style="width:72%;">24小时内发货</span>
    </p>

    <p>
      <span style="padding-right: 5px;">预约订单: </span>
      <span style="width:72%;">每周<?=Yii::$app->params['bookingSender'] ?>上午发货</span>
    </p>

    <p>
      <span style="padding-right: 5px;">特殊要求: </span>
      <span style="width:72%;">请留言或直接联系我们</span>
    </p>

    <div class='btn btn-success btn-sm' id='close_express_info_bottom' style="width:40%;margin-left:30%;position: absolute;bottom: 20px;">关闭</div>
  </div>
</div>
