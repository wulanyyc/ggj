<?php

namespace app\modules\right\models;

use yii\db\ActiveRecord;

class Mod extends ActiveRecord
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
        return 'right_module';
    }

    /**
     * 入库规则
     * @return array
     */
    public function rules() {
        return [
            [['text', 'type'], 'required'],
            [['module', 'controller'], 'match', 'pattern' => '/^[a-zA-Z]+$/'],
        ];
    }

    /**
     * 得到页面信息
     * @return [type] [description]
     */
    public static function getLinkText() {
        $data = self::find()
            ->select('text, link, group_parent_id, id')
            ->where(['type' => ['node', 'group_node', 'group_parent']])
            ->asArray()
            ->all();
        if (empty($data)) {
            return [];
        }
        $res = [];
        foreach ($data as $d) {
            if ($d['link'] != '#') {
                $res[$d['group_parent_id']]['data'][] = [
                        'text' => $d['text'],
                        'link' => $d['link']
                    ];
            } else {
                $res[$d['id']]['text'] = $d['text'];
            }
        }
        sort($res);
        return $res;
    }
}
