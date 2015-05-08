<?php
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
namespace Upadd\Bin;

class Conf {
	protected static $in;

	protected $_data = array ();

	final protected function __construct($file) {
        $this->_data =  require $file;
    }

	final public function __clone() {}

	public static function getConf($file = null) {
		if (! (self::$in instanceof self)) {
			self::$in = new self ( $file );
		}
        return self::$in;
	}

	public function __get($key) {
		if (array_key_exists ( $key, $this->_data )) {
			return $this->_data [$key];
		} else {
			return null;
		}
	}

	public function __set($key, $value) {
		$this->_data [$key] = $value;
	}




}
