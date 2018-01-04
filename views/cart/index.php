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

  .item {
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    margin-bottom: 4px;
    font-size: 15px;
  }

  .item .label {
    width: 90px;
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
    height: 44%;
  }

  #detail {
    height: 60%;
    overflow-y: scroll;
  }

  #coupon {
    height: 80%;
    /*overflow-y: scroll;*/
  }

  #express_info {
    height: 44%;
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
    display: flex;
    flex-direction: row;
    justify-content: flex-end;
    align-items: center;
    z-index: 10;
    border-top: 1px solid #f5f5f5;
  }

  .card-content {
    padding: 3px 15px;
  }

  .forbid {
    overflow-y: hidden;
  }

  .no_address, .show_address, .all_address_item {
    display: flex;
    flex-direction: row;
    justify-content: center;
    height: 100%;
    align-items: center;
  }

  .show_address {
    margin: 10px 0px;
  }

  .address {
    justify-content: flex-start;
  }

  .all_address_item {
    padding: 2% 3%; border-bottom: 1px solid #eee;height:auto;
  }

  #address_info, #all_address_info {
    height: 90%;
    overflow-y: scroll;
  }

  #address_form label {
    width: 25%;
  }

  #address_form input {
    width: 70%;
  }

  .edit_address_item, .del_address_item {
    text-align: center;
  }

  .hide_address {
    display: none;
  }

  .address-status.active {
    color: red;
    font-size: 18px;
  }

  .label_choose {
    border: 1px solid #ccc;
    text-align: center;
    font-size: 12px;
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
    font-size: 12px;
    padding: 5px;
    width: 80px;
    display: inline-block;
    margin-right: 5px;
    margin-bottom: 5px;
  }

  #show_label {
    font-size: 12px;padding:2px 10px;
  }

  #coupon_items {
    width: 100%;padding:2%;
  }

  .coupon_item {
    display: flex;flex-direction: row;justify-content: space-between;
    align-items: center;border: 1px dashed #ccc;padding: 2%;width:100%;
    margin-bottom: 2%;
  }

  .coupon_item_label {
    background-color: #53a93f;border-radius: 5px;font-size: 14px;width:56px;text-align: center;color:white;padding:5px 8px;
  }

  .coupon_item_text {
    margin-left: 2%;
  }

  .coupon_item_money {
    text-align: center;font-size: 28px;
  }

  .coupon_check {
    width: 40px;
    font-size: 22px;
    text-align: center;
  }
</style>

<input type="hidden" name="cart_type" id="cart_type" value="<?=$data['type'] ?>" />
<input type="hidden" name="cart_id" id="cart_id" value="<?=$data['id'] ?>" />
<input type="hidden" name="express_fee" id="express_fee" value="<?=$data['express_fee'] ?>" />
<input type="hidden" name="cart_num" id="cart_num" value="<?=count($data['product']) ?>" />

<div class="card">
  <div class="card-content" id="show_address_content">
      <?php if (empty($address)) { ?>
      <div class="no_address">
        <br/>
        <button id="add_address" type="button" class="btn btn-outline-danger btn-sm" style="width:140px;margin-bottom: 10px;font-size: 14px;height:30px;"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;添加收货地址</button>
      </div>
      <div class="hide_address" data-id="">
        <div style="width: 98%;">
          <p style="font-weight: bold;color:#333;">
            <span id="show_rec_name"></span>
            <span id="show_rec_phone" style="padding-left: 5px;"></span>&nbsp;
            <span id="show_label" class="border border-success text-success"></span>
          </p>
          <p style="color:#a3a3a3;display: flex;flex-direction: row;justify-content: flex-start;">
            <span><i class="fa fa-map-marker" aria-hidden="true"></i>&nbsp;</span>
            <span id="show_address"></span>
          </p>
        </div>
        <div>
          <i class="fa fa-chevron-right" aria-hidden="true" style="color:#ccc;"></i>
        </div>
      </div>
      <?php } else { ?>
      <div class="show_address" data-id="<?=$address[0]['id'] ?>">
        <div style="width: 98%;">
          <p style="font-weight: bold;color:#333;">
            <span id="show_rec_name"><?=$address[0]['rec_name'] ?></span>
            <span id="show_rec_phone" style="padding-left: 5px;"><?=$address[0]['rec_phone'] ?></span>&nbsp;
            <span id="show_label" class="border border-success text-success"><?=$address[0]['label'] ?></span>
          </p>
          <p style="color:#a3a3a3;display: flex;flex-direction: row;justify-content: flex-start;">
            <span><i class="fa fa-map-marker" aria-hidden="true"></i>&nbsp;</span>
            <span id="show_address"><?=$address[0]['rec_city'] ?><?=$address[0]['rec_district'] ?><?=$address[0]['rec_detail'] ?></span>
          </p>
        </div>
        <div>
          <i class="fa fa-chevron-right" aria-hidden="true" style="color:#ccc;"></i>
        </div>
      </div>
      <?php } ?>
    </div>
  </div>
