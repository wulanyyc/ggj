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

    .modal {
        top: 18% !important;
        opacity: 0.9 !important;
    }

    .modal-body {
        padding: 10px !important;
    }

    .modal-footer {
        padding: 5px !important;
    }

    .btn-primary {
        background-color: #1ba93b !important;
        border-color: #1ba93b !important;
    }

    .bootbox-body {
        font-size: 14px !important;
    }
</style>


<div id="background">
  <div id="zhuanpan" data-valid="0">
    <div id="pan_container">
        <img src="/img/prize/pan_new.png" id="pan" style="height: 100%;width:auto;"/>
    </div>
    <div id="pointer_container">
        <img id="pointer" src="/img/prize/arrow.png"/>
    </div>
  </div>
</div>

