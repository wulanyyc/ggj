<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class UserLog extends ActiveRecord
{
    /**
     * 表名
     * @return [type]
     */
    public static function tableName() {
        return 'user_visit_log';
    }

    /**
     * 表规则
     * @return [type]
     */
    public function rules() {
        return [
        ];
    }
}