</div>

<div class="card">
  <div id="products" class="card-content">
    <div id="product_detail" style="display: flex;justify-content: space-between;flex-direction: row;align-items: center;">
      <div style="position: relative;width:80%;height:80px;overflow: hidden;">
        <?php foreach($data['product'] as $item) { ?>
        <div class="product" style="width:20%;height: 100%;line-height:90px;display: inline-block;">
          <img src="<?=$item['img'] ?>" style="height:100%;" />
        </div>
        <?php } ?>
      </div>

      <div>
        <span style="font-size: 13px;cursor: pointer;">共<?=count($data['product']) ?>件&nbsp;&nbsp;
          <i class="fa fa-chevron-right" aria-hidden="true" style="color:#ccc;"></i>
        </span>
      </div>
    </div>

    <hr style="margin-top: 0.5rem;margin-bottom: 0.5rem;" />
    <div class="item" style="justify-content: space-between;align-items: center;">
        <div style="display: flex;flex-direction: row;align-items: center;justify-content: flex-start;">
          <p class="label">配送方式：</p>
          <div class="form-check form-check-inline">
            <label class="form-check-label" style="display: flex;flex-direction: row;align-items: center;justify-content: flex-start;">
              <input class="form-check-input" type="radio" name="express_rule" id="express_1" value="0" checked> 
              <span class="badge badge-success" style="letter-spacing: 1px;font-size: 12px;line-height: 18px;font-weight: normal;margin-top: 8px;">快递</span>
            </label>
          </div>
          <div class="form-check form-check-inline">
            <label class="form-check-label" style="display: flex;flex-direction: row;align-items: center;justify-content: flex-start;">
              <input class="form-check-input" type="radio" name="express_rule" id="express_2" value="1">
              <span class="badge badge-info" style="letter-spacing: 1px;font-size: 12px;line-height: 18px;font-weight: normal;margin-top: 8px;">自提</span>
            </label>
          </div>
        </div>

        <div id="express_time">
          <span style="font-size: 13px;cursor: pointer;color:red;">发货时间&nbsp;&nbsp;
            <i class="fa fa-chevron-right" aria-hidden="true" style="color:#ccc;"></i>
          </span>
        </div>
    </div>
  </div>
</div>

