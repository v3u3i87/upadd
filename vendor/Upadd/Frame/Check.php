<?php
namespace Upadd\Frame;
/**
 +----------------------------------------------------------------------
 | UPADD [ Can be better to Up add]
 +----------------------------------------------------------------------
 | Copyright (c) 20011-2015 http://upadd.cn All rights reserved.
 +----------------------------------------------------------------------
 | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
 +----------------------------------------------------------------------
 | Author: Richard.z <v3u3i87@gmail.com>
 **/
use Upadd\Bin\Verify;

class Check extends Verify {

    // 判断验证是否通过，默认通过
    public $_flag = true;
    // 错误消息集
    public $_message = array ();

	/**
	 * 返回提示信息
	 * @return array
	 */
	public function __info() {
		if (isset ( $this->_message ) && is_array ( $this->_message )) {
			return $this->_message;
		}
	}



    /**
     * 定义JSON输出格式
     * @param int $code
     * @param string $msg
     * @param array $data
     */
    public function msg($code=204,$msg='hi,您正在非法请求',$data=array()){
        echo json(array(
            'status'=>$code,
            'message'=>$msg,
            'data'=>$data
        ));
        exit;
    }




}
//End Check class