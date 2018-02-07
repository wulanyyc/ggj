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
use app\components\WechatHelper;

class WechatFilter extends Behavior
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
        $action = $event->action->id;

        if (!Yii::$app->request->isAjax) {
            if (!empty($_GET['code'])) {
                $code = $_GET['code'];

                // init weixin user
                if (empty($_SESSION['openid'])) {
                    WechatHelper::initWxPageVisit($code);
                }
            }
        }

        return $event->isValid;
    }
}