<div class="card">
  <div id="discount" class="card-content">
    <div style="margin-top:5px;display: flex;flex-direction: row;justify-content: flex-start;">
      <input type="text" class="form-control input-sm" name="code" id="code" placeholder="优惠码:好友手机号码" maxlength=11 style="width:50%;font-size: 14px;margin-left: 5px;" />
      <button type="button" id="use_discount" class="btn btn-outline-success btn-sm" style="height:30px;margin-top:5px;margin-left:5px;">使用</button>
      <span id="ask" style="line-height: 40px;margin-left: 10px;color:#0C58B0;cursor: pointer;font-size: 13px;">优惠码规则？</span>
    </div>
    <hr style="margin-top: 0.5rem;margin-bottom: 0.5rem;"/>
    <div style="margin-bottom:3px;display: flex;flex-direction: row;justify-content: space-between;" id="choose_coupon">
      <p class="label" style="width:70%;">优惠券</p>
      <p style="font-size: 13px;cursor: pointer;">
        <?php if ($coupon > 0) { ?>
        可用<span class="text-danger"><?=$coupon ?></span>张&nbsp;&nbsp;&nbsp;<i class="fa fa-chevron-right" aria-hidden="true" style="color:#ccc;"></i>
        <?php } else { ?>
        可用<span class="text-danger">0</span>张&nbsp;&nbsp;&nbsp;<i class="fa fa-chevron-right" aria-hidden="true" style="color:#ccc;"></i>
        <?php } ?>
      </p>
    </div>
  </div>
</div>

<div class="card">
  <div id="fee" class="card-content">
    <div class="item">
        <p class="label">商品金额：</p>
        <p style="color:red;">¥<span id="product_price"><?=$data['product_price'] ?></span></p>
    </div>
    <div class="item">
        <p class="label">运费：</p>
        <p style="color:red;">+ ¥<span id="express_fee_show"><?=$data['express_fee'] ?></span></p>
    </div>
    <div class="item">
        <p class="label">优惠码：</p>
        <p style="color:red;">- ¥<span id="discount_fee">0</span></p>
    </div>
    <div class="item">
        <p class="label">优惠券：</p>
        <p style="color:red;">- ¥<span id="coupon_fee">0</span></p>
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
  <div id="tips" style="color:red;margin-left: 5px;margin-right: 5px;font-size: 15px;">实付款:</div>
  <div id="total" style="margin-left:5px;margin-right: 5px;opacity: 1;">
    <div style="display: inline-block;color:red;width:80px;text-align: left;">
      <span class="money" style="font-size: 15px;font-weight: normal;">¥</span>
      <span class="realprice" style="font-size:22px;font-weight: normal;" id="realprice"></span>
    </div>
  </div>
  <div class='btn btn-outline-info' id='edit' style="width:90px;margin-right: 10px;">修改商品</div>
  <div class='btn btn-success' id='order' style="width:100px;margin-right: 10px;">去下单</div>
</div>

<div class="card" id="question">
  <div class="card-header bg-white" style="color: #53a93f;border-radius: 0;border-bottom: 1px solid #92BC2C;display: flex;flex-direction: row;justify-content: space-between;">
      优惠码<i class="fa fa-times" aria-hidden="true" style="cursor: pointer;" id="close_question"></i>
  </div>
  <div style="padding: 5%;">
    <p style="display: flex;flex-direction: row;justify-content: flex-start;">
      <span style="padding-right: 5px;">1: </span>
      <span>任何好友的手机号码即为优惠码，随机立减<?=$discount_start ?>%-<?=$discount_end ?>%的订单金额。</span>
    </p>
    <p style="display: flex;flex-direction: row;justify-content: flex-start;margin-top: 5px;">
      <span style="padding-right: 5px;">2: </span>
      <span>订单支付成功后为好友充此次优惠的50%到其平台账户，赶快来一起享受优惠吧。</span>
    </p>

    <p style="display: flex;flex-direction: row;justify-content: flex-start;margin-top: 5px;">
      <span style="padding-right: 5px;">3: </span>
      <span>折扣有效期为2小时，超时需重新提交订单获取优惠。</span>
    </p>

    <div class='btn btn-success btn-sm' id='close_question_bottom' style="width:40%;margin-left:30%;margin-top: 3%;">关闭</div>
  </div>
</div>

