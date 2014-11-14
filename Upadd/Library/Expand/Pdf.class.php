<?php
defined('UPADD_HOST') or exit();
//pdf设置类
class SetPdf{
	
	/**
	 * 获取PDF文件页数的函数获取
	* 文件应当对当前用户可读（linux下）
	* @param  [string] $path [文件路径]
	* @return [array]   [数组msg表示成功与否，第二位表示提示信息]
	*/
	public static function getPdfPages($path){
		if(!file_exists($path)) return array(false,"文件\"{$path}\"不存在！");
		if(!is_readable($path)) return array(false,"文件\"{$path}\"不可读！");
		// 打开文件
		$fp=@fopen($path,"r");
		if (!$fp) {
			return array('msg'=>false,'info'=>"打开文件\"{$path}\"失败");;
		}else {
			$max=0;
			while(!feof($fp)) {
				$line = fgets($fp,255);
				if (preg_match('/\/Count [0-9]+/', $line, $matches)){
					preg_match('/[0-9]+/',$matches[0], $matches2);
					if ($max<$matches2[0]) $max=$matches2[0];
				}
			}
			fclose($fp);
			// 返回页数
			return array('msg'=>true,'info'=>$max);;
		}
	}

	

	//End Set Pdf	
}

/*
 		Load('expand/SetPdf.class.php');
		$results = SetPdf::getPdfPages('cs.pdf');
		//vd($results);
		if($results['msg']){
			echo $results['page'];
		}else{
			// 在这里放置失败的处理代码
		}
 */