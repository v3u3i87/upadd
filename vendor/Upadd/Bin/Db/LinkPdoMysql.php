<?php
namespace Upadd\Bin\Db;

use Upadd\Bin\Tool\Log;

class LinkPdoMysql implements Db{

    /**
     * 对象
     *
     * @var unknown
     */
    public $_linkID = null;

    public $_sql = '';

    public $_query = null;

    public function __construct($link)
    {
        try {
            $dns = "mysql:dbname={$link ['name']};host={$link ['host']};port={$link ['port']};";
            $this->_linkID = new \PDO($dns,$link ['user'], $link ['pass']);
            $this->_linkID->query('SET NAMES '.$link ['charset']);
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
    }


    /**
     * 查询
     */
    public function select($sql)
    {
        if($sql)
        {
            $result = $this->query($sql);
            return $result->fetchAll(2);
        }else{
            return false;
        }
    }

    /**
     * 查询一条
     * @param unknown $sql
     * @return $data or bool
     */
    public function find($sql)
    {
        if($sql)
        {
            $result = $this->query ( $sql );
            return $result->fetch(2);
        }else{
            return false;
        }
    }

    /**
     * 获取下条自增ID
     * @param unknown $sql
     * @return multitype:multitype:
     */
    public function getNextId($sql)
    {
        if ($sql)
        {
            $_result = $this->select ( $sql );
            return $_result [0] ['Auto_increment'];
        }else{
            return false;
        }
    }

    /**
     * 获取表总行数
     * @param unknown $sql
     */
    public function getTotal($sql)
    {
        $total = $this->query ( $sql );
        return $total->fetchColumn();
    }

    /**
     * 获取表字段 并返回索引数组
     * @name
     * @param string $t
     * @return multitype:
     */
    public function getField($sql = null)
    {
        $_result = $this->select ( $sql );
        $field = '';
        foreach ( $_result as $k => $v )
        {
            $field .= $v ['Field'] . ',';
        }
        $field = substr ( $field, 0, - 1 );
        $field = explode ( ',', $field );
        return $field;
    }

    /**
     * 返回当前新增ID
     *
     * @return number
     */
    public function getId($sql = null)
    {
        return $this->_linkID->lastInsertId();
    }

    /**
     * 对外提供提交SQL
     */
    public function sql($sql)
    {
        $this->_sql = $sql;
        return $this->_linkID->exec( $sql );
    }

    // 释放结果集
    public function out($result='') { }

    /**
     * 提交SQL
     */
    public function query($sql) {
        $this->log($sql);
        return $this->_linkID->query($sql);
    }

    // 记录SQL错误
    public function log($sql = '') {
        $this->_sql = $sql;
        Log::write ( $sql, 'log.sql' ); // 记录SQL
    }


    /**
     * 开启事务
     * @return mixed
     */
    public function begin(){
        return $this->_linkID->beginTransaction();
    }

    /**
     * 提交事务并结束
     * @return mixed
     */
    public function commit(){
        return $this->_linkID->commit();
    }

    /**
     * 回滚事务
     * @return mixed
     */
    public function rollback(){
        return $this->_linkID->rollBack();
    }

    /**
     * 返回一条SQL语句s
     * @param $type as exit or
     * @return mixed
     */
    public function printSql($type=1){
        $type ? p($this->_sql) : p($this->_sql,1);
    }



}
