<?php
class IndexCheck extends Check 
{
	
	
	public function TestAdd($data){
		if(self::IsNullString($data['img'])){
			$this->_message[] = 'img不得为空！';
			$this->_flag = false;
		}
		
		if(self::IsNullString($data['type'])){
			$this->_message[] = 'type不得为空！';
			$this->_flag = false;
		}
		
		return $this->_flag;
	}
	
	
	public function checkMail()
	{
		return true;
	}
	
	
}