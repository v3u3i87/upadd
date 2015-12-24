<?php
namespace Upadd\Frame;

use Upadd\Bin\Db\Db;
use Upadd\Bin\Tool\Verify;
use Upadd\Bin\Tool\Log;
use Upadd\Bin\Tool\PageData;
use Upadd\Bin\UpaddException;

class Query{

    /**
     * 表名
     *
     * @var unknown
     */
    protected $_table = null;

    /**
     * 主键或关键字
     * @var null
     */
    protected $_primaryKey = null;

    /**
     * 数据库对象
     *
     * @var unknown
     */
    private $_db;

    /**
     * 表前
     *
     * @var unknown
     */
    protected $db_prefix;

    /**
     * 拼凑SQL语句
     * @var array
     */
    protected $_sql = array('from'=>'','join'=>'', 'where'=>'','in_where'=>'','not_where'=>'','like'=>'','order'=>'','limit'=>'');

    /**
     * 合并多表查询
     * @var null
     */
    public $_mergeJoin = null;

    /**
     * 分页参数
     * @var array
     */
    public $_pageData = array();


    /**
     * 临时保存数据
     * @var array
     */
    protected $_data = array();

    public function __construct(Db $db,$_table,$_primaryKey,$data,$db_prefix)
    {
        $this->_db = $db;
        $this->_table = $_table;
        $this->_primaryKey = $_primaryKey;
        $this->_data = $data;
        $this->db_prefix = $db_prefix;
    }

