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
    public $prefix = "prize_";
    public $dayLimit = 5; // 抽奖天数限制
    public $prizeLimit = 5; // 领奖期限
    public $limit = 10; // 抽奖次数限制
    
    public function actionIndex() {
        $params = Yii::$app->request->get();

        $sid = isset($params['share_id']) ? $params['share_id'] : '';
        $from = isset($params['from']) ? $params['from'] : '';

        if ($from == 'timeline' || $from == 'singlemessage'
         || $from == 'groupmessage' || !empty($_COOKIE['openid']) || !empty($_COOKIE['gguid'])) {
            if (empty($_COOKIE['gguid'])) {
                $uniq = uniqid();
                setcookie('gguid', $uniq, time() + 86400 * $this->dayLimit, '/');
            } else {
                $uniq = $_COOKIE['gguid'];
            }

            if (!empty($sid)) {
                Yii::$app->redis->setex($uniq . '_from', 86400 * $this->dayLimit, $sid);
            }
            
            return $this->render('index', [
                'controller' => Yii::$app->controller->id,
            ]);
        }

        return $this->render('error', [
            'controller' => Yii::$app->controller->id,
        ]);
    }

    public function actionGetrotate() {
        $limit    = $this->limit;
        $dayLimit = $this->dayLimit;

        $uniq = $_COOKIE['gguid'];

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
            $remainTime = Yii::$app->redis->ttl($uniq . "_from");
            if ($remainTime > 0) {
                $remainDay = round($remainTime / 86400, 1);
            } else {
                $remainDay = $dayLimit;
            }

            echo json_encode([
                'status' => 'fail',
                'msg'    => '您本周已达到' . $limit . '次抽奖限制, 请' . $remainDay . '天后再抽',
            ]);

            Yii::$app->end();
        }

        $rotate = rand(1, 8) * 45;
        $rotate -= 40;

        $rotate += 1080;

        Yii::$app->redis->setex($uniq, 86400 * $dayLimit, $rotate);
        $prize = PriceHelper::getPrize($rotate);

        if ($cnt == $limit) {
            echo json_encode([
                'status' => 'ok', 
                'rotate' => $rotate, 
                'msg' => '您本周已没有抽奖机会了, 建议领取本次奖品:' . $prize['text'],
            ]);
            Yii::$app->end();
        }

        $remain = $limit - $cnt;

        if ($remain < 0) {
            $remain = 0;
        }

        echo json_encode([
            'status' => 'ok',
            'rotate' => $rotate, 
            'msg' => '您还有'. $remain .'次抽奖机会，本次奖品:' . $prize['text'],
        ]);
    }

    public function actionSuc() {
        $uniq = isset($_COOKIE['gguid']) ? $_COOKIE['gguid'] : '';

        if (empty($uniq)) {
            return $this->render('error', [
                'controller' => Yii::$app->controller->id,
            ]);
        }

        $rotate = Yii::$app->redis->get($uniq);
        $prize = PriceHelper::getPrize($rotate);

        $prize['uniq'] = $uniq;

        $cntKey = $uniq . '_cnt';

        $cnt = Yii::$app->redis->get($cntKey);

        if ($cnt > $this->limit) {
            $remainTime = Yii::$app->redis->ttl($cntKey);
            $remainDay = round($remainTime / 86400, 1);

            return $this->render('limit', [
                'controller' => Yii::$app->controller->id,
                'day' => $remainDay,
            ]);
        }

        $data = Yii::$app->redis->get($uniq . "_code");

        if (empty($data)) {
            $prizeCode = SiteHelper::getRandomStr(6);

            // 存在的话，重新生成一个
            if (Yii::$app->redis->get($this->prefix . $prizeCode)) {
                $prizeCode = SiteHelper::getRandomStr(6);
            }

            Yii::$app->redis->setex($this->prefix . $prizeCode, 86400 * $this->prizeLimit, json_encode($prize));

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

            Yii::$app->redis->setex($this->prefix . $prizeCode, 86400 * $this->prizeLimit, json_encode($prize));
        }

        return $this->render('suc', [
            'controller' => Yii::$app->controller->id,
            'ticket' => urlencode($ticket),
            'text' => $prize['text'],
            'code' => $prizeCode,
            'day'  => $this->dayLimit,
            'prizeLimit' => $this->prizeLimit,
        ]);
    }

    public function actionFail() {
        return $this->render('fail', [
            'controller' => Yii::$app->controller->id,
            'day' => $this->dayLimit,
        ]);
    }
}
