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
is_upToken();
//控制器
class Action{
		
	/**
	 * 视图
	 * @var unknown
	 */
	public $_html;
	
	/**
	 * 验证对象
	 * @var unknown
	 */
	public $_check;
	
	/**
	 * 逻辑对象
	 * @var unknown
	 */
	public $_logic;
	
	public $_info;
	
	public function __construct(){}
	
	/**
	 * 初始化
	 * @param string $check
	 * @param string $logic
	 */
	public function run($check=null,$logic=null,$html=null){
		if ($this->_check === null) {
			$this->_check = $check;
		}		
		if ($this->_logic == null) {
			$this->_logic = $logic;
		}
		$this->setPath($html,true);
	}
	
	/**
	 * 获取模型对象
	 * @param string $name
	 * @param string $type
	 * @return unknown
	 */
	public function setMode($name=null,$type=null){
		if(!empty($name)){
			switch ($type){
				//设置Logic
				case 1 :
					$new_name = ucfirst($name).'Logic';
				break;
				//设置Check
				case 2 :	
					$new_name = ucfirst($name).'Check';
				break;
			}
			if(file_exists(CHECK_PAHT.$new_name.IS_EXP) || file_exists(LOGIC_PAHT.$new_name.IS_EXP)){
				if (class_exists($new_name)) return  new $new_name();
			}
		}
	}
	
	
	/**
	 * 设置模板路径
	 * @param string $html
	 * @param unknown $type
	 */
	public function setPath($html=null,$type){
		if($this->_html === null){
			$this->_html = Templates::getHtml();
		}
		if($type){
			$_html = $html;
			$this->_html->setAction($html);
		}else{
			$this->_html->setPath($html);
		}
	}
	
	
	/**
	 * 验证数据
	 * @param string $fun
	 * @param array $Filter
	 */
	public function check_js($fun,$Filter,$type=null){
		if(is_array($Filter) && is_string($fun)) {
			if (!$this->_check->$fun($Filter)){
				//验证不通过返回信息
				$msg = $this->_check->__info();
				if($msg){
					Load('Util_Json');
					return Json::setJson($msg);
				}else{
					return true;
				}
			}
		}
	}

	/**
	 * 验证数据
	 * @param string $fun
	 * @param array $Filter
	 */
	public function check_info($fun,$Filter,$key=''){
		if(is_array($Filter) && is_string($fun)) {
			if (!$this->_check->$fun($Filter,$key)){
				//验证不通过返回信息
				$msg = $this->_check->__info();
				if($msg){
					$this->_html->setPath('Public');
					$this->_html->setVal('url',Tool::GetPrevPage());
					$this->_html->setVal('info',$msg);
					$this->_html->setFile('Gm_info.html');
					exit;
				}else{
					return true;
				}
			}
		}
	}
	
	
	/**
	 * 操作提示
	 * @param bool $bool
	 * @param string $msg
	 * @param string $url
	 * @param string $info
	 */
	public function jump($bool,$msg,$url=null,$info){
		if(isset($bool) && !empty($bool)){
			$_url = $url;
			$_msg = $msg;
		}else{
			$_url = Tool::GetPrevPage();
			$_msg = $info;
		}
		$this->_html->setPath('Public');
		$this->_html->setVal('url',$_url);
		$this->_html->setVal('info',$_msg);
		$this->_html->setFile('Gm_jump.html');
		exit;
	}
	

	/**
	 * 主动提示信息
	 * @param unknown $msg
	 * @param string $url
	 */
	public function __info($msg,$url=null){
		if(empty($url))$url = Tool::GetPrevPage();
		$this->_html->setPath('Public');
		$this->_html->setVal('url',$url);
		$this->_html->setVal('info',$msg);
		$this->_html->setFile('Gm_jump.html');
		exit;
	}
	
	
	/**
	 * POST提交，CHECK自动验证
	 * @param 验证方法名称 $fun
	 * @param POST数据
	 * @return Ambigous <mixed, unknown, multitype:unknown, multitype:unknown >
	 */
	public function _post($fun,$post,$key=null){
		if(!empty($post) && $this->check_info($fun, $post,$key)) return true;
	}
	
	
	/**
	 * 分页处理
	 * @param 表 $total
	 * @param 分页数 $pagesize
	 * @param 对象逻辑 $in
	 * @param 对象方法 $setLimit
	 */
	 public function page($total, $pagesize,$in=null,$setLimit=null) {
	 	Load('Page');
	 	$_page = new Page($total,$pagesize);
	 	//判断当前分页方式
	 	if($in && !empty($setLimit)){
	 		$in->$setLimit($_page->GetLimit());
	 	}else{
	 		$this->_logic->getLimit($_page->GetLimit());
	 	}
	 	$this->_html->setVal('page',$_page->Show());
	 	$this->_html->setVal('num',($_page->GetPage()-1)*$pagesize);
	 }
	 
	 
}
//End Action class