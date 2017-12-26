<?php

namespace app\models;

use yii\db\ActiveRecord;

class Customer extends ActiveRecord
{
    /**
     * 数据库
     * @return 数据库配置
     */
    public static function getDb(){
        return \Yii::$app->db;
    }

    public static function tableName(){
        return 'customer';
    }
    
    public function rules() {
        return [
            [['phone'], 'required'],
            [['phone'], 'unique'],
        ];
    }
}
