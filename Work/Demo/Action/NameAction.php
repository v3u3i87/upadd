<?php
class NameAction extends Action{
	
	public function index(){
		echo 'abc';
		echo '<br />';
		$name = $this->check_js(0, 'a');
		$this->_html->setVal('name',$name);
		$this->_html->setFile('new.html');
	}
	
	
	
	public function b(){
		$this->_html->setPath('public');
		$this->_html->setVal('name',$this->_logic->abc());
		$this->_html->setFile('abc.html');
	}
	

	
	
	
	
}	