<?php

namespace app\models;

use yii\db\ActiveRecord;

class Address extends ActiveRecord
{
    /**
     * 数据库
     * @return 数据库配置
     */
    public static function getDb(){
        return \Yii::$app->db;
    }

    public static function tableName(){
        return 'address';
    }
    
    public function rules() {
        return [
            [['userphone'], 'required'],
            [['rec_name'], 'required'],
            [['rec_phone'], 'required'],
            [['rec_district'], 'required'],
            [['rec_detail'], 'required'],
        ];
    }
}
