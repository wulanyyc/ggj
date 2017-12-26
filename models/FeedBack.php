<?php

namespace app\models;

use yii\db\ActiveRecord;

class FeedBack extends ActiveRecord
{
    /**
     * 数据库
     * @return 数据库配置
     */
    public static function getDb(){
        return \Yii::$app->db;
    }

    public static function tableName(){
        return 'feedback';
    }
    
    public function rules() {
        return [
            [['userphone'], 'required'],
            [['advice'], 'required'],
        ];
    }
}
