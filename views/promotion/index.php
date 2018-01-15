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
    margin: 5px; 5px;
  }

  #promotion {
    margin-top: 1%; display: flex;flex-direction: row;justify-content:flex-start;flex-wrap: wrap;
  }

  a.promotion-item {
    width:48%;height:150px;border-radius: 3px;display: flex;flex-direction: row;margin-bottom: 1%;
    margin-left: 1%;text-decoration: none;
  }

  .promotion-item-left {
    width:50%;height:100%;opacity: 0.8;display: flex;justify-content:center;align-items:center;
  }

  .promotion-item-left-content {
    height:50%;width:65%;
  }

  .promotion-item-left-content-top {
    text-align: center;font-size: 20px;color:#fff;height: 44px;line-height: 44px;border-bottom: 2px solid #fff;font-weight: 400;width:100%;
  }

  .promotion-item-left-content-bottom {
    color:#fff;font-size:20px;line-height: 44px;padding:3px;text-align: center;
  }

  .promotion-item-right {
    width:50%;height:100%;
    display: flex;
    flex-direction: row;
    justify-content: center;
    align-items: center;
  }

  .promotion-item-right img {
    height: 100%;
  }

  .day1-left {
    background-color: #53a93f;
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

  .day7-left {
    background-color: #866352;
  }

  .day7-right {
    background-color: #D3C0BA;
  }
</style>

<div class="card">
    <div class="card-header bg-white" style="color: #53a93f;border-radius: 0;border-bottom: 2px solid #92BC2C;">
        <span class="first-title">天天特价</span>
    </div>
    <div id="promotion">
      <?php foreach($data as $value) { ?>
      <a class="promotion-item" href="/buy?id=<?=$value['id'] ?>">
        <div class="promotion-item-left prom-shop-left day<?=$value['day'] ?>-left">
          <div class="promotion-item-left-content">
            <div class="promotion-item-left-content-top">星期<?=$value['day_cn'] ?></div>
            <div class="promotion-item-left-content-bottom"><?=$value['name'] ?></div>
          </div>
        </div>

        <div class="promotion-item-right prom-shop-right day<?=$value['day'] ?>-right">
          <img src="<?=$value['img'] ?>"></img>
        </div>
      </a>
      <?php } ?>
    </div>
</div>
