<?php
class Ad extends Model{		
	
	
	public function __construct(){
		parent::__construct('ad','sql');
	}
	
	public function a(){
		return $this->_find(' * ', 'id=2');
	}
		
	public function b(){
		return $this->_find(' * ', 'id=3');
	}
	
	public function c(){
		return $this->_find(' * ', 'id=11');
	}
	
	public function d(){
		return $this->_find(' * ', 'id=6');
	}
	
	public function e(){
		return $this->_show(lode(',', 'id,aid,theme,type')," aid='6' AND type='ad' " );
	}

	public function edit(){
		return $this->_edit(array('type'=>'slide')," id=2 ");
	}
	
	
	public function setTest(){
		return $this->_add(array('aid'=>6,'theme'=>1,'type'=>'ad','sort'=>mt_rand(9, 9),'img'=>NSA.mt_rand(999, 9999)));
	}
	
	
	/**
	 * 表字段处理
	 */
	public function testAdd(){
		p(call());
	}
	
	
	
	
	
}