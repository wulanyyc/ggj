<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\components\PriceHelper;
use app\components\SiteHelper;
use app\components\WechatHelper;

class PrizeController extends Controller
{
    public $layout = 'blank';
    public $shareId = '';
    public $dayLimit = 5;
    public $prefix = "prize_";
    
    public function actionIndex() {
        $params = Yii::$app->request->get();

        $sid = isset($params['share_id']) ? $params['share_id'] : '';
        $from = isset($params['from']) ? $params['from'] : 0;

        // if ($from === 'timeline' || $from === 'singlemessage' || !empty($_COOKIE['openid'])) {
            if (empty($_COOKIE['puid'])) {
                $uniq = uniqid();
                setcookie('puid', $uniq, time() + 86400 * $this->dayLimit, '/');
            } else {
                $uniq = $_COOKIE['puid'];
            }

            if (!empty($sid)) {
                Yii::$app->redis->setex($uniq . '_from', 86400 * $this->dayLimit, $sid);
            }
            
            return $this->render('index', [
                'controller' => Yii::$app->controller->id,
            ]);
        // }

        // return $this->render('error', [
        //     'controller' => Yii::$app->controller->id,
        // ]);
    }

    public function actionGetrotate() {
        $limit = 100;
        $dayLimit = $this->dayLimit;

        $uniq = $_COOKIE['puid'];

        $cntKey = $uniq . '_cnt';
        $cnt = Yii::$app->redis->get($cntKey);

        if ($cnt > 0) {
            $cnt += 1;
            Yii::$app->redis->setex($cntKey, 86400 * $dayLimit, $cnt);
        } else {
            $cnt = 1;
            Yii::$app->redis->setex($cntKey, 86400 * $dayLimit, $cnt);
        }

        if ($cnt > $limit) {
            $rotate = Yii::$app->redis->get($uniq);
            $prize = PriceHelper::getPrize($rotate);

            echo json_encode(['status' => 'fail', 'rotate' => $rotate, 'msg' => '您已达到' . $limit . '次抽奖限制，奖品:' . $prize['text']]);
            Yii::$app->end();
        }


        $rotate = 360 + rand(0, 360);
        if ($rotate / 45 == 0) {
            $rotate += 12;
        }

        Yii::$app->redis->setex($uniq, 86400 * $dayLimit, $rotate);
        $prize = PriceHelper::getPrize($rotate);

        if ($cnt == $limit) {
            echo json_encode(['status' => 'fail', 'rotate' => $rotate, 'msg' => '您已达到' . $limit . '次抽奖限制，奖品:' . $prize['text']]);
            Yii::$app->end();
        }

        $remain = $limit - $cnt;

        echo json_encode([
            'status' => 'ok',
            'rotate' => $rotate, 
            'msg' => '您还有'. $remain .'次抽奖机会，本次奖品:' . $prize['text'],
        ]);
    }

    public function actionSuc() {
        $uniq = isset($_COOKIE['puid']) ? $_COOKIE['puid'] : '';

        if (empty($uniq)) {
            return $this->render('error', [
                'controller' => Yii::$app->controller->id,
            ]);
        }

        $rotate = Yii::$app->redis->get($uniq);
        $prize = PriceHelper::getPrize($rotate);

        $prize['uniq'] = $uniq;

        $data = Yii::$app->redis->get($uniq . "_code");

        if (empty($data)) {
            $prizeCode = SiteHelper::getRandomStr(6);

            if (Yii::$app->redis->get($this->prefix . $prizeCode)) {
                $prizeCode = SiteHelper::getRandomStr(6);
            }

            Yii::$app->redis->setex($this->prefix . $prizeCode, 86400 * 30, json_encode($prize));

            $qrData = WechatHelper::getTempqrcode($prizeCode);

            $ticket = isset($qrData['ticket']) ? $qrData['ticket'] : '';
            if (empty($ticket)) {
                return $this->render('error', [
                    'controller' => Yii::$app->controller->id,
                ]);
            }

            Yii::$app->redis->setex($uniq . "_code", 86400 * $this->dayLimit, json_encode(['ticket' => $ticket, 'code' => $prizeCode]));
        } else {
            $data = json_decode($data, true);
            $ticket = $data['ticket'];
            $prizeCode = $data['code'];

            Yii::$app->redis->setex($this->prefix . $prizeCode, 86400 * 30, json_encode($prize));
        }

        return $this->render('suc', [
            'controller' => Yii::$app->controller->id,
            'ticket' => urlencode($ticket),
            'text' => $prize['text'],
            'code' => $prizeCode,
            'day' => $this->dayLimit,
        ]);
    }

    public function actionFail() {
        return $this->render('fail', [
            'controller' => Yii::$app->controller->id,
            'day' => $this->dayLimit,
        ]);
    }
}
