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
        left: 0;
        top: 42%;
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

    #pointer {
        width: 10%;
        margin: 0 auto;
        text-align: center;
    }

    @-webkit-keyframes rotation{
      from {
        -webkit-transform: rotate(0deg);
      }

      to {
        -webkit-transform: rotate(360deg);
      }
    }

    .Rotation{
        transform:rotate(22.5deg);
        -ms-transform:rotate(22.5deg);   /* IE 9 */
        -moz-transform:rotate(22.5deg);  /* Firefox */
        -webkit-transform:rotate(22.5deg); /* Safari 和 Chrome */
        -o-transform:rotate(22.5deg);  /* Opera */
        /*animation: rotation 2s linear;*/
        /*-moz-animation: rotation 2s linear;*/
        /*-webkit-animation: rotation 2s linear;*/
        /*-o-animation: rotation 2s linear;*/
    }

    .modal {
        top: 22% !important;
    }
</style>


<div id="background">
  <div id="zhuanpan">
    <div id="pan_container">
        <img src="/img/prize/pan.png" id="pan" style="height: 100%;width:auto;"/>
    </div>
    <div id="pointer_container">
        <img id="pointer" src="/img/prize/arrow.png" data-valid="0" />
    </div>
  </div>
</div>

