<?php 
namespace app\components;

use Yii;
use yii\base\Behavior;
use yii\base\ActionEvent;
use yii\web\Controller;


class PagesetBehavior extends Behavior {
    public $tableSet = [];
    /**
     * Declares event handlers for the [[owner]]'s events.
     * @return array events (array keys) and the corresponding event handler methods (array values).
     */
    public function events()
    {
        return [Controller::EVENT_BEFORE_ACTION => 'beforeAction'];
    }


    /**
     * 给view层set数据库中最近date
     * @params $event
     * @return
     */
    public function beforeAction($event) {
        $controller = Yii::$app->controller;
        $tableSet = $controller->tableSet;
        $view = $controller->getView();
        if ($tableSet) {
            foreach ($tableSet as $key => $table) {
                if (!isset($table['key']) || !isset($table['class'])
                    || !method_exists($table['class'], 'getLatestDate')) {
                    continue;
                }
                $date = $table['class']::getLatestDate($table['key']);
                $date = date('Y-m-d', strtotime($date));
                $view->params['tdate'][$key] = $date ? $date : date('Y-m-d', strtotime('-1 day'));
            }
        }
        return $event->isValid;
    }
}

