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

class CookieFilter extends Behavior
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
        if(!isset($_COOKIE) && empty($_COOKIE)){
            throw new \yii\base\UserException('浏览器的cookie功能未支持，请百度：开启浏览器cookie功能');
        }
        return $event->isValid;
    }
}
