<?php
use yii\web\View;
use app\components\MsaView;
use app\assets\ClipAsset;

$this->title = '订单追踪';

MsaView::registerJsFile($this,'/js/order/track.js', 
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

/*  #detail, #express_info {
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
  }*/

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

  .forbid {
    overflow-y: hidden;
  }

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
    width: 9%;
    text-align: center;
  }

  .step-content {
    width: 90%;
  }

  #unknown {
    text-align: center;
    padding: 2%;
  }

</style>

<div class="card" id="detail">
  <div class="card-header bg-white" style="color: #1ba93b;border-radius: 0;border-bottom: 1px solid #92BC2C;">
      订单详情
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
      <?=$product ?>
    </tbody>
  </table>

  <div id="inner-gifts"></div>
</div>

<br/>

<div class="card" id="express_info">
  <div class="card-header bg-white" style="color: #1ba93b;border-radius: 0;border-bottom: 1px solid #92BC2C;">
      物流信息
      <i class="fa fa-times" aria-hidden="true" style="cursor: pointer;position: absolute;right:15px;" id="close_express"></i>
  </div>
  <div id="express_info_content" style="height: 80%;">
    <?=$express ?>
  </div>
</div>
