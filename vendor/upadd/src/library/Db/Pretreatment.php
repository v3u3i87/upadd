<?php

namespace Upadd\Bin\Db;

/**
 * About:Richard.z
 * Email:v3u3i87@gmail.com
 * Blog:https://www.zmq.cc
 * Date: 2017/11/7
 * Time: 下午2:53
 * Name:
 */

use Upadd\Bin\Tool\Log;
use Upadd\Bin\Tool\PageData;
use Upadd\Bin\UpaddException;
use Upadd\Bin\Tool\Verify;

class Pretreatment extends \Upadd\Bin\Db\LinkPdoMysql
{

    /**
     * 默认库
     * @var string
     */
    public $use = 'local';

    /**
     * 是否自动创建时间戳
     * @var bool
     */
    public $_automaticityTime = false;

    /**
     * 表名
     *
     * @var unknown
     */
    public $_table = null;

    /**
     * 主键或关键字
     * @var null
     */
    public $_primaryKey = null;

    /**
     * 外部参数
     * @var array
     */
    public $parameter = [];


    /**
     * 表前
     *
     * @var unknown
     */
    protected $db_prefix;

    /**
     * 数据库连接信息
     * @var array
     */
    protected $_dbInfo = [];

    /**
     * 数据库名
     * @var null
     */
    protected $dbName = null;

    /**
     * 分页数据
     * @var array
     */
    private $_pageData = [];

    /**
     * where判断语句
     * @var null
     */
    private $_where = null;

    /**
     * 字段
     * @var null
     */
    private $_field = null;

    /**
     * 设置分页数
     * @var null
     */
    private $_limit = null;

    /**
     * 排序
     * @var null
     */
    private $_sort = null;

    /**
     * 搜索
     * @var null
     */
    private $_like = null;

    /**
     * @var null
     */
    private $_or_where = null;

    /**
     * @var null
     */
    private $_in_where = null;

    /**
     * @var null
     */
    private $_not_in_where = null;

    /**
     * 去重
     * @var null
     */
    private $_distinct = null;

    /**
     * 多表
     * @var
     */
    private $_join = null;

    /**
     * 合并
     * @var null
     */
    private $_mergeJoin = null;

    private $db = null;


    public function __construct($dbInfo = null)
    {
        if ($dbInfo !== null) {
            $this->_dbInfo = $dbInfo;
        } else {
            $this->_dbInfo = conf('database@db');
            //派发数据库
            $this->distribution();
        }
    }


    /**
     * 派发链接DB对象
     */
    private function distribution()
    {
        if (conf('database@many') === true) {
            foreach ($this->_dbInfo as $key => $value) {
                if ($this->use === $value['use']) {
                    $this->_dbInfo = $value;
                    continue;
                }
            }
        }
    }

    public function instance()
    {
        //表前
        $this->db_prefix = $this->_dbInfo['prefix'];
        //设置表名
        $this->_table = ($this->db_prefix . $this->_table);
        //数据库名
        $this->dbName = $this->_dbInfo ['name'];
        //实例化数据库链接
        $this->init($this->_dbInfo);
        //获取当前对象
        $this->db = $this->obj();
    }

    /**
     * 返回数据库所有的表名
     * @return mixed
     */
    public function getAllTables()
    {
        $this->_sql = 'show tables';
        $tmp = $this->select();
        $list = [];
        if (count($tmp) > 1) {
            $list = arrayToOne($tmp);
        }
        return $list;
    }


    /**
     * 获取表列详细信息
     * @return array|bool|mixed
     */
    public function getTablesInfo()
    {
        $this->_sql = " SHOW FULL COLUMNS FROM `{$this->dbName}`.`{$this->_table}` ";
        return $this->select();
    }


    /**
     * 批量插入
     * @param array $field
     * @param $data
     * @return bool|int
     * @throws UpaddException
     */
    public function batchInsert($field = [], $data)
    {
        if (empty($field) || empty($data) || count($data) <= 0) {
            throw new UpaddException('字段或是数据不得为空');
        }

        $fieldData = implode("`,`", $field);
        $value = [];
        //应该还有更好的执行方式,暂时还没有考虑好
        foreach ($field as $k => $v) {
            $value[] = '?';
        }
        $value = implode(",", $value);

        $this->_sql = "INSERT INTO `{$this->dbName}`.`{$this->_table}` (`$fieldData`) VALUES ({$value});";
        $prepare = $this->_db->prepare($this->_db->_sql);
        $count = count($data);
        $numbe = 0;
        foreach ($data as $k => $v) {
            if ($prepare->execute($v)) {
                $numbe += 1;
            }
        }

        if ($numbe == $count) {
            return $count;
        } else {
            return false;
        }
    }


