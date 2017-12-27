<?php
use yii\web\View;
use app\components\MsaView;

$this->title = '支付详情';

MsaView::registerJsFile($this,'/js/pay/index.js', 
    ['position' => View::POS_END, 
        'depends' => [
            'app\assets\SiteAsset',
        ]
    ]
);

?>

<style type="text/css">
  footer {
    display: none;
  }

  .item {
    display: flex;
    flex-direction: row;
    justify-content: flex-start;
    margin-bottom: 5px;
    font-size: 14px;
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
    padding: 0 0 !important;
    background-color: #fff;
  }

  .status-item {
    margin-right: 10px;
    display: inline-block;
    font-size: 14px;
    height: 45px;
    line-height: 45px;
    padding-bottom: 5px;
    padding-left: 5px;
    padding-right: 5px;
    margin-left: 6px;
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
    display: flex;
    flex-direction: row;
    justify-content: flex-start;
    align-items: center;
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

<div id="info">
  test 支付结果页
</div>

