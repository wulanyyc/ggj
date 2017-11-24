<?php

namespace app\modules\right\models;

use yii\db\ActiveRecord;

class User extends ActiveRecord
{
    /**
     * 数据库
     * @return 数据库配置
     */
    public static function getDb(){
        return \Yii::$app->db;
    }

    public static function tableName(){
        return 'right_user';
    }
    
    public function rules() {
        return [
            [['username'], 'required'],
            [['username'], 'unique'],
        ];
    }
}
