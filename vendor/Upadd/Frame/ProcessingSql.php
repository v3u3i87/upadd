<?php

/**
 * About:Richard.z
 * Email:v3u3i87@gmail.com
 * Blog:https://www.zmq.cc
 * Date: 16/1/12
 * Time: 17:50
 * Name: SQL语句处理
 */
namespace Upadd\Frame;

use Upadd\Bin\Tool\Verify;

use Upadd\Bin\UpaddException;

class ProcessingSql{


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
     * 表前
     *
     * @var unknown
     */
    protected $db_prefix;

    /**
     * 外部参数
     * @var array
     */
    public $parameter = array();

    /**
     * where判断语句
     * @var null
     */
    public $_where = null;

    /**
     * 字段
     * @var null
     */
    public $_field = null;

    /**
     * 设置分页数
     * @var null
     */
    public $_limit = null;

    /**
     * 排序
     * @var null
     */
    public $_sort = null;

    /**
     * 搜索
     * @var null
     */
    public $_like = null;


    public $_in_where = null;

    public $_not_in_where = null;

    /**
     * 去重
     * @var null
     */
    public $_distinct = null;

    /**
     * 多表
     * @var
     */
    public $_join = null;

    public $_mergeJoin = null;


    /**
     * 查询字段
     * @param null $_field
     * @return array|null|string
     */
    protected function joint_field($_field=null)
    {
        if (Verify::IsNullString ( $_field ))
        {
            $this->_field = ' * ';

        } elseif (Verify::isArr ( $_field ))
        {
            $this->_field  = '`'.lode ("`,`", $_field ) .'`';
        } elseif (is_string ( $_field ))
        {
            $this->_field = $_field;
        }

        return $this->_field;
    }

    /**
     * 联合WHERE
     * @param null $where
     * @return null|string
     */
    protected function joint_where($where=null)
    {

        $tmp = '';
        // 数组的方式
        if (Verify::isArr ( $where ))
        {
            foreach ( $where as $k => $v )
            {
                if(Verify::isArr($v))
                {
                    foreach($v as $in=>$item)
                    {
                        $tmp .= '`'.$k.'`' .$in." '{$item}' ".' AND ';
                    }
                }else{
                    $tmp .= '`'.$k.'`' . "='{$v}'" . ' AND ';
                }

            }
            $this->_where = substr ( $tmp, 0, - 4 );

        }

        if (is_string ( $where ))
        {
            $this->_where = $where;
        }

        return $this->_where;
    }

    /**
     * 设置分页
     * @param string $limit
     * @return string
     */
    protected function setLimit($limit = '')
    {
        return $this->_limit = $limit;
    }

    /**
     * SQL语句逻辑
     * @return array|string
     */
    protected function mergeSqlLogic()
    {
        $sql[] = $this->_field;
        $sql[] = 'FROM';
        if($this->_join)
        {
            $sql[]= substr ( $this->_join, 0, - 1 );
        }else{
            $sql[]= "`$this->_table`";
        }

        //判断
        if($this->_where)
        {
            $sql[]= ' WHERE ' .$this->_where;
        }

        if($this->_in_where)
        {
            $sql[] = $this->_in_where;
        }


        if($this->_not_in_where)
        {
            $sql[] = $this->_not_in_where;
        }


        //模糊搜索
        if($this->_like)
        {
            if($this->_where)
            {
                $sql[]= ' AND '.$this->_like;
            }else{
                $sql[]= ' WHERE ' .$this->_like;
            }
        }

        //排序
        if($this->_sort)
        {
            $sql[]= $this->_sort;
        }

        //分页
        if($this->_limit)
        {
            $sql[]= $this->_limit;
        }

        return implode(' ',$sql);
    }


}