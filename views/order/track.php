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
    width: 100%;
    color: red;
    /*width: 9%;*/
    /*text-align: center;*/
  }

  .step .label span{
    font-size: 16px;
  }

  .step-content {
    width: 90%;
  }

  #express_main {
    display: none;
  }

</style>

<div class="card" id="detail">
  <div class="card-header bg-white" style="color: #1ba93b;border-radius: 0;border-bottom: 1px solid #92BC2C;">
      订单详情
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

  <div id="inner-gifts">
    <?php if (!empty($giftInfos)) { ?>
      <?php foreach($giftInfos as $v) { ?>
      <div class="item" style="display: table;">
        <p style="display: table-cell;font-size: 16px;">
          <i class="fa fa-gift" aria-hidden="true" style="color:red;font-size: 20px;"></i>
          <?=$v['name'] ?>
        </p>
      </div>
      <?php } ?> 
    <?php } ?>
  </div>
</div>

<br/>

<div class="card" id="express_info">
  <div class="card-header bg-white" style="color: #1ba93b;border-radius: 0;border-bottom: 1px solid #92BC2C;">
      物流信息
  </div>
  <div id="express_info_content" style="height: 80%;">
    <?=$express ?>
  </div>
</div>
