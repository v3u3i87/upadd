<?php
namespace Upadd\Frame;

class Check {

    // 判断验证是否通过，默认通过
    public $_flag = true;
    // 错误消息集
    public $_message = array ();

	/**
	 * 返回提示信息
	 * @return array
	 */
	public function __info()
    {
		if (isset ( $this->_message ) && is_array ( $this->_message ))
		{
			return $this->_message;
		}
	}






}