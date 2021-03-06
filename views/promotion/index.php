<?php 
use yii\web\View;
use app\components\MsaView;
use app\assets\SiteAsset;

$this->title = '今日特价';

SiteAsset::register($this);
?>

<style type="text/css">
  .card {
    border-radius: 0;
    border: none;
    border-bottom: 1px solid #eee;
    border-top: 1px solid #eee;
    margin: 5px;
  }

  #promotion-page {
    /*margin-top: 1%;*/
  }

  a.promotion-page-item {
    display: block;
    width:98%;
    border-radius: 3px;
    text-decoration: none;
    margin: 0 auto;
    margin-bottom: 2%;
  }

  .promotion-page-item-top {
    width:100%;
    height: 100px;
    opacity: 0.8;
  }

  .promotion-page-item-top-content {
    height:100%;width:68%;margin: 0 auto;
  }

  .promotion-page-item-top-content-top {
    text-align: center;
    font-size: 20px;
    color:#fff;
    height: 55px;
    line-height: 55px;
    border-bottom: 2px solid #fff;
    font-weight: 400;width:100%;
  }

  .promotion-page-item-top-content-bottom {
    color:#fff;
    font-size:16px;
    line-height: 44px;
    padding:3px;
    text-align: center;
  }

  .promotion-page-item-bottom {
    width:100%;
    height:250px;
    text-align: center;
    line-height: 250px;
  }

  .promotion-page-item-bottom img {
    height: 80%;
  }

  .day1-left {
    background-color: #1ba93b;
  }

  .day1-right {
    background-color: #D0E3DC;
  }

  .day2-left {
    background-color: #DD182B;
  }

  .day2-right {
    background-color: #F3CFD3;
  }

  .day3-left {
    background-color: #866D8D;
  }

  .day3-right {
    background-color: #D5CCDB;
  }

  .day4-left {
    background-color: #4C8C93;
  }

  .day4-right {
    background-color: #C7D9E0;
  } 

  .day5-left {
    background-color: #DDD17E;
  }

  .day5-right {
    background-color: #ECEADA;
  }

  .day6-left {
    background-color: #D58E39;
  }

  .day6-right {
    background-color: #E8D2BD;
  }

  .day0-left {
    background-color: #866352;
  }

  .day0-right {
    background-color: #D3C0BA;
  }
</style>

<div class="card">
<!--     <div class="card-header bg-white" style="color: #1ba93b;border-radius: 0;border-bottom: 2px solid #92BC2C;">
        <span class="first-title">天天特价</span>
    </div> -->
    <div id="promotion-page">
      <?php foreach($data as $value) { ?>
      <a class="promotion-page-item" href="/buy?id=<?=$value['id'] ?>">
        <div class="promotion-page-item-top prom-shop-left day<?=$value['day'] ?>-left">
          <div class="promotion-page-item-top-content">
            <div class="promotion-page-item-top-content-top">星期<?=$value['day_cn'] ?></div>
            <div class="promotion-page-item-top-content-bottom"><?=$value['name'] ?></div>
          </div>
        </div>

        <div class="promotion-page-item-bottom prom-shop-right day<?=$value['day'] ?>-right">
          <img src="<?=$value['img'] ?>"></img>
        </div>
      </a>
      <?php } ?>
    </div>
</div>
