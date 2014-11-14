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

//验证码类
class ValidateCode {
		private $charset = 'ABCDEFGHKMNPRSTUVWXYZ23456789';	//随机因子
		private $code;							//验证码
		private $codelen = 4;					//验证码长度
		private $width = 145;					//宽度
		private $height = 20;					//高度
		private $img;								//图形资源句柄
		private $fontcolor;						//指定字体颜色
		
		//生成随机码
		private function createCode() {
			$_len = strlen($this->charset)-1;
			for ($i=0;$i<$this->codelen;$i++) {
				$this->code .= $this->charset[mt_rand(0,$_len)];
			}
		}
		
		//生成背景 $_SERVER["SERVER_NAME"] '.mt_rand(1,3).'
		private function createBg() {
			$this->img = imagecreatefrompng(UPADD_HOST.'/data/yan/bg_code1.png');
		}
		
		//生成文字
		private function createFont() {	
			$_x = $this->width / $this->codelen;
			for ($i=0;$i<$this->codelen;$i++) {
				$this->fontcolor = imagecolorallocate($this->img,mt_rand(200, 255),mt_rand(200, 255),mt_rand(200, 255));
				imagestring($this->img, 5, $_x * $i / 4 + $this->width / 2.6, 2, $this->code[$i], $this->fontcolor);
			}
		}
		
		//输出
		private function outPut() {
			header('Content-type:image/png');
			imagepng($this->img);
			imagedestroy($this->img);
		}
		
		//对外生成
		public function doimg() {
			$this->createBg();
			$this->createCode();
			$this->createFont();
			$this->outPut();
		}
		
		//获取验证码
		public function getCode() {
			return strtolower($this->code);
		}
		
	}
