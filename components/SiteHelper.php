<?php
namespace app\components;

use Yii;
use yii\base\Component;

/**
 * 基础帮助类
 * @author yangyuncai
 *
 */
class SiteHelper extends Component{
    public static function getLayout() {
        $width = isset($_COOKIE['width']) ? $_COOKIE['width'] : 1280;

        if ($width <= 767) {
            return 'page';
        }
        return 'site';
    }
}
