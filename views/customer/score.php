<?php
use yii\web\View;
use app\components\MsaView;
use app\assets\SiteAsset;

MsaView::registerJsFile($this,'/js/customer/score.js', 
    ['position' => View::POS_END, 
        'depends' => [
            'app\assets\SiteAsset',
        ]
    ]
);

$this->title = '积分商城';
?>

<style type="text/css">
  .item {
    display: flex;
    flex-direction: row;
    justify-content: flex-start;

    display: -webkit-flex;
    flex-direction: -webkit-row;
    justify-content: -webkit-flex-start;
    margin-bottom: 5px;
    font-size: 14px;
  }

  .item .label {
    width: 70px;
  }

  p {
    margin: 0;
  }

  footer {
    display: none;
  }

  .card-content {
    padding: 5px 15px;
  }

  #score_items {
    width: 100%;padding:2%;
  }

  .score_item {
    display: flex;flex-direction: row;justify-content: space-around;
    align-items: center;
    display: -webkit-flex;flex-direction: -webkit-row;justify-content: -webkit-space-around;
    align-items: -webkit-center;

    border: 1px dashed #ccc;padding: 2%;width:100%;
    margin-bottom: 2%;
  }

  .score_item_label {
    background-color: #53a93f;border-radius: 30px;font-size: 14px;
    width:60px;text-align: center;color:white;
    height: 60px;line-height: 60px;
  }

  .score_item_text {
    margin-left: 1%;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;

    display: -webkit-flex;
    flex-direction: -webkit-column;
    justify-content: -webkit-center;
    align-items: -webkit-center;
  }

  .score_item_money {
    text-align: center;font-size: 28px;
  }

  .score_check {
    width: 40px;
    font-size: 22px;
    text-align: center;
  }

  #question {
    position: fixed;
    z-index: 100;
    bottom: 0;
    width: 100%;
    border-radius: 0;
    height: 60%;
    
    display: none;
  }

</style>

<div class="card" id="score">
    <div class="card-header bg-white" style="color: #53a93f;border-radius: 0;border-bottom: 1px solid #92BC2C;display: flex;flex-direction: row;justify-content: space-between;display: -webkit-flex;flex-direction: -webkit-row;justify-content: -webkit-space-between;">
      积分商城<span>目前：<span class="text-danger" id="current_score"><?=$data['score'] ?></span>分</span>
      <span style="color:#aaa;" id="ask">如何获取？</span>
    </div>
    <div style="padding:5%;">
      <?php foreach($config as $key => $item) { ?>
      <div class="score_item">
        <p class="score_item_label">兑余额</p>
        <div class="score_item_text">
          <p class="score_item_money text-danger"><?=$item['score'] ?>积分 = <?=$item['money'] ?>元</p>
          <button type="button" class="btn btn-danger change btn-sm" data-score=<?=$item['score'] ?> data-id=<?=$key ?> style="width:100px;">去兑换</button>
        </div>
      </div>
      <?php } ?>
    </div>
    <br/>
    <button type="button" class="btn btn-success btn-sm" id="inner_back" style="width:50%;margin-left:25%;margin-bottom: 10px;margin-top:5px;">返回</button>
</div>

<div class="card" id="question">
  <div class="card-header bg-white" style="color: #53a93f;border-radius: 0;border-bottom: 1px solid #92BC2C;display: flex;flex-direction: row;justify-content: space-between;display: -webkit-flex;flex-direction: -webkit-row;justify-content: -webkit-space-between;">
      如何获取积分<i class="fa fa-times" aria-hidden="true" style="cursor: pointer;" id="close_question"></i>
  </div>
  <div style="padding: 5%;">
    <p style="display: flex;flex-direction: row;justify-content: flex-start;display: -webkit-flex;flex-direction: -webkit-row;justify-content: -webkit-flex-start;">
      <span style="padding-right: 5px;">1: </span>
      <span>购买商品获得等额消费积分。</span>
    </p>
    <p style="display: flex;flex-direction: row;justify-content: flex-start;display: -webkit-flex;flex-direction: -webkit-row;justify-content: -webkit-flex-start;">
      <span style="padding-right: 5px;">2: </span>
      <span>关注官方微信ggj_cd, 参加活动获取积分。</span>
    </p>
  </div>
</div>
