<?php
use yii\web\View;
use app\components\MsaView;

$this->title = '抽奖';

// MsaView::registerJsFile($this,'/js/site/index.js', 
//     ['position' => View::POS_END, 
//         'depends' => [
//             'app\assets\SiteAsset',
//         ]
//     ]
// );

?>

<style type="text/css">
    #background {
      width:100%;
      height: 100%;
      background-image: url('/img/prize.jpeg');
      background-size: 100% 100%;
    }
</style>


<div id="background">

</div>

