<?php
use yii\web\View;
use app\components\MsaView;

$this->title = '地址管理';

MsaView::registerJsFile($this,'/js/address/index.js', 
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

  .item .label {
    width: 70px;
  }

  p {
    margin: 0;
  }

  #address_info {
    position: fixed;
    z-index: 100;
    bottom: 0;
    width: 100%;
    border-radius: 0;
    height: 60%;
    overflow-y: scroll;
    display: none;
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
    padding: 5px 15px;
  }

  .forbid {
    overflow-y: hidden;
  }

  .all_address_item {
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
    padding: 3% 5%; border-bottom: 1px solid #eee;height:auto;
  }

  #address_info {
    height: 80%;
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

</style>

<div class="card" id="all_address_info" style="width:100%;height:100%;">
    <div class="card-header bg-white" style="color: #53a93f;border-radius: 0;border-bottom: 1px solid #92BC2C;display: flex;flex-direction: row;justify-content: space-between;">
      地址管理
    </div>

    <div style="height:80%;overflow-y: scroll;" id="all_address_items">
      <?php foreach($address as $item) { ?>
      <div class="all_address_item">
          <div style="width: 80%;" class="address-content" data-id="<?=$item['id'] ?>">
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

    <button id="inner_add_address" type="button" class="btn btn-danger" style="width:50%;margin-left:25%;font-size: 14px;"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;添加收货地址</button>
</div>

<div class="card" id="address_info">
  <div class="card-header bg-white" style="color: #53a93f;border-radius: 0;border-bottom: 1px solid #92BC2C;display: flex;flex-direction: row;justify-content: space-between;">
      收货地址<i class="fa fa-times" aria-hidden="true" style="cursor: pointer;" id="close_address"></i>
  </div>
  <form id="address_form" name='address_form' action='#' method='post' style='margin:5%;' onsubmit='return false;'>
    <input type="hidden" name="id" value='' id="edit_address_id" />
    <div class='form-group' style='margin-top:10px;border-bottom: 1px solid #f5f5f5;'>
        <label>收货人：</label>
        <input type='text' placeholder='' name='rec_name' class='input-sm' id='rec_name' maxlength=45 value=''></input>
    </div>
    <div class='form-group' style='margin-top:10px;border-bottom: 1px solid #f5f5f5;'>
        <label>手机号码：</label>
        <input type='text' placeholder='' name='rec_phone' class='input-sm' id='rec_phone' size=11 value=''></input>
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

    <div style="display: flex;flex-direction: row;justify-content: center;position: absolute;bottom:20px;width: 100%;">
      <button type="button" class="btn btn-success btn-sm" id="save_address" style="width:150px;">保存</button>
    </div>
  </form>
</div>