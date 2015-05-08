<?php
namespace Upadd\Bin\Db;

use Upadd\Bin\Log;

class LinkPdoMysql extends Db{

    /**
     * 对象
     *
     * @var unknown
     */
    protected $_linkID = null;


    public function __construct($link) {
        try {
            $pdoDns = "mysql:dbname={$link ['name']};host={$link ['host']};port={$link ['port']};";
            //'SET NAMES '.$link ['charset'] mysql:dbname=test;host=localhost
            $this->_pdo = new \PDO($pdoDns,$link ['user'], $link ['pass']);
            $this->_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
    }


    /**
     * 查询
     */
    public function select($sql) {
        if ($sql) {
            $result = $this->query ( $sql );
            p($result);
            $_result = array ();
            while ( ! ! $row = mysql_fetch_assoc ( $result ) ) {
                $_result [] = $row;
            }
            $this->out ( $result );
            return $_result;
        }
    }

    public function find($sql) {
        $result = $this->query ( $sql );
        $data = mysql_fetch_assoc ( $result );
        $this->out ( $result );
        return $data;
    }

    /**
     * 获取下条自增ID
     *
     * @param unknown $sql
     * @return multitype:multitype:
     */
    public function getNextId($sql) {
        if ($sql) {
            $_result = $this->select ( $sql );
            return $_result [0] ['Auto_increment'];
        }
    }

    /**
     * 获取表总行数
     *
     * @param unknown $sql
     */
    public function getTotal($sql) {
        $total = mysql_fetch_row ( $this->query ( $sql ) );
        return $total [0];
    }

    /**
     * 获取表字段 并返回索引数组
     *
     * @name
     *
     * @param string $t
     * @return multitype:
     */
    public function getField($sql = null) {
        $_result = $this->select ( $sql );
        $field = '';
        foreach ( $_result as $k => $v ) {
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
    public function getId($sql = null) {
        return mysql_insert_id ();
    }

    /**
     * 对外提供提交SQL
     */
    public function sql($sql) {
        return $this->query ( $sql );
    }

    // 释放结果集
    protected function out($result='') { }

    /**
     * 提交SQL
     */
    protected function query($sql) {
        Log::write ( $sql, 'log.sql' ); // 记录SQL
        $_stmt = $this->_pdo->prepare($sql);
        $_stmt->execute();
        return $_stmt;
    }

    // 记录SQL错误
    protected function log($result = '') {}




}
