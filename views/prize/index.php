<?php
use yii\web\View;
use app\components\MsaView;

$this->title = '抽奖';

MsaView::registerJsFile($this,'/js/prize/index.js', 
    ['position' => View::POS_END, 
        'depends' => [
            'app\assets\SiteAsset',
        ]
    ]
);

?>

<style type="text/css">
    #background {
        width:100%;
        height: 100%;
        background-image: url('/img/prize/prize.jpeg');
        background-size: 100% 100%;
        position: fixed;
        z-index: 1;
    }

    body {
        padding-top: 0 !important;
    }

    #zhuanpan {
        position: absolute;
        top: 41%;
        z-index: 2;
        display: table;
        width: 100%;
        height: 42%;
    }

    #rotate {
        width: 100%;
        height: 100%;
    }

    #pointer_container {
        display: table-cell;
        vertical-align: middle;
        width: 100%;
        height: 100%;
        text-align: center;
        position: absolute;
        z-index: 8;
        top: 35%;
    }

    #pointer {
        width: 15%;
        margin: 0 auto;
        text-align: center;
    }

    #pan_container {
        display: table-cell;
        vertical-align: middle;
        width: 100%;
        height: 100%;
        text-align: center;
        position: absolute;
        z-index: 5;
        left: 0;
        right: 0;
    }

    .btn-default {
        display: none;
    }

/*    @keyframes rotation{
      from {
        transform: rotate(0deg);
      }

      to {
        transform: rotate(360deg);
      }
    }

    @-webkit-keyframes rotation{
      from {
        -webkit-transform: rotate(0deg);
      }

      to {
        -webkit-transform: rotate(360deg);
      }
    }*/

    #alert {
        position: fixed;
        top: 8% !important;
        z-index: 199;
        display: none;
        text-align: center;
        width: 90%;
        margin-left: 5%;
        background-color: #fff;
        opacity: 0.9;
        border-radius: 5px;
    }

    #loading {
        position: fixed;
        top: 59% !important;
        z-index: 100;
        display: none;
        text-align: center;
        /*position: relative;*/
        width: 100%;
    }
</style>


<div id="background">
  <div id="zhuanpan" data-valid="0">
    <div id="pan_container">
        <img src="/img/prize/pan_3.png" id="pan" style="height: 100%;width:auto;"/>
    </div>
    <div id="pointer_container">
        <img id="pointer" src="/img/prize/arrow.png"/>
    </div>
  </div>
</div>

<div id="loading">
    <img src="/img/icon/loading.gif" style="width:32px;margin: 0 auto;"/>
</div>

<div id="alert">
    <h5 style="padding-top: 15px;">抽奖提示</h5>
    <hr/>
    <div id="alert-content" style="padding: 0px 5px;letter-spacing: 1px;"></div>
    <hr/>
    <div style="padding-bottom: 5px;">
        <!-- <button type="button" class="btn btn-danger" id="alert-repeat">朕要重抽</button> -->
        <button type="button" class="btn btn-success" style="margin-left:10px;" id="alert-ok">朕要去领奖</button>
    </div>
</div>