<div class="card" id="detail">
  <div class="card-header bg-white" style="color: #53a93f;border-radius: 0;border-bottom: 1px solid #92BC2C;display: flex;flex-direction: row;justify-content: space-between;">
      商品详情<i class="fa fa-times" aria-hidden="true" style="cursor: pointer;" id="close_detail"></i>
  </div>
  <table class="table table-bordered" style="margin-top: 10px;font-size: 13px;width:98%;margin:1% auto;">
    <thead>
      <tr>
        <th scope="col">商品</th>
        <th scope="col">数量</th>
        <th scope="col">总价</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($data['product'] as $item) { ?>
        <tr>
          <td><?=$item['name'] ?></td>
          <td><?=$data['product_cart'][$item['id']]['num'] ?><?=$item['unit'] ?></td>
          <td><?=$data['product_cart'][$item['id']]['price'] * $data['product_cart'][$item['id']]['num'] ?></td>
        </tr>
      <?php } ?>
    </tbody>
    </table>
</div>

<div class="card" id="all_address_info">
    <div class="card-header bg-white" style="color: #53a93f;border-radius: 0;border-bottom: 1px solid #92BC2C;display: flex;flex-direction: row;justify-content: space-between;">
      收货地址<i class="fa fa-times" aria-hidden="true" style="cursor: pointer;" id="close_all_address"></i>
    </div>

    <div style="height:80%;overflow-y: scroll;" id="all_address_items">
      <?php foreach($address as $item) { ?>
      <div class="all_address_item">
          <div style="width: 10%" class="address-status" data-id="<?=$item['id'] ?>"><i class="fa fa-check-square-o" aria-hidden="true"></i></div>
          <div style="width: 70%;" class="address-content" data-id="<?=$item['id'] ?>">
            <p style="font-weight: bold;color:#333;">
              <?=$item['rec_name'] ?>
              <span id="cipher_phone" style="padding-left: 5px;"><?=$item['rec_phone'] ?></span>&nbsp;
              <span class="border border-success text-success" style="font-size: 12px;padding:0px 10px;"><?=$item['label'] ?></span>
            </p>
            <p style="color:#a3a3a3;display: flex;flex-direction: row;justify-content: flex-start;">
              <span><i class="fa fa-map-marker" aria-hidden="true"></i></span>
              <span>&nbsp;<?=$item['rec_city'] ?><?=$item['rec_district'] ?><?=$item['rec_detail'] ?></span>
            </p>
          </div>

          <div data-id="<?=$item['id'] ?>" class="edit_address_item" style="width: 10%">
            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
          </div>

          <div data-id="<?=$item['id'] ?>" class="del_address_item" style="width: 10%">
            <i class="fa fa-trash-o" aria-hidden="true"></i>
          </div>
      </div>
      <?php } ?>
    </div>

    <button id="inner_add_address" type="button" class="btn btn-danger" style="width:50%;margin-left:25%;font-size: 14px;"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;添加收货地址并使用</button>
    <span id='close_all_address_bottom' style="padding-left: 25px;font-size: 14px;color:#0C58B0;">关闭</span>
</div>

