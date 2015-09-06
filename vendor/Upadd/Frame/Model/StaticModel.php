<?php
/**
+----------------------------------------------------------------------
| UPADD [ Can be better to Up add]
+----------------------------------------------------------------------
| Copyright (c) 2011-2015 http://upadd.cn All rights reserved.
+----------------------------------------------------------------------
| Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
+----------------------------------------------------------------------
| Author: Richard.z <v3u3i87@gmail.com>
 **/
namespace Upadd\Frame\Model;

use Upadd\Bin\Verify;
use Upadd\Bin\Log;
use Upadd\Bin\PageData;
use Upadd\Bin\UpaddException;


class StaticModel{

    public static $_db = null;
    public static $_sql = null;
    public static $_table = null;

    public function __construct($_db,$_sql,$_table){
        static::$_db = $_db;
        static::$_sql = $_sql;
        static::$_table = $_table;
        p(static::$_table);
//        p(array(
//            static::$_db ,
//            static::$_sql,
//            static::$_table
//        ));
    }


    /**
     * 查询字段
     * @param null $_field
     * @return array|null|string
     */
    private static function lodeField($_field=null){
        $Field = '';
        if (Verify::IsNullString ( $_field )) {
            $Field = ' * ';
        } elseif (Verify::isArr ( $_field )) {
            $Field = lode ( ',', $_field );
        } elseif (is_string ( $_field )) {
            $Field = $_field;
        }
        return $Field;
    }


    /**
     * Where语句转
     * @param null $where in type string or array
     * @return string
     */
    private static function lodeWhere($where=null){
        $_inWhere = '';
        // 拼接WHERE
        $where != null ? $_inWhere = ' WHERE ' : null;
        // 数组的方式
        if (Verify::isArr ( $where )) {
            foreach ( $where as $k => $v ) {
                $_inWhere .= $k . "='{$v}'" . ' AND ';
            }
            $_inWhere = substr ( $_inWhere, 0, - 4 );
        }
        // 字符串方式
        if (is_string ( $where )) {
            $_inWhere .= $where;
        }
        return $_inWhere;
    }

    /**
     * 合并SQL语句
     * @return array|string
     */
    private static function mergeSql($type = null){
//        $this->is_mergeJoin();
//
//        if($this->_mergeJoin){
//            $this->_sql['from']  = ' FROM '.$this->_mergeJoin;
//        }else{
//            static::$_sql['from']  = ' FROM '.static::$_table;
//        }
//        return $this->is_mergeTypeSql($type);

        return static::$_sql['from']  = ' FROM '.static::$_table;
    }


    /**
     * 判断多表查询
     */
    protected function is_mergeJoin(){
        if(isset($this->_sql['join']) && empty($this->_mergeJoin)){
            if(is_array($this->_sql['join'])) {
                $this->_mergeJoin = lode(' ', $this->_sql['join']);
            }else{
                $this->_mergeJoin = $this->_sql['join'];
            }
            $this->_mergeJoin = substr ( $this->_mergeJoin, 0, - 1 );
            //Log::write($this->_mergeJoin ,'model_sql.log');
            unset($this->_sql['join']);
        }
    }


    public static function all($_field=null){
        $sql = ' SELECT ' .  self::lodeField($_field) . self::mergeSql();
        return static::$_db->select($sql);
    }




}