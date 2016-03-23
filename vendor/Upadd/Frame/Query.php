<?php
namespace Upadd\Frame;

use Upadd\Bin\Db\Db;
use Upadd\Bin\Tool\Verify;
use Upadd\Bin\Tool\Log;
use Upadd\Bin\Tool\PageData;
use Upadd\Frame\ProcessingSql;
use Upadd\Bin\UpaddException;

class Query extends ProcessingSql{

    /**
     * 数据库对象
     *
     * @var unknown
     */
    private $_db;

    /**
     * 分页参数
     * @var array
     */
    public $_pageData = array();


    public function __construct(Db $db,$_table,$_primaryKey,$db_prefix)
    {
        $this->_db = $db;
        $this->_table = $_table;
        $this->_primaryKey = $_primaryKey;
        $this->db_prefix = $db_prefix;
    }

    /**
     * 查询列表
     * @param null $_field
     * @return array 有分页
     */
    public function get($_field=null){
        $this->joint_field($_field);
        $this->_db->_sql = ' SELECT ' . $this->mergeSqlLogic();
        $_data = $this->_db->select();
        if(count($this->_pageData) > 0 && $_data)
        {
            $this->_pageData['data'] = $_data;
            $_data = $this->_pageData;
        }
        return $_data;
    }


    /**
     * 单行查询
     * @param null $_field
     * @return mixed
     */
    public function find($_field=null)
    {
        $this->joint_field($_field);
        $this->_db->_sql = ' SELECT '. $this->mergeSqlLogic();
        return $this->_db->find();
    }

    /**
     * 通过主键查询
     * @param $value
     * @param null $_field
     * @return mixed
     */
    public function first($value,$_field=null)
    {
        $this->where(array($this->_primaryKey=>$value));
        return $this->find($_field);
    }

    /**
     *  多表查询
     * @param null $_table
     * @return $this
     */
    public function join($_table=array())
    {
        if(empty($_table)) return false;
        $name = '';
        foreach($_table as $k=>$v)
        {
            $name .= $this->db_prefix.$k.' as ' . $v .' ,';
        }
        $this->_join =  $name;
        return $this;
    }


    /**
     * where判断
     * @param data $_where as array|null|string
     * @return $this
     */
    public function where($_where=null)
    {
        $this->joint_where($_where);
        return $this;
    }


    /**
     * InWhere类型
     * @param $key
     * @param $data
     * @param string $type
     * @return $this
     */
    public function in_where($key,$data=array())
    {
        if($key && $data)
        {
            if(is_array($data))
            {
                $data = lode(',',$data);
            }
//            p($data);
            if ($this->_where)
            {
                $this->_in_where = " AND `{$key}` IN ({$data}) ";
            } else {
                $this->_in_where = " WHERE `{$key}` IN ({$data}) ";
            }
            return $this;
        }else{
            throw new UpaddException('缺少key或data的参数');
        }
    }

    /**
     *
     * @param $key
     * @param array $data
     * @return $this
     * @throws UpaddException
     */
    public function not_where($key,$data=null){
        if($key && $data) {
            if(is_array($data)){
                $data = lode(',',$data);
            }
            if ($this->_where) {
                $this->_not_in_where = " AND `{$key}`  NOT IN ({$data}) ";
            } else {
                $this->_not_in_where = " WHERE `{$key}`  NOT IN ({$data}) ";
            }
            return $this;
        }else{
            throw new UpaddException('缺少key或data的参数');
        }
    }

    /**
     * 去重统计
     * @param $key
     * @return $this
     */
    public function count_distinct($key,$field=null)
    {
        if($key)
        {
            $tmp = null;
            if($field)
            {
                $tmp = ", `{$field}` ";
            }
            $sql = " COUNT(distinct `{$key}`) AS `conut` ";
            if($tmp)
            {
                $sql.=$tmp;
            }
            $this->_db->_sql = 'SELECT '.$this->mergeSqlLogic();
            return $this->_db->getTotal();
        }
    }

    /**
     * 排序
     * @param unknown $sort
     * @return string
     */
    public function sort($sort, $by = true)
    {
        if ($by)
        {
            $this->_sort =  " ORDER BY `{$sort}` DESC";
        } else {
            $this->_sort =  " ORDER BY `{$sort}` ASC";
        }
        return $this;
    }

    
    /**
     * 模糊查询
     * @param unknown $key
     * @param string $_field
     * @return \Upadd\Frame\Model
     */
    public function like($key,$_field=null){
        $this->_like = $key .' LIKE '." '{$_field}' ";
        return $this;
    }


