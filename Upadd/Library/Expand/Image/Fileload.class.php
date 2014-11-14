<?php
//文件上传类
defined('UPADD_HOST') or exit();
class Fileload{
	
	private $_error;//错误代码
	private $_max_file_size;//超出设置最大值
	private $_type;//类型
	private $_typeArr = array('jpg','jpeg','gif','png');
	//array('image/jpeg','image/pjpeg','image/png','image/x-png','image/gif');
	//array('jpg','jpeg','gif','png');
	private $_path;//目录设置  
	private $_today; //子目录
	private $_fileName;
	private $_tmp;//临时文件
	private $_linkPath;
	private $_linktotay;

	
	public function __construct($file,$max_file_siz){
		if($_FILES[$file]){
			$this->_error = $_FILES[$file]['error'];
			$this->_max_file_size = $max_file_siz / 1024 ;//则算以下
			$this->_type = $_FILES[$file]['type'];
			//获取目录设置
			$this->_path = UPADD_HOST.IMG_PATH;
			$this->_linktotay = date('Ymd').'/';
			$this->_today = $this->_path.$this->_linktotay;//创建子目录名称
			$this->_fileName = $_FILES[$file]['name'];//获取上传的文件名称
			$this->_tmp = $_FILES[$file]['tmp_name'];
			$this->checkErrorCode();
			$this->checkType();
			$this->checkPath();
			$this->moveload();
		}
	}
	
	//返回路径
	public function getpath(){
		$path = '';
		$this->_linkPath = $path.$this->_linkPath;
		return $this->_linkPath;
	}
	
	//移动文件
	private function moveload(){
		if (is_uploaded_file($this->_tmp)){
			umask(0022);
			chmod($this->_tmp,0777);
			if (!move_uploaded_file($this->_tmp, $this->setnewName())){
					Tool::GetError('警告:上传失败!');
			}
		}else {
			Tool::GetError('警告:临时文件不存在!');
		}
	}
	
	//获取名称后重立名
	private function setnewName(){
		$nameArr = explode('.', $this->_fileName);
		$attr =  $nameArr[count($nameArr)-1];
		$newName = date('YmdHis').mt_rand(100, 1000).'.'.$attr;		
		//返回的是文件目录，不含有根目录
		$this->_linkPath =  IMG_PATH.$this->_linktotay.$newName;
		return $this->_today.$newName;
	}
	
	
	//判断目录
	private function checkPath(){
		//设置总目录
		if (!is_dir($this->_path) || !is_writeable($this->_path)){
			if(!mkdir($this->_path,0777)){
				TOOL::GetError('处理的总目录没有创建成功!');
			}
		}
		//设置子目录
		if (!is_dir($this->_today) || !is_writeable($this->_today)){
			if(!mkdir($this->_today,0777)){
				TOOL::GetError('处理的子目录没有创建成功!');
			}
		}
	}
	
	//判断类型
	private function checkType(){
		if(in_array($this->_type,$this->_typeArr)){
			Tool::GetError('警告:上传的类型不合法');
		}
	}
	
	//判断上传错误代码
	private function checkErrorCode(){
		if (!empty($this->_error)){
			switch ($this->_error){
				case 1 :
					Tool::GetError('警告:上传值超过了约定最大值!');
					break;
				case 2 :
					Tool::GetError('警告:上传值超过了最大'.$this->_max_file_size.'KB');
					break;
				case 3 :
					Tool::GetError('警告:只有部分文件被上传，但数据已全部丢失!');
					break;
				case 4 :
					Tool::GetError('警告:没有任何文件被上传!');
					break;
				default:
					Tool::GetError('警告:未知错误!');
			}
		}
	}
	
	
	
}//edn fileload
