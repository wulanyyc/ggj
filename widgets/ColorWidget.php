<?php
namespace app\widgets;

use Yii;
use yii\base\Widget;
use app\modules\right\models\User;

/**
 * 颜色主题
 * @author yangyuncai
 *
 */
class ColorWidget extends Widget
{
    public $colorInfo;
    private $colorArr;

    /**
     * 初始化
     */
    public function init(){
        parent::init();
        $this->colorInfo = '/css/skins/green.min.css';
        $this->colorArr = [
            '/css/skins/green.min.css',
            '/css/skins/blue.min.css',
            '/css/skins/azure.min.css',
            '/css/skins/teal.min.css',
            '/css/skins/orange.min.css',
            '/css/skins/pink.min.css',
            '/css/skins/darkred.min.css',
            '/css/skins/purple.min.css',
            '/css/skins/darkblue.min.css',
            '/css/skins/gray.min.css',
            '/css/skins/black.min.css',
            '/css/skins/deepblue.min.css',
        ];
        $uid = Yii::$app->session['uid'];
        $userInfo = User::find()->where(['id' => $uid])->one();
        if ($userInfo) {
            $config = json_decode($userInfo->user_config, true);
            if (isset($config['current-skin']) && $config['current-skin'] && in_array($config['current-skin'], $this->colorArr)) {
                $this->colorInfo = $config['current-skin'];
            }
        }
    }
    
    /**
     * 执行
     * @return
     */
    public function run(){
        $html = <<<EOF
           <link href="{$this->colorInfo}" rel="stylesheet">
EOF;
        return $html;
    }
}