    /**
     * 批量添加
     * @param array $all
     * @return array|bool
     */
    public function addAll($all = array())
    {
        if ($all) {
            $keyID = array();
            foreach ($all as $k => $v) {
                $keyID [] = $this->add($v);
            }
            return $keyID;
        }
        return false;
    }


    /**
     * 新增
     * @param array $_data
     */
    public function add($data, $debug = false)
    {
        if ($this->_automaticityTime == true) {
            $data['add_time'] = time();
            $data['update_time'] = time();
        }

        $val = [];
        $value = [];
        $field = [];
        foreach ($data as $k => $v) {
            $value[] = '?';
            $field[] = $k;
            $val[] = $v;
        }
        $field = implode("`,`", $field);
        $value = implode(",", $value);
        $this->_sql = "INSERT INTO `{$this->dbName}`.`{$this->_table}` (`$field`) VALUES ({$value});";
        Log::run($this->_sql);
        $prepare = $this->db->prepare($this->_sql);
        if ($prepare->execute($val)) {
            if (empty($prepare->rowCount())) {
                return false;
            } else {
                return $this->getId();
            }
        } else {
            throw new \PDOException();
        }
    }

    /**
     * 修改数据
     * @param $_data
     * @param $where
     * @return bool
     */
    public function update($_data = [], $where = null)
    {
        if (!is_array($_data)) {
            throw new UpaddException('修改数据必须为数组类型.');
        }

        if ($this->_automaticityTime == true) {
            $_data['update_time'] = time();
        }

        $upValue = [];
        $upField = [];
        foreach ($_data as $key => $v) {
            $upField[] = " `{$key}`= ? ";
            $upValue[] = $v;
        }
        $upField = lode(',', $upField);
        $_where = '';
        if (!empty($this->_where)) {
            $_where = $this->_where;
        } else {
            $_where = $this->joint_where($where);
        }
        $so = '';
        if(!empty($_where)){
            $so = " WHERE {$_where};";
        }

        $this->_sql = "UPDATE `{$this->dbName}`.`{$this->_table}` SET {$upField} {$so}";
        Log::run($this->_sql);
        $prepare = $this->db->prepare($this->_sql);
        if ($prepare->execute($upValue)) {
            if (empty($prepare->rowCount())) {
                return false;
            } else {
                return true;
            }
        } else {
            throw new \PDOException();
        }
    }


    /**
     * 自增数值
     * @param $field
     * @param int $number
     * @return bool
     * @throws UpaddException
     */
    public function since($field,$number=1)
    {
        if (!is_string($field))
        {
            throw new UpaddException('自增类型必须是字符串参数');
        }

        if ($this->_automaticityTime == true) {
            $_data['update_time'] = time();
        }

        $_editdata = " `$field` = `{$field}`+{$number}";
        $_where = '';
        if (!empty($this->_where)) {
            $_where = $this->_where;
        }
        $this->_sql = "UPDATE `{$this->_table}` SET {$_editdata}  WHERE {$_where};";
        Log::run($this->_sql);
        return $this->sql();
    }

    /**
     * @param $field
     * @param int $number
     * @return bool
     * @throws UpaddException
     */
    public function reduction($field,$number=1)
    {
        if (!is_string($field))
        {
            throw new UpaddException('自减类型必须是字符串参数');
        }

        if ($this->_automaticityTime == true) {
            $_data['update_time'] = time();
        }

        $_editdata = " `{$field}` = IF(`{$field}`<1, 0, `{$field}`-{$number})";
        $_where = '';
        if (!empty($this->_where)) {
            $_where = $this->_where;
        }
        $this->_sql = "UPDATE `{$this->_table}` SET {$_editdata}  WHERE {$_where};";
        Log::run($this->_sql);
        return $this->sql();
    }



    /**
     * 保存数据
     * @param unknown $_data
     * @param unknown $where
     */
    public function save($_data = array(), $where = null)
    {
        if (!empty($this->_where) && !empty($_data)) {
            return $this->update($_data, $this->_where);
        }

        if (is_array($_data) && !empty($where)) {
            return $this->update($_data, $where);
        }

        if ($this->parameter && empty($_data) && empty($this->_where)) {
            return $this->add($this->parameter);
        }

        if ($this->_where && $this->parameter) {
            return $this->update($this->parameter, $this->_where);
        }
        return false;
    }

