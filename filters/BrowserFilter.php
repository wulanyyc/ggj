<?php
/**
 * baidu cas auth 
 * @author yangyuncai
 *
 */

namespace app\filters;

use Yii;
use yii\base\ActionEvent;
use yii\base\Behavior;
use yii\web\Controller;

class BrowserFilter extends Behavior
{
    public $actions = [];

    /**
     * Declares event handlers for the [[owner]]'s events.
     * @return array events (array keys) and the corresponding event handler methods (array values).
     */
    public function events() {
        return [Controller::EVENT_BEFORE_ACTION => 'beforeAction'];
    }

    /**
     * @param ActionEvent $event
     * @return boolean
     * @throws MethodNotAllowedHttpException when the request method is not allowed.
     */
    public function beforeAction($event) {   
        $userAgent = Yii::$app->request->getUserAgent();
        if(preg_match('/msie [6|7|8]/i', $userAgent)){
            throw new \yii\base\UserException('平台不支持ie6，7，8系列浏览器, 请使用其它高级浏览器chrome or firefox等');
        }
        return $event->isValid;
    }
}
