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
    public function __construct($_total, $_pagesize) {
        $this->_total = $_total ? $_total : 1;
        $this->_pagesize = $_pagesize;
        $this->_pagenum = ceil($this->_total / $this->_pagesize);
        $this->_page = $this->SetPage();
        $this->_limit = "LIMIT ".($this->_page-1)*$this->_pagesize.",$this->_pagesize";
        $this->_bothnum = 2;
    }

    //获取当前页码
    private function SetPage() {
        if (!empty($_GET['page'])) {
            if ($_GET['page'] > 0) {
                if ($_GET['page'] > $this->_pagenum) {
                    return $this->_pagenum;
                } else {
                    return $_GET['page'];
                }
            } else {
                return 1;
            }
        } else {
            return 1;
        }
    }

    //上一页
    private function Prev() {
        if ($this->_page == 1) {
            return $this->_page;
        }
        return $this->_page-1;
    }


    //下一页
    private function Next() {
        if ($this->_page == $this->_pagenum) {
            return $this->_page;
        }
        return $this->_page+1;
    }

    //尾页
    private function Last() {
        if ($this->_pagenum - $this->_page > $this->_bothnum) {
           return $this->_pagenum;
        }
    }


    public function show(){
        $info = array(
            //当前页
            'current'=>$this->_page,
            //上一页
            'prev'=>$this->Prev(),
            //下一页
            'next'=>$this->Next(),
            //末尾页
            'last'=>$this->Last(),
            //总数
            'total'=>$this->_total,
            //默认条数
            'per_page'=>$this->_pagesize,
            //分页数
            'limit'=> $this->_limit
        );
      return $info;
    }

}