<div class="card" id="address_info">
  <div class="card-header bg-white" style="color: #53a93f;border-radius: 0;border-bottom: 1px solid #92BC2C;display: flex;flex-direction: row;justify-content: space-between;">
      收货地址<i class="fa fa-times" aria-hidden="true" style="cursor: pointer;" id="close_address"></i>
  </div>
  <form id="address_form" name='address_form' style='margin:5%;'>
    <input type="hidden" name="id" value='' id="edit_address_id" />
    <div class='form-group' style='margin-top:10px;border-bottom: 1px solid #f5f5f5;'>
        <label>收货人：</label>
        <input type='text' placeholder='' name='rec_name' class='input-sm' id='rec_name' maxlength=45 value='' />
    </div>
    <div class='form-group' style='margin-top:10px;border-bottom: 1px solid #f5f5f5;'>
        <label>手机号码：</label>
        <input type='text' placeholder='' name='rec_phone' class='input-sm' id='phone' size=11 value='' />
    </div>
    <div class='form-group' style='margin-top:10px;border-bottom: 1px solid #f5f5f5;'>
        <label>所在城市：</label>
        <select name="rec_city" id="rec_city">
          <option value="成都">成都</option>
        </select>
    </div>
    <div class='form-group' style='margin-top:10px;border-bottom: 1px solid #f5f5f5;'>
        <label>所在地区：</label>
        <select name="rec_district" id="rec_district">
        <?php foreach($citymap as $item) { ?>
          <option value="<?=$item ?>"><?=$item ?></option>
        <?php } ?>
        </select>
    </div>
    <div class='form-group' style='margin-top:10px;border-bottom: 1px solid #f5f5f5;display: flex;flex-direction: row;justify-content: flex-start;padding-bottom: 10px;'>
        <label style="width:30%;">详细地址：</label>
        <textarea style="width:68%;" placeholder='街道、楼牌号, 限100字' maxlength=100 name='rec_detail' id='rec_detail' class='input-sm'></textarea>
    </div>
    <div class='form-group' style='margin-top:10px;padding-bottom:10px;border-bottom: 1px solid #f5f5f5;display: flex;flex-direction: row;justify-content: flex-start;' id="label_add_group">
        <label style="width:20%;">标签：</label>
        <div style="width:78%;">
          <div class="label_choose active">家</div>
          <div class="label_choose">公司</div>
          <div class="label_choose">学校</div>
          <div id="label_add">+</div>
          <div id="label_add_input" style="width:95%;display: none;">
            <div class="input-group">
              <input type="text" class="form-control" maxlength=5 style="font-size: 12px;" placeholder="请输入标签名字,最多5个字" id="label_add_text" value="" />
              <span class="input-group-btn">
                <button class="btn btn-secondary" type="button" style="font-size: 12px;" id="label_add_input_ok">确定</button>
              </span>
            </div>
          </div>
        </div>
    </div>

    <div style="display: flex;flex-direction: row;justify-content: center;width: 100%;">
      <button type="button" class="btn btn-success btn-sm" id="save_address" style="width:150px;">保存并使用</button>
      <span id='close_address_bottom' style="padding-left: 25px;font-size: 14px;color:#0C58B0;line-height: 31px;">关闭</span>
    </div>
  </form>
</div>

<div class="card" id="coupon">
  <div class="card-header bg-white" style="color: #53a93f;border-radius: 0;border-bottom: 1px solid #92BC2C;display: flex;flex-direction: row;justify-content: space-between;">
      优惠券<i class="fa fa-times" aria-hidden="true" style="cursor: pointer;" id="close_coupon"></i>
  </div>
  <div id="coupon_items" data-ids="" style="height: 75%;overflow-y: scroll;">
  </div>
  <button id="ok_coupon" type="button" class="btn btn-success" style="position: absolute;bottom:3%;width:40%;left:30%;">确定并使用</button>
  <span id='close_coupon_bottom' style="position: absolute;bottom:4%;left:78%;font-size: 16px;color:#0C58B0;">关闭</span>
</div>


<div class="card" id="express_info">
  <div class="card-header bg-white" style="color: #53a93f;border-radius: 0;border-bottom: 1px solid #92BC2C;display: flex;flex-direction: row;justify-content: space-between;">
      发货时间<i class="fa fa-times" aria-hidden="true" style="cursor: pointer;" id="close_express_info"></i>
  </div>
  <div style="padding: 5%;">
    <p style="display: flex;flex-direction: row;justify-content: flex-start;">
      <span style="padding-right: 5px;">普通订单: </span>
      <span>24小时内发货，预计隔日到达。</span>
    </p>
    <p style="display: flex;flex-direction: row;justify-content: flex-start;margin-top: 5px;">
      <span style="padding-right: 5px;">预约订单: </span>
      <span>发货时间为每周1、3、5上午。<br/>取最近的时间，预计隔日到达。</span>
    </p>
    <p style="display: flex;flex-direction: row;justify-content: flex-start;margin-top: 5px;">
      <span style="padding-right: 5px;">特殊要求: </span>
      <span>请留言或直接联系我们。</span>
    </p>

    <div class='btn btn-success btn-sm' id='close_express_info_bottom' style="width:40%;margin-left:30%;margin-top: 3%;">关闭</div>
  </div>
</div>