    /**
     * 删除信息
     * @param string $where
     */
    public function del($where = null)
    {
        $this->_sql = " DELETE FROM {$this->_table} WHERE {$this->joint_where($where)};";
        return $this->sql();
    }

    /**
     * 排序
     * @param $key
     * @param bool $by 1/DESC,0/ASC
     * @return $this
     */
    public function sort($key, $by = true)
    {
        if ($by) {
            $this->_sort = " ORDER BY {$key} DESC";
        } else {
            $this->_sort = " ORDER BY {$key} ASC";
        }
        return $this;
    }


    /**
     * 模糊查询
     * @param unknown $key
     * @param string $_field
     * @return \Upadd\Frame\Model
     */
    public function like($key, $_field = null)
    {
        $this->_like = $key . ' LIKE ' . " '{$_field}' ";
        return $this;
    }


    /**
     * 通过主键查询
     * @param      $value
     * @param null $_field
     * @return mixed
     */
    public function first($value, $_field = null)
    {
        return $this->where(array($this->_primaryKey => $value))->find($_field);
    }

    /**
     * 通过主键查询
     * @param      $value
     * @param null $_field
     * @return mixed
     */
    public function by($value, $_field = null)
    {
        return $this->where(array($this->_primaryKey => $value))->find($_field);
    }


    /**
     * 根据数组分割
     * @param array $where
     * @return Pretreatment
     */
    public function byWhere($where=[])
    {
        $where = lode(' AND ',$where);
        return $this->where($where);
    }

    /**
     *  多表查询
     * @param null $_table
     * @return $this
     */
    public function join($_table = array())
    {
        if (empty($_table)) {
            return false;
        }
        $name = '';
        foreach ($_table as $k => $v) {
            $name .= $this->db_prefix . $k . ' as ' . $v . ' ,';
        }
        $this->_join = $name;
        return $this;
    }

    /**
     *  多表左边查询
     * @param null $_table
     * @return $this
     */
    public function join_left($_table = array())
    {
        if (empty($_table)) {
            return false;
        }
        $name = '';
        foreach ($_table as $k => $v)
        {
            if($k=='on'){
                $name .= ' ON '.$v.' ';
            }else{
                $name .= ' LEFT JOIN '.$this->db_prefix . $k . ' as ' . $v . ' ';
            }
        }

        if($this->_join)
        {
            $this->_join = substr($this->_join, 0, -1);
            $this->_join.= $name;
        }

        return $this;
    }


    /**
     * where判断
     * @param data $_where as array|null|string
     * @return $this
     */
    public function where($_where = null)
    {
        $this->joint_where($_where);
        return $this;
    }


    /**
     * OR 类型查询
     * @param array ...$or_where
     * @return $this
     * @throws UpaddException
     */
    public function or_where(...$or_where)
    {
        if (empty($or_where) || count($or_where) < 2) {
            throw new UpaddException('or_where 类型,需要传至少两个参数');
        }

        $tmp = '';
        if ($this->_where) {
            $tmp = ' AND ( ';
        } else {
            $tmp = ' WHERE ( ';
        }

        foreach ($or_where as $value) {
            if (count($value) < 2) {
                $tmp .= $this->where_arr_to_sql($value);
            } else {
                $tmp .= ' ( ' . $this->where_arr_to_sql($value) . ' ) ';
            }
            $tmp .= ' OR ';
        }

        $tmp = substr($tmp, 0, -4);
        $tmp .= ' ) ';
        $this->_or_where = $tmp;
        return $this;
    }

    /**
     * InWhere类型
     * @param        $key
     * @param        $data
     * @param string $type
     * @return $this
     */
    public function in_where($key, $data = array())
    {
        if (empty($key) && empty($data)) {
            throw new UpaddException('in_where 类型,需传key或data');
        }
        if (is_array($data)) {
            $tmp=[];
            foreach ($data as $k=>$v){
                $tmp[] = "'{$v}'";
            }
            $data = lode(',', $tmp);
        }

        if ($this->_where) {
            $this->_in_where = " AND `{$key}` IN ({$data}) ";
        } else {
            $this->_in_where = " WHERE `{$key}` IN ({$data}) ";
        }
        return $this;
    }


