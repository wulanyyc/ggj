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
use app\components\SiteHelper;

class CustomerFilter extends Behavior
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
        if (!in_array($action, $this->actions)) {
            if (Yii::$app->request->isAjax) {
                if (!SiteHelper::checkSecret()) {
                    SiteHelper::render('fail', '用户验证失败'); // 文案不要改，前台判断依赖
                }
            } else {
                if (!SiteHelper::checkSecret()) {
                    Yii::$app->controller->redirect('/customer/login');
                    Yii::$app->end();
                }
            }
        }

        return $event->isValid;
    }
}
