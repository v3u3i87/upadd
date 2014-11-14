<?php
class IndexLogic extends Logic
{
		public $_user;
		
		public $_member;
		
		public function __construct(){
			$this->_user = $this->loadModel('ad');
			$this->_name = $this->loadModel('name');
		}
		
	 	public function a(){
	 		return $this->_user->a();
	 	}
			
	 	public function b(){
	 		return $this->_user->b();
	 	}
	 	
	 	
	 	public function c(){
	 		return $this->_user->c();
	 	}
	 	
	 	public function d(){
	 		return $this->_user->d();
	 	}

	 	public function setTest(){
	 		return $this->_user->setTest();
	 	}
	 	
	 	public function e(){
	 		return $this->_user->edit();
	 	}
	 	
	 	/**
	 	 * 添加测试
	 	 */
	 	public function testAdd(){
	 		return $this->_user->testAdd();
	 	}
	 	
	 	
}