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
defined('UPADD_HOST') or exit();
//分页类
class Page {
	private $_total;										//总记录
	private $_pagesize;								//每页显示多少条
	private $_limit;										//limit
	private $_page;										//当前页码
	private $_pagenum;								//总页码
	private $_url;											//地址
	private $_bothnum;								//两边保持数字分页的量
	
	//构造方法初始化
	public function __construct($_total, $_pagesize) {
		$this->_total = $_total ? $_total : 1;
		$this->_pagesize = $_pagesize;
		$this->_pagenum = ceil($this->_total / $this->_pagesize);
		$this->_page = $this->SetPage();
		$this->_limit = "LIMIT ".($this->_page-1)*$this->_pagesize.",$this->_pagesize";
		$this->_url = $this->SetUrl();
		$this->_bothnum = 2;
	}
	
	//获取limit
	public function GetLimit() {
		return $this->_limit;
	}
	
	//获取page
	public function GetPage() {
		return $this->_page;
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
	
	//获取地址
	private function SetUrl() {
		$_url = $_SERVER["REQUEST_URI"];
		$_par = parse_url($_url);
		if (isset($_par['query'])) {
			parse_str($_par['query'],$_query);
			unset($_query['page']);
			$_url = $_par['path'].'?'.http_build_query($_query);
		}
		return $_url;
	}
	
	//首页
	private function Home() {
		if ($this->_page > $this->_bothnum+1) {
			return ' <a href="'.$this->_url.'">首页</a> ...';
		}
	}
	
	//上一页
	private function Prev() {
		if ($this->_page == 1) {
			return '<span class="disabled">上一页</span>';
		}
		return ' <a href="'.$this->_url.'&page='.($this->_page-1).'">上一页</a> ';
	}
	
	//数字目录
	private function PageList() {
		$_pagelist = '';
		for ($i=$this->_bothnum;$i>=1;$i--) {
			$_page = $this->_page-$i;
			if ($_page < 1) continue;
			$_pagelist .= ' <a href="'.$this->_url.'&page='.$_page.'">'.$_page.'</a> ';
		}
		$_pagelist .= ' <span class="me">'.$this->_page.'</span> ';
		for ($i=1;$i<=$this->_bothnum;$i++) {
			$_page = $this->_page+$i;
			if ($_page > $this->_pagenum) break;
			$_pagelist .= ' <a href="'.$this->_url.'&page='.$_page.'">'.$_page.'</a> ';
		}
		return $_pagelist;
	}
	
	//下一页
	private function Next() {
		if ($this->_page == $this->_pagenum) {
			return '<span class="disabled">下一页</span>';
		}
		return ' <a href="'.$this->_url.'&page='.($this->_page+1).'">下一页</a> ';
	}
	
	//尾页
	private function Last() {
		if ($this->_pagenum - $this->_page > $this->_bothnum) {
			return ' ...<a href="'.$this->_url.'&page='.$this->_pagenum.'">'.$this->_pagenum.'</a> ';
		}
	}
	
	//分页信息
	public function Show() {
		$_page = '';
		$_page .= $this->Home();
		$_page .= $this->Prev();
		$_page .= $this->PageList();
		$_page .= $this->Next();
		$_page .= $this->Last();
		return $_page;
	}
	
	/////////////////////////////////////////////////////////
	private function setRew() {
		$_url = $_SERVER["REQUEST_URI"];
		$_par = parse_url($_url);
		if (isset($_par['query'])) {
			parse_str($_par['query'],$_query);
			unset($_query['page']);
			$_url = $_par['path'].'?'.http_build_query($_query);
		}
		return $_url;
	}
	
	//首页
	private function rewHome() {
		if ($this->_page > $this->_bothnum+1) {
			return ' <a href="'.$this->_rew.'">首页</a> ...';
		}
	}
	
	//上一页
	private function rewPrev() {
		if ($this->_page == 1) {
			return '<span class="disabled">上一页</span>';
		}
		if(preg_match("/page/",$this->_rew)){
			return ' <a href="'.($this->_page-1).'.html">上一页</a> ';
		}else{
			return ' <a href="page/'.($this->_page-1).'.html">上一页</a> ';
		}
	}
	
	//数字目录
	private function rewPageList() {
		$_pagelist = '';
		for ($i=$this->_bothnum;$i>=1;$i--) {
			$_page = $this->_page-$i;
			if ($_page < 1) continue;
			if(preg_match("/page/",$this->_rew)){
				$_pagelist .= ' <a href="'.$_page.'.html">'.$_page.'</a> ';
			}else{
				$_pagelist .= ' <a href="page/'.$_page.'.html">'.$_page.'</a> ';
			}
		}
		$_pagelist .= ' <span class="me">'.$this->_page.'</span> ';
		for ($i=1;$i<=$this->_bothnum;$i++) {
			$_page = $this->_page+$i;
			if ($_page > $this->_pagenum) break;
			if(preg_match("/page/",$this->_rew)){
				$_pagelist .= ' <a href="'.$_page.'.html">'.$_page.'</a> ';
			}else{
				$_pagelist .= ' <a href="page/'.$_page.'.html">'.$_page.'</a> ';
			}
		}
		return $_pagelist;
	}
	
	//下一页
	private function rewNext() {
		if ($this->_page == $this->_pagenum) {
			return '<span class="disabled">下一页</span>';
		}
		if(preg_match("/page/",$this->_rew)){
			return ' <a href="'.($this->_page+1).'.html">下一页</a> ';
		}else{
			return ' <a href="'.$this->_rew.'page/'.($this->_page+1).'.html">下一页</a> ';
		}
	}
	
	//尾页
	private function rewLast() {
		if ($this->_pagenum - $this->_page > $this->_bothnum) {
			if(preg_match("/page/",$this->_rew)){
				return ' ...<a href="'.$this->_pagenum.'.html">'.$this->_pagenum.'</a> ';
			}else{
				return ' ...<a href="page/'.$this->_pagenum.'.html">'.$this->_pagenum.'</a> ';
			}
		}
	}
	
	//rew
	public function rewShow(){
		$_page = '';
		$_page .= $this->rewPrev();
		//$_page .= $this->rewPageList();
		$_page .= $this->rewNext();
		return $_page;
	}
}
