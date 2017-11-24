<?php

namespace app\controllers;

use Yii;
use app\controllers\AuthController;
use app\components\CommonHelper;
use app\modules\right\models\User;

class AdminController extends AuthController
{
    /**
     * 登录系统
     */
    public function actionIndex() {
        $this->layout = 'admin';
        if (Yii::$app->request->isAjax) {
            $username = $_POST['username'];
            $password = $_POST['password'];

            $ret = [];
            if (empty($username) || empty($password)) {
                $ret['status'] = 'fail';
                $ret['msg']    = '用户名或密码不能为空';
                echo json_encode($ret);
                Yii::$app->end();
            }

            if (!CommonHelper::checkPassword($username, $password)) {
                $ret['status'] = 'fail';
                $ret['msg']    = '用户名或密码错误'; 
                echo json_encode($ret);
            } else {
                $data = User::find()->where(['username' => $username])->asArray()->one();
                $uid  = $data['id'];
                // $link = CommonHelper::getRightLinkByUid($uid);

                Yii::$app->session['username'] = $username;
                Yii::$app->session['uid']      = $uid;
                Yii::$app->session['rootflag'] = $data['rootflag'];

                if (extension_loaded('redis')) {
                    // 删除历史cache
                    $visitHistoryFlag = Yii::$app->request->cookies->get('visitFlag');
                    if (!empty($visitHistoryFlag)) {
                        $visitHistoryFlag = urldecode($visitHistoryFlag);
                        Yii::$app->redis->del($visitHistoryFlag);
                    }
                    
                    // 新建cookie与cache
                    $visitFlag = uniqid();
                    Yii::$app->response->cookies->add(new \yii\web\Cookie([
                        'name' => 'visitFlag',
                        'value' => $visitFlag
                    ]));
                    
                    Yii::$app->redis->set($visitFlag, $username);
                    Yii::$app->redis->expire($visitFlag, 86400);
                }

                $ret['status'] = 'suc';
                echo json_encode($ret);
            }
        } else {
            return $this->render('index');
        }
    }

    /**
     * 修改密码
     */
    public function actionReset() {
        $this->layout = 'full';
        if (Yii::$app->request->isAjax) {
            $username = Yii::$app->session['username'];
            $oldPassword = $_POST['password'];
            $newPassword = $_POST['password2'];

            $ret = [];
            if (empty($username)) {
                $ret['status'] = 'fail';
                $ret['msg']    = '请先登录系统';
                echo json_encode($ret);
                Yii::$app->end();
            }

            if (empty($oldPassword) || empty($newPassword)) {
                $ret['status'] = 'fail';
                $ret['msg']    = '密码不能为空';
                echo json_encode($ret);
                Yii::$app->end();
            }

            if (!CommonHelper::checkPassword($username, $oldPassword)) {
                $ret['status'] = 'fail';
                $ret['msg']    = '密码错误'; 
                echo json_encode($ret);
            } else {
                $id = Yii::$app->session['uid'];
                $newPw = CommonHelper::makePassword($username, $newPassword);

                $user = User::findOne($id);
                $user->password = $newPw;
                $user->save();

                $ret['status'] = 'suc';

                echo json_encode($ret);
            }
        } else {
            return $this->render('reset');
        }
    }


    /**
     * 退出系统
     */
    public function actionLogout() {
        Yii::$app->session->remove('username');
        Yii::$app->session->remove('uid');
        Yii::$app->session->remove('rootflag');
        
        $cookies   = Yii::$app->request->cookies;
        $visitFlag = urldecode($cookies->get('visitFlag'));
        Yii::$app->redis->del($visitFlag);

        Yii::$app->controller->redirect('/admin/index');
    }
}