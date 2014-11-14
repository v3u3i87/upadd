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

/**
* @Demo
* $it = new ImgThumb('pic',$_POST['MAX_FILE_SIZE']);//缩略图
* $path = $it->getpath();//返回路径
* $it->thumb(160,60);
* $it->getimg();
**/

//图片上传生成缩略图
defined('UPADD_HOST') or exit();
class ImgThumb{
	//文件类型
	private $_error;//错误代码
	private $_max_file_size;//超出设置最大值
	private $_type;//类型
	private $_typeArr = array('image/jpeg','image/pjpeg','image/png','image/x-png','image/gif');
	private $_path;//目录设置   预计规划是策略模式
	private $_today;
	private $_fileName;
	private $_tmp;//临时文件
	private $_linkPath;
	private $_linktotay;
	
	//图片处理
	private $_file;
	private $_widht;
	private $_height;
	private $_imgtype;
	private $_img;
	private $_newimg;
	
	private $conf ;//获取配置文件
	
	/**
	 * 传入2个值，一个是文件name="file"，一个是最大上传度
	 * @param unknown $file
	 * @param unknown $max_file_siz
	 */
	public function __construct($file,$max_file_siz){
		$this->conf = conf::getConf();
		$this->_error = $_FILES[$file]['error'];
		$this->_max_file_size = $max_file_siz / 1024 ;//计算最大上传
		$this->_type = $_FILES[$file]['type'];
		$this->_path = UPADD_HOST.$this->conf->img_path;//获取目录设置
		$this->_linktotay = date('Ymd').'/';
		$this->_today = $this->_path.$this->_linktotay;//创建子目录名称
		$this->_fileName = $_FILES[$file]['name'];//获取上传的文件名称
		$this->_tmp = $_FILES[$file]['tmp_name'];
		$this->checkErrorCode();
		$this->checkType();
		$this->checkPath();
		$this->moveload();
		//生成缩略图
		$this->_file = $_SERVER["DOCUMENT_ROOT"].$this->getpath();
		//获取图片信息，并且进行数组分割成变量
		list($this->_widht,$this->_height,$this->_imgtype) = getimagesize($this->_file);
		//获取图片类型
		$this->_img = $this->setTypeimg($this->_file, $this->_imgtype);
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
			chmod($this->_tmp,0755);
			if (!move_uploaded_file($this->_tmp, $this->setnewName())){
				Tool::GetError('警告:上传失败!');
			}
		}else {
			Tool::GetError('警告:临时文件不存在!');
		}
	}
	
	//获取文件格式，然后重新立名
	private function setnewName(){
		$nameArr = explode('.', $this->_fileName);
		$attr =  $nameArr[count($nameArr)-1];
		$newName = date('YmdHis').mt_rand(100, 1000).'.'.$attr;
		$this->_linkPath = $this->conf->img_path.$this->_linktotay.$newName;
		return $this->_today.$newName;
	}
	
	
	//判断目录 是否存在，如果不存在就创建，在linux下有问题
	private function checkPath(){
		//设置总目录
		if (!is_dir($this->_path) || !is_writeable($this->_path)){
			if(!mkdir($this->_path,0775)){
				TOOL::GetError('处理的总目录没有创建成功!');
			}
		}
		//设置子目录
		if (!is_dir($this->_today) || !is_writeable($this->_today)){
			if(!mkdir($this->_today,0775)){
				TOOL::GetError('处理的子目录没有创建成功!');
			}
		}
	}
	
	//判断类型
	private function checkType(){
		if(!in_array($this->_type,$this->_typeArr)){
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
	
	//百分比
	public function Per_thumb($per){
		$new_w = $this->_widht * ($per / 100);
		$new_h = $this->_height * ($per / 100);
		$this->_newimg = imagecreatetruecolor($new_w, $new_h);
		//复制一份高质量的图片
		imagecopyresampled($this->_newimg,$this->_img, 0, 0, 0, 0, $new_w, $new_h, $this->_widht, $this->_height);
	}
	
	//固定比例
	public function thumb($new_w=0,$new_h=0){
		
		if(empty($new_w) && empty($new_h)){
			$new_w = $this->_widht;
			$new_h = $this->_height;
		}
		
		if(!is_numeric($new_w) || !is_numeric($new_h)){
			$new_w = $this->_widht;
			$new_h = $this->_height;
		}
		
		$n_w = $new_w;
		$n_h = $new_h;
		
		$j_w = 0;
		$j_h = 0;
		
		if ($this->_widht < $this->_height) {
			$new_w = ($new_h/$this->_height)*$this->_widht;
		} else {
			$new_h = ($new_w/$this->_widht)*$this->_height;
		}	
		
		if ($new_w < $n_w) { //如果新高度小于新容器高度
			$r = $n_w / $new_w; //按长度求出等比例因子
			$new_w *= $r; //扩展填充后的长度
			$new_h *= $r; //扩展填充后的高度
			$j_h = ($this->_height - $n_w) / 4; //求出裁剪点的高度
		}
		
		if ($new_h < $n_h) { //如果新高度小于容器高度
			$r = $n_h / $new_h; //按高度求出等比例因子
			$new_w *= $r; //扩展填充后的长度
			$new_h *= $r; //扩展填充后的高度
			$j_w = ($this->_widht - $n_h) / 4; //求出裁剪点的长度
		}
	
		$this->_newimg = imagecreatetruecolor($n_w, $n_h);
		//复制一份高质量的图片
		imagecopyresampled($this->_newimg,$this->_img, 0, 0, $j_w,$j_h, $new_w, $new_h, $this->_widht, $this->_height);
	}
	
	

	//判断图片类型
	private function setTypeimg($file,$type){
		switch ($type){
				case 1 :
					$img = imagecreatefromgif($file);
					break;
				case 2 :
					$img = imagecreatefromjpeg($file);
					break;
				case 3 :
					$img = imagecreatefrompng($file);
					break;
				default:
					Tool::GetError('警告：此图片系统暂不支持!');
		}
		return $img;				
	}
	
	//类型判断输出
	public function getimg(){
		imagepng($this->_newimg,$this->_file);
		imagedestroy($this->_img);
		imagedestroy($this->_newimg);
	}
	
}
