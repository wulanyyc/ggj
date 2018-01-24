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

  .score_item {
    width:100%;
    margin-bottom: 5%;
    display: table;
  }

  .score_item img {
    width: 100%;
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
    <div class="card-header bg-white" style="color: #1ba93b;border-radius: 0;border-bottom: 1px solid #92BC2C;">
      积分商城<span>目前：<span class="text-danger" id="current_score"><?=$data['score'] ?></span>分</span>
      <span style="color:#aaa;" id="ask">如何获取？</span>
    </div>
    <div style="padding:5%;">
      <?php foreach($config as $key => $item) { ?>
        <div class="score_item">
          <img src="http://img.guoguojia.vip/img/icon/change_<?=$key ?>.png" data-id=<?=$key ?> class="change" />
        </div>
      <?php } ?>
    </div>
    <br/>
    <button type="button" class="btn btn-danger btn-sm" id="inner_back" style="width:50%;margin-left:25%;margin-bottom: 10px;margin-top:5px;">返回</button>
</div>

<div class="card" id="question">
  <div class="card-header bg-white" style="color: #1ba93b;border-radius: 0;border-bottom: 1px solid #92BC2C;">
      如何获取积分
      <i class="fa fa-times" aria-hidden="true" style="cursor: pointer;position: absolute;right: 15px;" id="close_question"></i>
  </div>
  <div style="padding: 5%;">
    <p style="">
      <span style="padding-right: 5px;">1: </span>
      <span>购买商品获得等额消费积分。</span>
    </p>
    <p style="">
      <span style="padding-right: 5px;">2: </span>
      <span>参加官方活动获取积分。</span>
    </p>
  </div>
</div>
