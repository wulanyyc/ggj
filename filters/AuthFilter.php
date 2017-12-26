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
use app\modules\right\models\User;
use app\modules\right\models\UserRole;
use app\components\CommonHelper;
use app\models\UserLog;

class AuthFilter extends Behavior
{
    public $actions = [];
    
    public $visitType;

    /**
     * Declares event handlers for the [[owner]]'s events.
     * @return array events (array keys) and the corresponding event handler methods (array values).
     */
    public function events()
    {
        return [Controller::EVENT_BEFORE_ACTION => 'beforeAction'];
    }

    /**
     * @param ActionEvent $event
     * @return boolean
     * @throws MethodNotAllowedHttpException when the request method is not allowed.
     */
    public function beforeAction($event)
    {
        // 权限控制
        $module = Yii::$app->controller->module->id;
        $controller = Yii::$app->controller->id;

        $action = Yii::$app->controller->action->id;
        
        // 认证controller权限豁免
        if($module == 'basic' && $controller == 'auth'){
            return $event->isValid;
        }

        if($module == 'basic' && $controller == 'admin' && $action == 'index'){
            return $event->isValid;
        }
        
        if (!isset(Yii::$app->session['username'])) {
            $username = $this->getUserName();
            if (empty($username)) {
                Yii::$app->controller->redirect('/admin/index');
                Yii::$app->end();
            }
        
            $userInfo = User::find()->where(['username' => $username])->asArray()->one();
            if(empty($userInfo)){
                throw new \yii\base\UserException('欢迎访问果果佳，若无平台或功能访问权限，请联系362798045@qq.com开通权限');
            }
            
            Yii::$app->session['username'] = $username;
            Yii::$app->session['uid'] = $userInfo['id'];
            Yii::$app->session['rootflag'] = $userInfo['rootflag'];
        }
     
        if(!CommonHelper::checkRights()){
            $defaultController = $this->getDefaultController();
            if ($defaultController == $controller) {
                $link = CommonHelper::getRightLinkByUid();
                if ($link) {
                    Yii::$app->controller->redirect($link);
                    Yii::$app->end();
                }
            }
            throw new \yii\base\UserException('欢迎访问果果佳，若无平台或功能访问权限，请联系362798045@qq.com开通权限');
        }
        
        return $event->isValid;
    }
    
    /**
     * 获取用户名
     * @throws \yii\base\UserException
     * @return unknown
     */
    private function getUserName() {
        if (extension_loaded('redis')) {
            $cookies = Yii::$app->request->cookies;
            $visitFlag = urldecode($cookies->get('visitFlag'));
            if (!empty($visitFlag)){
                $username = Yii::$app->redis->get($visitFlag);
                if (!empty($username)) {
                    $this->visitType = 'cookie';
                    return $username;
                }
            }
        }
        
        return '';
    }
    
    /**
     * 获取系统默认跳转url
     * @return string
     */
    private function getDefaultController() {
        $defaultRoute = Yii::$app->defaultRoute;
        $arr = explode('/', $defaultRoute);
        
        $defaultController = $arr[0];
        
        return $defaultController;
    }
    
    /**
     * 添加用户访问记录
     */
    private function addUserVisitLog() {
        $username    = Yii::$app->session['username'];
        $requestType = Yii::$app->request->isAjax ? 'ajax' : 'normal';
        $requestUrl  = $_SERVER['REQUEST_URI'];
        $ip          = $_SERVER['REMOTE_ADDR'];
        $userAgent   = $_SERVER['HTTP_USER_AGENT'];
        $params      = json_encode($_REQUEST);
    
        $log = new UserLog();
        $log->username       = $username;
        $log->request_type   = $requestType;
        $log->request_url    = $requestUrl;
        $log->ip             = $ip;
        $log->user_agent     = $userAgent;
        $log->request_params = $params;
        $log->save();
    }

}
