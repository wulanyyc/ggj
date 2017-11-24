<?php
use yii\web\View;
use app\components\MsaView;

$this->title = '首页';

MsaView::registerJsFile($this,'/js/home/index.js',
    [
        'position' => View::POS_END,
        'depends' => [
            'app\assets\DatePickerAsset',
            'app\assets\SelectAsset',
            'app\assets\BootBoxAsset',
         ]
    ]
);

?>
<div class ='col-md-10 col-md-offset-1'>
coding......
</div>