    /**
     *
     * @param       $key
     * @param array $data
     * @return $this
     * @throws UpaddException
     */
    public function not_where($key = null, $data = null)
    {
        if (empty($key) && empty($data)) {
            throw new UpaddException('not_where类型:传key或data');
        }
        if (is_array($data)) {
            $data = lode(',', $data);
        }
        if ($this->_where) {
            $this->_not_in_where = " AND `{$key}`  NOT IN ({$data}) ";
        } else {
            $this->_not_in_where = " WHERE `{$key}`  NOT IN ({$data}) ";
        }
        return $this;
    }


    /**
     * 批量更新函数
     * @param $data array 待更新的数据，二维数组格式
     * @param array $params array 扩展where and 类型判断
     * @param string $field string 值不同的条件，默认为id int 类型
     * @return bool|string
     */
    public function batchUpdate($data, $fieldIn, $andParams = [])
    {
        if (!is_array($data) || !$fieldIn || !is_array($andParams)) {
            return false;
        }
        $updates = $this->parseUpdate($data, $fieldIn);
        $where = $this->parseParams($andParams);
        // 获取所有键名为$field列的值，值两边加上单引号，保存在$fields数组中
        // array_column()函数需要PHP5.5.0+，如果小于这个版本，可以自己实现，
        $fields = array_column($data, $fieldIn);
        $fields = implode(',', array_map(function ($value) {
            return "'" . $value . "'";
        }, $fields));

        $this->_sql = sprintf("UPDATE `%s` SET %s WHERE `%s` IN (%s) %s ;", $this->_table, $updates, $fieldIn, $fields, $where);
        $result = $this->sql($this->_sql);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 将二维数组转换成CASE WHEN THEN的批量更新条件
     * @param $data array 二维数组
     * @param $field string 列名
     * @return string sql语句
     */
    private function parseUpdate($data, $field)
    {
        $sql = '';
        $keys = array_keys(current($data));
        foreach ($keys as $column) {

            $sql .= sprintf("`%s` = CASE `%s` \n", $column, $field);
            foreach ($data as $line) {
                $sql .= sprintf("WHEN '%s' THEN '%s' \n", $line[$field], $line[$column]);
            }
            $sql .= "END,";
        }

        return rtrim($sql, ',');
    }

    /**
     * 解析where条件
     * @param $params
     * @return array|string
     */
    private function parseParams($params)
    {
        $where = [];
        foreach ($params as $key => $value) {
            $where[] = sprintf("`%s` = '%s'", $key, $value);
        }
        return $where ? ' AND ' . implode(' AND ', $where) : '';
    }

    /**
     * 获取表字段
     * @return multitype
     */
    public function getTableField()
    {
        $this->_sql = "SHOW COLUMNS FROM {$this->_table};";
        return $this->getField();
    }

    /**
     * 获取表的下条自增ID
     */
//    public function getTableNextId()
//    {
//        $this->_sql = "SHOW TABLE STATUS `{$this->dbName}`.`{$this->_table}`; ";
//        return $this->getNextId();
//    }


    /**
     * 锁表 Mysql in MyISAM
     * @param number $type as true in 1 WRITE  && false in 0 READ
     */
    public function lock($type = 1)
    {
        if ($type) {
            $this->_sql = "LOCK TABLES `{$this->_table}` WRITE;";
        } else {
            $this->_sql = "LOCK TABLES `{$this->_table}` READ;";
        }
        return $this->sql();
    }

    /**
     * 解锁 Mysql in MyISAM
     */
    public function unlock()
    {
        $this->_sql = " UNLOCK TABLES;";
        return $this->sql();
    }

    /**
     * 获取当前查询条件表总数
     */
    public function getTablesTotal()
    {
        $this->joint_field('COUNT(*) AS `conut` ');
        $this->_sql = 'SELECT ' . $this->mergeSqlLogic() . ';';
        return $this->getTotal();
    }

    /**
     * getTotal别名
     * @return mixed
     */
    public function count()
    {
        return $this->getTablesTotal();
    }

    /**
     * @return array
     */
    public function getParameter()
    {
        return $this->parameter;
    }

    /**
     * 查询列表
     * @param null $_field
     * @return array 有分页
     */
    public function get($_field = null)
    {
        $this->joint_field($_field);
        $this->_sql = 'SELECT ' . $this->mergeSqlLogic() . ';';
        $_data = $this->select();
        if (count($this->_pageData) > 0 && $_data) {
            $this->_pageData['data'] = $_data;
            $_data = $this->_pageData;
        }
        return $_data;
    }

    /**
     * 返回指定字段
     * @param null $field
     * @return bool
     */
    public function one($field = null)
    {
        if ($field) {
            $data = $this->find();
            if (isset($data[$field])) {
                return $data[$field];
            }
        }
        return false;
    }


    /**
     * 单行查询
     * @param null $_field
     * @return mixed
     */
    public function find($_field = null)
    {
        $this->joint_field($_field);
        $merge = $this->mergeSqlLogic();
        $this->_sql = 'SELECT ' . $merge . ';';
        return $this->fetch();
    }


    /**
     * 求和
     * @param $field
     * @return mixed
     */
    public function sum($field)
    {
        $this->joint_field(" SUM({$field}) as `sum` ");
        $merge = $this->mergeSqlLogic();
        $this->_sql = 'SELECT ' . $merge . ';';
        return $this->fetch();
    }



    /**
     * 构造分页参数
     * @param int $pagesize
     * @return $this
     */
    public function page($pagesize = 10)
    {
        //查询条件
        $getTotal = $this->count();
        $page = new PageData($getTotal, $pagesize);
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
    public function limit($param = null, $num = null)
    {
        $tmp = 'LIMIT ';
        if (empty($num)) {
            if (is_array($param)) {
                $tmp .= lode(',', $param);

            } elseif (is_string($param)) {
                $tmp .= $param;
            } else {
                throw new UpaddException('limit()参数错误');
            }
        } else {
            $tmp .= $param . ',' . $num;
        }

        $this->setLimit($tmp);
        return $this;
    }

    /**
     * 设置分页
     * @param string $limit
     * @return string
     */
    private function setLimit($limit = '')
    {
        return $this->_limit = $limit;
    }


    /**
     * 查询字段
     * @param null $_field
     * @return array|null|string
     */
    private function joint_field($_field = null)
    {
        if (Verify::IsNullString($_field)) {
            $this->_field = ' * ';

        } elseif (Verify::isArr($_field)) {
            $this->_field = '`' . lode("`,`", $_field) . '`';
        } elseif (is_string($_field)) {
            $this->_field = $_field;
        }
        return $this->_field;
    }

    /**
     * 处理数组拼接成字符串
     * @param null $where
     * @return string
     */
    private function where_arr_to_sql($where = null)
    {
        $tmp = '';
        // 数组的方式
        if (Verify::isArr($where)) {
            foreach ($where as $k => $v) {
                if (Verify::isArr($v)) {
                    foreach ($v as $in => $item) {
                        $tmp .= '`' . $k . '` ' . $in;
                        if (is_array($item)) {
                            $tmp .= ' (' . implode(',', $item) . ')';
                        } else {
                            $tmp .= " '{$item}' ";
                        }
                        $tmp .= ' AND ';
                    }
                } else {
                    $tmp .= '`' . $k . '`' . "='{$v}'" . ' AND ';
                }
            }
            return substr($tmp, 0, -4);
        }
        return '';
    }

    /**
     * 联合WHERE
     * @param null $where
     * @return null|string
     */
    private function joint_where($where = null)
    {
        if (is_string($where)) {
            $this->_where = $where;
        } else {
            $this->_where = $this->where_arr_to_sql($where);
        }
        return $this->_where;
    }

    /**
     * SQL语句逻辑
     * @return array|string
     */
    private function mergeSqlLogic()
    {
        $sql = [];
        if ($this->_field) {
            $sql[] = $this->_field;
        }
        if ($this->_distinct) {
            $sql[] = ',' . $this->_distinct;
        }
        $sql[] = 'FROM';

        if ($this->_join) {
            $sql[] = substr($this->_join, 0, -1);
        } else {
            $sql[] = "`$this->_table`";
        }

        //判断
        if ($this->_where) {
            $sql[] = ' WHERE ' . $this->_where;
        }

        if ($this->_in_where) {
            $sql[] = $this->_in_where;
        }

        if ($this->_not_in_where) {
            $sql[] = $this->_not_in_where;
        }

        if ($this->_or_where) {
            $sql[] = $this->_or_where;
        }

        //模糊搜索
        if ($this->_like) {
            if ($this->_where) {
                $sql[] = ' AND ' . $this->_like;
            } else {
                $sql[] = ' WHERE ' . $this->_like;
            }
        }

        //排序
        if ($this->_sort) {
            $sql[] = $this->_sort;
        }

        //分页
        if ($this->_limit) {
            $sql[] = $this->_limit;
        }

        $sql = implode(" ", $sql);
        return $sql;
    }


}