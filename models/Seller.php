<?php

namespace app\models;

use yii\db\ActiveRecord;

class Seller extends ActiveRecord
{
    /**
     * 数据库
     * @return 数据库配置
     */
    public static function getDb(){
        return \Yii::$app->db;
    }

    public static function tableName(){
        return 'seller';
    }
    
    public function rules() {
        return [
            [['name'], 'required'],
            [['name'], 'unique'],
        ];
    }
}
