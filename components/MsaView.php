<?php

namespace app\components;

use Yii;
use yii\base\Component;

class MsaView extends Component
{
    /**
     * 
     * @param object $obj  当前view对象
     * @param string $url
     * @param array $options
     * @param string $key
     */
    public static function registerJsFile($obj, $url, $options = [], $key = null) {
        $urlInfo = parse_url($url);
        $urlPath = $urlInfo['path'];
        
        $basePath = Yii::$app->basePath;
        $v = @filemtime($basePath.'/web'.$urlPath);
        
        $newUrl = $urlPath.'?v='.$v;
        $obj->registerJsFile($newUrl, $options, $key);
    }
}
