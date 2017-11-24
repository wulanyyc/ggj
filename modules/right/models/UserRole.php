<?php

namespace app\modules\right\models;

use yii\db\ActiveRecord;

class UserRole extends ActiveRecord
{
    /**
     * 数据库
     * @return 数据库配置
     */
    public static function getDb(){
        return \Yii::$app->db;
    }

    /**
     * 表名
     * @return string
     */
    public static function tableName(){
        return 'right_user_role';
    }
    
    /**
     * 入库规则
     * @return array
     */
    public function rules() {
        return [
            [['role_id', 'user_id'], 'required'],
            [['role_id', 'user_id'], 'integer'],
        ];
    }
}
