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
    margin-bottom: 5px;
    font-size: 16px;
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
    height: 100%;
  }

  .show_address {
    margin: 10px 0px;
  }

  .address {
    /*justify-content: flex-start;*/
  }

  .all_address_item {
    padding: 3% 5%; border-bottom: 1px solid #eee;height:auto;
  }

  #address_info {
    height: 85%;
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

  #address_form .form-group {
    margin-top:10px;
    /*display: flex;flex-direction: row;align-items: center;flex-wrap: nowrap;*/
  }

</style>

<div class="card" id="all_address_info" style="width:100%;height:100%;">
    <div class="card-header bg-white" style="color: #53a93f;border-radius: 0;border-bottom: 1px solid #92BC2C;">
      地址管理
    </div>

    <div style="height:80%;" id="all_address_items">
      <?php foreach($address as $item) { ?>
      <div class="all_address_item" style="display: table;width: 100%;">
          <div style="width: 80%;display: table-cell;" class="address-content" data-id="<?=$item['id'] ?>">
            <p style="font-weight: bold;color:#333;">
              <?=$item['rec_name'] ?>
              <span id="cipher_phone" style="padding-left: 5px;"><?=$item['rec_phone'] ?></span>&nbsp;
              <span class="border border-success text-success" style="font-size: 14px;padding:0px 10px;"><?=$item['label'] ?></span>
            </p>
            <p style="color:#a3a3a3;font-size: 16px;">
              <span><i class="fa fa-map-marker" aria-hidden="true"></i></span>
              <span>&nbsp;<?=$item['rec_city'] ?><?=$item['rec_district'] ?><?=$item['rec_detail'] ?></span>
            </p>
          </div>

          <div data-id="<?=$item['id'] ?>" class="edit_address_item" style="width: 10%;display: table-cell;vertical-align: middle;">
            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
          </div>

          <div data-id="<?=$item['id'] ?>" class="del_address_item" style="width: 10%;display: table-cell;vertical-align: middle;">
            <i class="fa fa-trash-o" aria-hidden="true"></i>
          </div>
      </div>
      <?php } ?>
    </div>

    <div style="">
      <button id="inner_add_address" type="button" class="btn btn-danger btn-sm" style="width:45%;margin-left:25%;font-size: 16px;"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;添加收货地址</button>
      <div id="inner_back" style="margin-left:30px;display: inline-block;" class="text-success">返回</div>
    </div>
</div>

<div class="card" id="address_info">
  <div class="card-header bg-white" style="color: #53a93f;border-radius: 0;border-bottom: 1px solid #92BC2C;">
      收货地址<i class="fa fa-times" aria-hidden="true" style="cursor: pointer;position: absolute;right:15px;" id="close_address"></i>
  </div>
  <form id="address_form" name='address_form' style='margin:5%;' autocomplete="off">
    <input type="hidden" name="id" value='' id="edit_address_id" />
    <div class='form-group' style="display: table;width: 100%;">
        <label style="width:30%; display: table-cell;">收货人：</label>
        <input class="form-control" style="display: table-cell;" type='text' placeholder='' name='rec_name' class='input-sm' id='rec_name' maxlength=45 value='' />
    </div>
    <div class='form-group' style="display: table;width: 100%;">
        <label style="width:30%;display: table-cell;">手机号码：</label>
        <input class="form-control" style="display: table-cell;" type='text' placeholder='' name='rec_phone' class='input-sm' id='phone' value='' />
    </div>
    <div class='form-group' style="display: table;width: 100%;">
        <label style="width:30%;display: table-cell;">所在城市：</label>
        <select class="form-control" style="display: table-cell;" name="rec_city" id="rec_city">
          <option value="成都">成都</option>
        </select>
    </div>
    <div class='form-group' style="display: table;width: 100%;">
        <label style="width:30%;display: table-cell;">所在地区：</label>
        <select class="form-control" style="display: table-cell;" name="rec_district" id="rec_district">
        <?php foreach($citymap as $item) { ?>
          <option value="<?=$item ?>"><?=$item ?></option>
        <?php } ?>
        </select>
    </div>
    <div class='form-group' style="display: table;width: 100%;">
        <label style="width:30%;display: table-cell;">详细地址：</label>
        <textarea class="form-control" style="display: table-cell;" placeholder='街道、楼牌号, 限100字' maxlength=100 name='rec_detail' id='rec_detail' class='input-sm'></textarea>
    </div>
    <div class='form-group' style='display: table;margin-top:10px;padding-bottom:10px;border-bottom: 1px solid #f5f5f5;display: table;width: 100%;' id="label_add_group">
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
                <button class="btn btn-secondary btn-sm" type="button" style="font-size: 14px;" id="label_add_input_ok">确定</button>
              </span>
            </div>
          </div>
        </div>
    </div>

    <div style="width: 100%;align-items: center;">
      <button type="button" class="btn btn-success btn-sm" id="save_address" style="width:150px;">保存</button>
      <span id='close_address_bottom' style="padding-left: 15px;font-size: 16px;color:#0C58B0;">关闭</span>
    </div>
  </form>
</div>