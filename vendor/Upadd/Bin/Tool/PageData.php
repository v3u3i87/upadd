<?php
/**
 +----------------------------------------------------------------------
| UPADD [ Can be better to Up add]
+----------------------------------------------------------------------
| Copyright (c) 20011-2014 http://upadd.cn All rights reserved.
+----------------------------------------------------------------------
| Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
+----------------------------------------------------------------------
| Author: Richard.z <v3u3i87@gmail.com>
**/
namespace Upadd\Bin\Tool;

use Data;

class PageData {
    /**
     * 总记录
     * @var int
     */
    private $_total;

    /**
     * 每页显示多少条
     * @var
     */
    private $_pagesize;
    /**
     * 分页参数
     * @var string
     */
    private $_limit;

    /**
     * 当前页码
     * @var float|int
     */
    private $_page;

    /**
     * 总页码
     * @var float
     */
    private $_pagenum;

    /**
     * 两边保持数字分页的量
     * @var int
     */
    private $_bothnum;

    //构造方法初始化
    public function __construct($_total, $_pagesize)
    {
        $this->_total = $_total ? $_total : 1;
        $this->_pagesize = $_pagesize;
        $this->_pagenum = ceil($this->_total / $this->_pagesize);
        $this->getPageNumeber();
        $this->_limit = $this->setLimit();
        $this->_bothnum = 2;
    }

    /**
     * 设置limit
     * @return string
     */
    public function setLimit()
    {
        $tmp = 'LIMIT ';
        $tmp.= ($this->_page-1)*$this->_pagesize;
        return $tmp.= ",$this->_pagesize";
    }


    /**
     * 设置当前的页面码
     * @return float|int
     */
    private function setPage()
    {
        $pageNumeber = (int) Data::get('page',1);

        if ( $pageNumeber > $this->_pagenum)
        {
            return $this->_pagenum;
        }
        return $pageNumeber;
    }

    /**
     * 获取PAGE数量
     */
    protected function getPageNumeber()
    {
        $this->_page = $this->setPage();
    }

    /**
     * 上一页
     * @return float|int
     */
    private function prev()
    {
        if ($this->_page == 1)
        {
            return $this->_page;
        }
        return $this->_page-1;
    }


    /**
     * 下一页
     * @return float|int
     */
    private function next()
    {
        if ($this->_page == $this->_pagenum)
        {
            return $this->_page;
        }
        return $this->_page+1;
    }

    /**
     * 尾页
     * @return float
     */
    private function last()
    {
        if ($this->_pagenum - $this->_page > $this->_bothnum)
        {
           return $this->_pagenum;
        }
        return 1;
    }


    public function show()
    {
        $info['limit'] = $this->_limit;
        $info['data'] =  array(
            //当前页
            'current'=>$this->_page,
            //上一页
            'prev'=>$this->prev(),
            //下一页
            'next'=>$this->next(),
            //末尾页
            'last'=>$this->last(),
            //总数
            'total'=>$this->_total,
            //默认条数
            'per_page'=>$this->_pagesize,
        );
        return $info;
    }

}