    /**
     * 构造分页参数
     * @param int $pagesize
     * @return $this
     */
    public function page($pagesize=10)
    {
        //查询条件
        $getTotal  = $this->getTotal();
        $page = new PageData($getTotal,$pagesize);
        $pageArr = $page->show();
        $this->setLimit($pageArr['limit']);
        $this->_pageData = $pageArr['data'];
        return $this;
    }

    /**
     * 查询条数
     * @param null $param in array,string
     * @return $this
     */
    public function limit($param=null)
    {
        $tmp = 'LIMIT ';
        if(is_array($param)){
            $tmp.= lode(',',$param);

        }elseif(is_string($param)){
            $tmp.= $param;
        }else{
            throw new UpaddException('limit()参数错误');
        }
        $this->setLimit($tmp);
        return $this;
    }

    /**
     * 新增
     * @param array $_data
     */
    public function add($_data) {
        $field = array ();
        $value = array ();
        foreach ( $_data as $k => $v ) {
            $field [] = $k;
            $value [] = $v;
        }
        $field = implode ( ',', $field );
        $value = implode ( "','", $value );
        $this->_db->_sql = "INSERT INTO {$this->_table} ($field) VALUES ('$value')";
        if($this->_db->sql())
        {
            return $this->getId();
        }
        return false;
    }

    /**
     * 保存数据
     * @param unknown $_data
     * @param unknown $where
     */
    public function save($_data=array(), $where=null)
    {
        if(is_array($_data) && !empty($where))
        {
            return $this->update($_data,$where);
        }

        if($this->parameter && empty($_data) && empty($this->_where))
        {
            return $this->add($this->parameter);
        }

        if($this->_where && $this->parameter)
        {
            return $this->update($this->parameter,$this->_where);
        }
        return false;
    }

    /**
     * 修改数据
     * @param $_data
     * @param $where
     * @return bool
     */
    public function update($_data, $where)
    {
        if (!is_array ( $_data )) return false;

        $_editdata = '';
        foreach ( $_data as $k => $v )
        {
            $_editdata .= " `$k`='$v',";
        }
        $_editdata = substr ( $_editdata, 0, - 1 );
        $_where = $this->joint_where($where);
        $this->_db->_sql = "UPDATE {$this->_table} SET {$_editdata}  WHERE {$_where} ";
        return $this->_db->sql();
    }

    /**
     * 批量添加
     * @param array $all
     * @return array|bool
     */
    public function addAll($all=array())
    {
        if($all){
            $keyID = array();
            foreach($all as $k=>$v)
            {
                $keyID [] = $this->add($v);
            }
            return $keyID;
        }
        return false;
    }

    /**
     * 删除信息
     * @param string $where
     */
    public function del($where = null)
    {
        $_where = $this->joint_where($where);
        $this->_db->_sql = " DELETE FROM {$this->_table} WHERE {$_where} ";
        return $this->_db->sql();
    }


    /**
     * 返回当前新增ID
     */
    public function getId()
    {
        return $this->_db->getId ();
    }

    /**
     * 获取表字段
     */
    public function getField()
    {
        $this->_db->_sql = "SHOW COLUMNS FROM {$this->_table}";
        return $this->_db->getField ();
    }

    /**
     * 获取下条自增ID
     */
    public function getNextId()
    {
        $this->_db->_sql = "SHOW TABLE STATUS LIKE `{$this->_table}` ";
        return $this->_db->getNextId ();
    }


    /**
     * 锁表 Mysql in MyISAM
     * @param number $type as true in 1 WRITE  && false in 0 READ
     */
    public function lock($type = 1)
    {
        if($type){
            $this->_db->_sql = "LOCK TABLES `{$this->_table}` WRITE";
        }else{
            $this->_db->_sql = "LOCK TABLES `{$this->_table}` READ";
        }
        return $this->_db->sql();
    }

    /**
     * 解锁 Mysql in MyISAM
     */
    public function unlock()
    {
        $this->_db->_sql = " UNLOCK TABLES ";
        return $this->_db->sql ();
    }

    /**
     * 获取当前查询条件表总数
     */
    public function getTotal()
    {
        $this->joint_field('COUNT(*) as conut ');
        $this->_db->_sql = 'SELECT '.$this->mergeSqlLogic();
        return $this->_db->getTotal();
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->parameter;
    }


    /**
     * 打印当前运行的SQL
     * @param int $type
     * @return mixed
     */
    public function printSql($status=true)
    {
        return $this->_db->printSql($status);
    }


    /**
     * 开启事务
     * @return mixed
     */
    public function begin()
    {
        return $this->_db->begin();
    }

    /**
     * 提交事务并结束
     * @return mixed
     */
    public function commit()
    {
        return $this->_db->commit();
    }

    /**
     * 回滚事务
     * @return mixed
     */
    public function rollback()
    {
        return $this->_db->rollBack();
    }

}