    /**
     * 查询列表
     * @param null $_field
     * @return array 有分页
     */
    public function get($_field=null){
        $sql = ' SELECT ' . $this->lodeField($_field) . $this->mergeSql();
        $_data = $this->_db->select($sql);
        if(count($this->_pageData) > 0){
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
    public function find($_field=null){
        return $this->_db->find(' SELECT '. $this->lodeField($_field) . $this->mergeSql());
    }


    /**
     *  多表查询
     * @param null $_table
     * @return $this
     */
    public function join($_table=null,$as=null){
        if(is_array($_table)){
            $name = '';
            foreach($_table as $k=>$v){
                $name .= $this->db_prefix.$k.' as ' . $v .' ,';
            }
            $this->_sql['join'] =  $name;
        }else{
            $this->_sql['join'][] = $this->db_prefix.$_table.' as ' . $as .' ,';
        }
        return $this;
    }


    /**
     * where判断
     * @param data $_where as array|null|string
     * @return $this
     */
    public function where($_where=null){
        $this->_sql['where'] = $this->lodeWhere($_where);
        return $this;
    }


    /**
     * InWhere类型
     * @param $key
     * @param $data
     * @param string $type
     * @return $this
     */
    public function in_where($key,$data,$type='IN'){
        if($key && $data) {
            if(is_array($data)){
                $data = lode(',',$data);
            }
            if ($this->_sql['where']) {
                $this->_sql['in_where'] = ' AND ' . $key ." {$type} ({$data}) ";
            } else {
                $this->_sql['in_where'] = ' WHERE ' . $key ." {$type} ({$data}) ";
            }
            return $this;
        }else{
            throw new UpaddException('缺少key或data的参数');
        }
    }


    /**
     * 排序
     * @param unknown $sort
     * @return string
     */
    public function sort($sort, $by = 1) {
        if ($by) {
            $this->_sql['order'] =  " ORDER BY {$sort} DESC";
        } else {
            $this->_sql['order'] =  " ORDER BY {$sort} ASC";
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
        $this->_sql['like']  = $key .' LIKE '." '%{$_field}%' ";
        return $this;
    }


    /**
     * 合并SQL语句
     * @return array|string
     */
    private function mergeSql($type = null){
        $this->is_mergeJoin();

        if($this->_mergeJoin){
            $this->_sql['from']  = ' FROM '.$this->_mergeJoin;
        }else{
            $this->_sql['from']  = ' FROM '.$this->_table;
        }
        return $this->is_mergeTypeSql($type);
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
            unset($this->_sql['join']);
        }
    }

    /**
     * 判断合并类型
     * @param $type
     * @return array|string
     */
    private function is_mergeTypeSql($type){
        $lode = '';
        if($type){
            if($type === 'from'){
                $lode = $this->_sql['from'];
                if($this->_sql['where']){
                    $lode.= $this->_sql['where'];
                }
            }
        }else{
            $lode = lode(' ',array_filter($this->_sql));
        }
        return $lode;
    }


    /**
     * 查询字段
     * @param null $_field
     * @return array|null|string
     */
    private function lodeField($_field=null){
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
    private function lodeWhere($where=null,$_inWhere = ''){
        // 拼接WHERE
        $where !== null ? $_inWhere = ' WHERE ' : null;
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
     * 构造分页参数
     * @param int $pagesize
     * @return $this
     */
    public function limit($pagesize=10){
        //查询条件
        $getTotal  = $this->getTotal();
        $page = new PageData($getTotal,$pagesize);
        $pageArr = $page->show();
        if(isset($pageArr['limit'])){
            $this->_sql['limit'] = $pageArr['limit'];
            unset($pageArr['limit']);
            $this->_pageData = $pageArr;
        }
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
        $_sql = "INSERT INTO {$this->_table} ($field) VALUES ('$value')";
        if($this->_db->sql ( $_sql )){
            return $this->getId();
        }
        return false;
    }

    /**
     * 修改
     * @param unknown $_data
     * @param unknown $where
     */
    public function save($_data, $where) {
        if (is_array ( $_data )) {
            $_editdata = '';
            foreach ( $_data as $k => $v ) {
                $_editdata .= " $k='$v',";
            }
            $_editdata = substr ( $_editdata, 0, - 1 );
        }
        $_where = $this->lodeWhere($where);
        $_sql = "UPDATE {$this->_table} SET {$_editdata}  {$_where} ";
        return $this->_db->sql ( $_sql );
    }

    /**
     * 删除信息
     * @param string $where
     */
    public function del($where = null) {
        $_where = $this->lodeWhere($where);
        $_sql = "DELETE FROM {$this->_table} {$_where} ";
        return $this->_db->sql ( $_sql );
    }


    /**
     * 返回当前新增ID
     */
    public function getId() {
        return $this->_db->getId ();
    }

    /**
     * 获取表字段
     */
    public function getField() {
        $sql = "SHOW COLUMNS FROM {$this->_table}";
        return $this->_db->getField ( $sql );
    }

    /**
     * 获取下条自增ID
     */
    public function getNextId() {
        $_sql = "SHOW TABLE STATUS LIKE '{$this->_table}'";
        return $this->_db->getNextId ( $_sql );
    }


    /**
     * 锁表 Mysql in MyISAM
     * @param number $type as true in 1 WRITE  && false in 0 READ
     */
    public function lock($type = 1){
        if($type){
            $sql = "LOCK TABLES `{$this->_table}` WRITE";
        }else{
            $sql = "LOCK TABLES `{$this->_table}` READ";
        }
        return $this->_db->sql ( $sql );
    }

    /**
     * 解锁 Mysql in MyISAM
     */
    public function unlock(){
        $sql = " UNLOCK TABLES ";
        return $this->_db->sql ( $sql );
    }


    /**
     * 打印当前运行的SQL
     * @param int $type
     * @return mixed
     */
    public function printSql($type=1){
        return $this->_db->printSql($type);
    }


    /**
     * 开启事务
     * @return mixed
     */
    public function begin(){
        return $this->_db->begin();
    }

    /**
     * 提交事务并结束
     * @return mixed
     */
    public function commit(){
        return $this->_db->commit();
    }

    /**
     * 回滚事务
     * @return mixed
     */
    public function rollback(){
        return $this->_db->rollBack();
    }


    /**
     * 获取当前查询条件表总数
     */
    public function getTotal(){
        $sql = 'SELECT COUNT(*) as conut '.$this->mergeSql('from');
        return $this->_db->getTotal ( $sql );
    }




}