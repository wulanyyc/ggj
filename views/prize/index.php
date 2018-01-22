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
        /*background-image: url('/img/prize/test_pan.jpeg');*/
        /*background-size: 100% 100%;*/
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
    }

    #pointer {
        width: 20%;
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
        transform:rotate(360deg);
        -ms-transform:rotate(360deg);   /* IE 9 */
        -moz-transform:rotate(360deg);  /* Firefox */
        -webkit-transform:rotate(360deg); /* Safari 和 Chrome */
        -o-transform:rotate(360deg);  /* Opera */
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
    <div id="pointer_container">
        <img id="pointer" class="Rotation" src="/img/prize/test_click.jpg" data-valid="0" />
    </div>
  </div>
</div>

