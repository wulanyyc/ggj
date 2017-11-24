<?php
namespace app\models;
use yii\db\ActiveRecord;
use app\components\CommonHelper;

class BaseModel extends ActiveRecord {
    /**
     * 通用取数据类
     * @param $select
     * @param $where
     * @param $andWhere
     * @param $group
     * @param $order
     * @param $limit
     * @return
     */
    public static function data(array $param, array $arrDiv = []) {
        $select    = isset($param['select'])    ? $param['select']    : false;
        $where     = isset($param['where'])     ? $param['where']     : false;
        $andWhere  = isset($param['andWhere'])  ? $param['andWhere']  : false;
        $group     = isset($param['group'])     ? $param['group']     : false;
        $order     = isset($param['order'])     ? $param['order']     : false;
        $limit     = isset($param['limit'])     ? $param['limit']     : false;
        $offset    = isset($param['offset'])    ? $param['offset']    : false;
        $leftJoin  = isset($param['leftJoin'])  ? $param['leftJoin']  : false;
        $rightJoin = isset($param['rightJoin']) ? $param['rightJoin'] : false;
        $innerJoin = isset($param['innerJoin']) ? $param['innerJoin'] : false;
        $joinOn    = isset($param['joinOn'])    ? $param['joinOn']    : false;
        $nickname  = isset($param['nickname'])  ? $param['nickname']  : false;

        if (!$select) {
            return [];
        }
        $fromName = static::tableName();
        if ($nickname) {
            $fromName .= ' ' . $nickname;
        }
        $cmd = (new \yii\db\Query())->from($fromName);
        if ($leftJoin && $joinOn) {
            $cmd = $cmd->leftJoin($leftJoin, $joinOn);
        } else if ($rightJoin && $joinOn) {
            $cmd = $cmd->rightJoin($rightJoin, $joinOn);
        } else if ($innerJoin && $joinOn) {
            $cmd = $cmd->innerJoin($innerJoin, $joinOn);
        }
        if ($where !== false) {
            $cmd = $cmd->where($where);
        }
        
        if (!empty($andWhere)) {
            foreach($andWhere as $value) {
                $cmd = $cmd->andWhere($value);
            }
        }

        if (!empty($group)) {
            $cmd = $cmd->groupBy($group);
        }
        
        if ($order !== false) {
            $cmd = $cmd->orderBy($order);
        }

        if ($offset !== false) {
            $cmd = $cmd->offset($offset);
        }

        if ($limit !== false) {
            $cmd = $cmd->limit($limit);
        }
        
        $data = $cmd->select($select)
                ->all(static::getDB());
        
        if (!empty($arrDiv)) {
            $data = CommonHelper::arrayDivDimension($data, $arrDiv);
        }
        return empty($data) ? [] : $data;
    }

    /**
     * 得到对应表的最近日期
     * @param $key 表中日期的字段名
     * @return
     */
    public static function getLatestDate($key, $valStyle = 'Y-m-d') {
        $params = [];
        $params['select'] = [$key];
        $params['order']  = $key . ' desc';
        $params['limit']  = 1;

        $data = self::data($params);
        if (isset($data[0][$key])) {
            return date($valStyle, strtotime($data[0][$key]));
        }
        return false;
    }
}
