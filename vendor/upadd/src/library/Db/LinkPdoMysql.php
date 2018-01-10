<?php

namespace Upadd\Bin\Db;

use PDO;
use Upadd\Bin\Tool\Log;
use Upadd\Bin\UpaddException;

class LinkPdoMysql implements Db
{

    /**
     * 对象
     *
     * @var unknown
     */
    protected $_linkID = null;

    public $_sql = '';

    protected $queue = array();

    protected $_logSql = [];

    protected static $links = [];

    public function init($link)
    {
        try {
            $this->_linkID = self::getDbInstance($link);
        } catch (PDOException $e) {
            throw new UpaddException($e->getMessage());
        }
    }


    public static function getDbInstance($link)
    {
        $name = $link ['name'];
        if (is_run_evn()) {//非命令行
            if (!isset(self::$links[$name])) {
                self::$links[$name] = self::getLink($link);
            }
            return self::$links[$name];
        } else {//命令行模式
            return self::getLink($link);;
        }
    }


    public static function getLink($link)
    {
        $dns = "mysql:dbname={$link ['name']};host={$link ['host']};port={$link ['port']};";
        $tmpLink = new PDO($dns, $link ['user'], $link ['pass']);
        $tmpLink->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $tmpLink->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $tmpLink->exec('SET NAMES ' . $link ['charset']);
        return $tmpLink;
    }


    /**
     * 查询
     */
    public function select()
    {
        $result = $this->query();
        if ($result) {
            return $result->fetchAll(PDO::FETCH_ASSOC);
        }
        return false;
    }

    /**
     * 查询一条
     * @param unknown $sql
     * @return $data or bool
     */
    public function fetch()
    {
        $result = $this->query();
        if ($result) {
            return $result->fetch(PDO::FETCH_ASSOC);
        }
    }

    /**
     * 获取下条自增ID
     * @param unknown $sql
     * @return multitype:multitype:
     */
    public function getNextId()
    {
        $_result = $this->select();
        if (isset($_result [0] ['Auto_increment'])) {
            return $_result [0] ['Auto_increment'];
        }
        throw new UpaddException('获取下条自增ID失败');
    }

    /**
     * 获取表总行数
     * @param unknown $sql
     */
    public function getTotal()
    {
        $query = $this->query();
        if ($query) {
            return $query->fetchColumn();
        }
        return 0;
    }

    /**
     * 获取表字段 并返回索引数组
     * @name
     * @param string $t
     * @return multitype:
     */
    public function getField()
    {
        $_result = $this->select();
        $field = '';
        foreach ($_result as $k => $v) {
            $field .= $v ['Field'] . ',';
        }
        $field = substr($field, 0, -1);
        $field = explode(',', $field);
        return $field;
    }

    /**
     * 返回当前新增ID
     * @return number
     */
    public function getId()
    {
        return $this->_linkID->lastInsertId();
    }

    /**
     * 对外提供提交SQL
     * @param null $sql
     * @return bool
     * @throws UpaddException
     */
    public function sql($sql = null)
    {
        if ($sql) {
            $this->_sql = $sql;
        }
        $this->log();
        $result = $this->_linkID->exec($this->_sql);
        if ($result) {
            return true;
        }
        return false;
    }

    public function obj()
    {
        return $this->_linkID;
    }

    /**
     * 提交SQL
     * @return bool|\PDOStatement
     * @throws UpaddException
     */
    public function query()
    {
        $this->log();
        $result = $this->_linkID->query($this->_sql);
        if ($result) {
            return $result;
        }
        return false;
    }

    /**
     * 记录SQL错误
     */
    public function log()
    {
        Log::run($this->_sql . "\n");
    }


    /**
     * 开启事务
     * @return mixed
     */
    public function begin($callable = null)
    {
        if (is_callable($callable)) {
            try {
                $this->_linkID->beginTransaction();
                $isOk = call_user_func_array($callable, func_get_args());
                if ($isOk !== false)
                {
                    $this->_linkID->commit();
                    return $isOk;
                }
            } catch (\PDOException $e) {
                $this->_linkID->rollBack();
                return false;
            }
        } else {
            return $this->_linkID->beginTransaction();
        }
    }

    /**
     * 提交事务并结束
     * @return mixed
     */
    public function commit()
    {
        return $this->_linkID->commit();
    }

    /**
     * 回滚事务
     * @return mixed
     */
    public function rollback()
    {
        return $this->_linkID->rollBack();
    }

    /**
     * 返回一条SQL语句s
     * @param $type as exit or
     * @return mixed
     */
    public function p($status = true)
    {
        if ($status) {
            p($this->_sql);
        } else {
            p($this->_sql, true);
        }
    }

    /**
     * 返回错误信息
     * @return array
     */
    public function error()
    {
        return $this->_linkID->errorInfo();
    }

    /**
     * 提交失败打印
     */
    public function debug()
    {
        var_dump($this->error());
    }

    public function close()
    {
        $this->_linkID = null;
    }

//    private function __destruct()
//    {
//        // TODO: Implement __destruct() method.
//        $this->close();
//    }

}