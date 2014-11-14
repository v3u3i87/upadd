<?php
class NameCheck extends Check 
{
	
	
	public function a($b){
		if (!$b) {
			$this->_message[] = '密码不得为空!';
			$this->_flag = false;
		}
		return $this->_flag;
	}
	
	
	public function b()
	{
		return true;
	}
	
	
}