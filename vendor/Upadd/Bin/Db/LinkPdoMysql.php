<?php
namespace Upadd\Bin\Db;

use Upadd\Bin\Tool\Log;

use PDO;
use Upadd\Bin\UpaddException;

class LinkPdoMysql implements Db{

    /**
     * 对象
     *
     * @var unknown
     */
    protected $_linkID = null;

    public $_sql = '';

    protected $queue = array();

    protected $_logSql = [];

    public function __construct($link)
    {
        try {
            $dns = "mysql:dbname={$link ['name']};host={$link ['host']};port={$link ['port']};";
            $this->_linkID = new PDO($dns,$link ['user'], $link ['pass']);
//            $this->_linkID->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $this->_linkID->exec('SET NAMES '.$link ['charset']);
        } catch (PDOException $e) {
            throw new UpaddException($e->getMessage());
        }
    }


    /**
     * 查询
     */
    public function select()
    {
        $result = $this->query();
        if($result)
        {
            return $result->fetchAll(PDO::FETCH_ASSOC);
        }
        return false;
    }

    /**
     * 查询一条
     * @param unknown $sql
     * @return $data or bool
     */
    public function find()
    {
        $result = $this->query();
        if($result)
        {
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
        if(isset($_result [0] ['Auto_increment']))
        {
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
        if($query)
        {
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
        $_result = $this->select ();
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
     * @return number
     */
    public function getId()
    {
        return $this->_linkID->lastInsertId();
    }

    /**
     * 对外提供提交SQL
     */
    public function sql($sql=null)
    {
        if($sql)
        {
            $this->_sql = $sql;
        }
        $this->log();
        $result = $this->_linkID->exec( $this->_sql );
        if($result)
        {
            return true;
        }else{
            return $this->is_debug();
        }
    }

    /**
     * 提交SQL
     */
    public function query()
    {
        $this->log();
        $result = $this->_linkID->query($this->_sql);
        if($result)
        {
            return $result;
        }else{
            return $this->is_debug();
        }
    }

    /**
     * 记录SQL错误
     */
    public function log()
    {
        Log::write ( $this->_sql, 'log.sql' ); // 记录SQL
    }


    /**
     * 开启事务
     * @return mixed
     */
    public function begin()
    {
        return $this->_linkID->beginTransaction();
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
    public function printSql($status=true)
    {
        if($status)
        {
            p($this->_sql);
        }else{
            p($this->_sql,true);
        }
    }

    /**
     * 返回错误信息
     * @return array
     */
    private function error()
    {
        $error = $this->_linkID->errorInfo();
        return [
            'msg'=>'type:'.$error[0]."\n".'code:'.$error[1]."\n".'info:'.$error[2],
            'info'=>$error
        ];
    }


    /**
     * 判断是否启用调试
     * @return bool
     * @throws UpaddException
     */
    private function is_debug()
    {
        try {
            $result = $this->error();
            $msg = $result['msg'];
            $info = $result['info'];
            if ($info[0] === '00000' || $info[0] === '01000')
            {
                return true;
            }
        }catch(\Exception $e){
            throw new UpaddException("sql:".$this->_sql.$this->error());
        }
    }

}
