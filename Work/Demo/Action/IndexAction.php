<?php
class IndexAction extends Action{
		
	public function index(){
// 		for ($i=0;$i<5000;$i++){
// 			if($this->_logic->setTest()){
// 				echo 'id:'.$i;
// 				echo '<br />';
// 			}
// 		}
		
		//$this->_logic->setTest();
		//p($this->_logic->e(),1);
		p($this->_logic->a(),1);
		p($this->_logic->b(),1);
		p($this->_logic->c(),1);
		p($this->_logic->d(),1);
		//$this->_html->setPath('new.html');
		$this->_html->setFile('new.html',0);
	}
	
	public function b(){
		echo '<html>
					<head><title>123</title></head>
				<body>
						<form action="http://127.0.0.1/index.php/index/c" method="post">
							<p>名称<input type="text" name="img"></p>
							<p>type<input type="text" name="type"></p>
							<p><input type="submit" name="add" value="提交"></p>
						</form>
				</body>
				</html>';
	}
	
	
	public function c(){
		if(call('add')){
			$this->check_js('TestAdd', call());
			if($this->_info){
				$this->_logic->testAdd(call());
			}else{
				echo 'asd';
				p($this->_info);
			}
			
		}
	}
	
	
	

	
	
	
	
}	