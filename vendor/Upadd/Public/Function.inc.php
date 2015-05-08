<?php
/**
 +----------------------------------------------------------------------
 | UPADD [ Can be better to Up add]
 +----------------------------------------------------------------------
 | Copyright (c) 2011-2015 http://upadd.cn All rights reserved.
 +----------------------------------------------------------------------
 | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
 +----------------------------------------------------------------------
 | Author: Richard.z <v3u3i87@gmail.com>
 | FileName:Upadd函数库
 **/

if ( ! function_exists('getHeader'))
{
    /**
     * 获取头信息
     */
    function getHeader()
    {
        foreach ($_SERVER as $name => $value)
        {
            if (substr($name, 0, 5) == 'HTTP_')
            {
                $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
            }
        }
        return $headers;
    }

}


if ( ! function_exists('p'))
{
    /**
     * 该函数格式化打印数组
     *
     * @param unknown $data
     * @param string $type
     *        	true as 1 为不断点执行
     */
    function p($data, $type = '') {
        echo '<pre>';
        print_r ( $data );
        echo '</pre>';
        if (! $type)
            exit ();
    }
}

if(! function_exists('vd')) {
    /**
     * 打印参数详细数据 var_dump
     *
     * @param unknown $data
     * @param string $type
     *            true as 1 为不断点执行
     */
    function vd($data, $type = '')
    {
        echo '<pre>';
        var_dump($data);
        echo '</pre>';
        if (!$type)
            exit ();
    }
}

if (! function_exists('is_exit')) {
    /**
     * 断点停止提示
     *
     * @param string $info
     */
    function is_exit($info = null)
    {
        if (!empty ($info)) {
            exit ($info);
        }
    }
}

if(! function_exists('lang')) {
    /**
     * 语言包提示
     *
     * @param unknown $key
     * @return unknown
     */
    function lang($key)
    {
        if ($key) {
            // 程序语言包
            if (defined('APP_LANG')) {
                $lang = require UPADD_HOST .VENDOR. '/Lang/' . APP_LANG . IS_EXP;
                return $lang [$key];
            } else {
                is_exit('Sorry, you have not set the language pack!');
            }
        }
    }
}

if(! function_exists('jump')) {
    /**
     * 转跳
     *
     * @param string $url
     */
    function Jump($url)
    {
        if (isset ($url)) {
            header('Location:' . $url);
            exit ();
        }
    }

}

if(! function_exists('lode')) {
    /**
     * 分割数组或字符串处理
     *
     * @param string $type
     *            : , | @
     * @param type $data
     *            : array|string
     * @internal string $type ->a=array ->explode || $type ->s=string ->implode
     * @return array|string
     */
    function lode($type, $data)
    {
        if (is_string($data)) {
            return explode($type, $data);
        } elseif (is_array($data)) {
            return implode($type, $data);
        }
    }
}

if(!function_exists('array_merge_one')) {
    /**
     * 多维数组合并成一维数组
     *
     * @param unknown $a
     * @return unknown
     */
    function array_merge_one($data)
    {
        static $one;
        foreach ($data as $v) {
            is_array($v) ? array_merge_one($v) : $one [] = $v;
        }
        return $one;
    }
}



/**
 * 序列化函数
 *
 * @param 序列化的数据 $data        	
 * @param 类型 $type
 *        	as true 序列化 | false 反序列化
 * @return string|mixed
 */
function setSequence($data, $type) {
	if ($type) {
		return serialize ( $data );
	} elseif (! $type) {
		return unserialize ( $data );
	}
}


if(!function_exists('conf')) {
    /**
     * 调用配置数组参数
     * @param string $name
     * @param string $val
     * @return string
     */
    function conf($name)
    {
        $name = lode('@', $name);
        if (count($name) < 2) {
                is_exit(lang('is_conf_no'));
        }
        $file = null;
        if ($name[0] == 'start') {
            $file = UPADD_HOST . 'bootstrap/' . $name [0] . IS_EXP;
        } else {
            $isEnv = load_conf();
            if($isEnv){
                $file = CONF_DIR . $isEnv . '/' .$name [0] . IS_EXP;
            }else{
                $file = CONF_DIR . $name [0] . IS_EXP;
            }
        }

        if (is_file($file)) {
            $conf = require $file;
            $key = $conf [$name [1]];
            return $key;
        }
    }
}


if(!function_exists('load_conf')){

    /**
     * 
     * @return Ambigous <NULL, unknown>|boolean
     */
    function load_conf(){
        $name = lode('@', 'start@environment');
        $file = UPADD_HOST . 'bootstrap/' . $name [0] . IS_EXP;
        $env = null;
        if(is_file($file)){
            $conf = require $file;
            $env = $conf [$name [1]];
        }else{
            is_exit(lang('run_is_name'));
        }
        
        if($env) {
            $osName = getMachineName();
            foreach ($env as $k => $v) {
                // 不是数字类型执行
                if (!is_numeric($k)) {
                    // 执行环境是否合法的匹配
                    if ($v) {
                        if(in_array($osName,$v)){
                            return $k;
                            break;
                        }
                    } else {
                        return false;
                    }
                } else{
                    return false;
                }
            } // End if to numeric
            
        }else{
            is_exit('没有填写环境配置,无法读取配置文件');
        }
        
    }

}


if(!function_exists('json')){
    
    /**
     * 对json进行编码或解码
     * @param null $data
     * @param int $DataType
     * @return bool
     */
    function json($data=null,$DataType=1){
        if($data){
            if(is_array($data)){
                return json_encode($data);
            }else{
                return json_decode($data,$DataType);
            }
        }else{
            is_exit(lang('is_json'));
        }
    }

}

if(!function_exists('is_dirName')) {
    /**
     * 判断目录是否存在，如果不存在就创建
     *
     * @param unknown $path
     */
    function is_dirName($dirName)
    {
        // 设置总目录
        if (!is_dir($dirName) || !is_writeable($dirName)) {
            if (!mkdir($dirName, 0777)) {
                is_exit($dirName . lang('is_dir'));
            }
        }
    }
}

if(!function_exists('is_upToken')) {
    /**
     * 令牌
     */
    function is_upToken()
    {
        defined('UPADD_HOST') or exit ('Please visit the normal!');
    }
}


if(!function_exists('array_sort_field')) {
    /**
     * 根据某字段多维数组排序
     *
     * @param unknown $array
     * @param unknown $field
     * @param string $desc
     */
    function array_sort_field(&$array, $field, $desc = false)
    {
        $fieldArr = array();
        foreach ($array as $k => $v) {

            $fieldArr [$k] = $v [$field];
        }
        $sort = $desc == false ? SORT_ASC : SORT_DESC;
        array_multisort($fieldArr, $sort, $array);
    }
}


if(!function_exists('isRunMachineName')) {

    /**
     * 判断运行环境机器名称是否合格
     * @return bool as name value
     */
    function isRunMachineName()
    {
        
        $env = conf('start@environment');
        //merge in config array
        $oneEnv = array_merge_one($env);
        $osName = getMachineName();
        if(in_array($osName,$oneEnv)){
            // 总目录
            is_dirName(CONF_DIR);
            foreach ($env as $k => $v) {
                // 不是数字类型执行
                if (!is_numeric($k)) {
                    // 创建配置目录
                    if (!is_dir(CONF_DIR . $k)) {
                        if ($k) {
                            is_dirName(CONF_DIR . $k);
                        }
                    }
                }else{
                    return true;
                }
               //end for
            }
            return true;
        }else{
            is_exit(lang('run_is_name').'isRunMachineName()->is_numeric');
        }
        
    }
    
}


if(!function_exists('m')){
    
    /**
     * 获取模型对象
     * @param unknown $table
     * @return Model
     */
    function m($table){
        if($table){
            $model = new \Upadd\Frame\Model();
            $model->setTable($table);
            return $model;
        }    
    }
    
}


if(!function_exists('getMachineName')){

    /**
     * 返回机器名称
     * @return string
     */
    function getMachineName(){
        $os = lode(" ", php_uname());
        if('/'==DIRECTORY_SEPARATOR ){
            $os =  $os[1];
        }else{
            $os =  $os[2];
        }
        $osName = $os;
        //strtolower($os);
        return $osName;
    }

}

if(!function_exists('msg')){
    /**
     * 定义JSON输出格式
     * @param int $code
     * @param string $msg
     * @param array $data
     */
    function msg($code=204,$msg='非法访问',$data=array()){
        echo json(array(
            'status'=>$code,
            'message'=>$msg,
            'data'=>$data
        ));
        exit;
    }
}


if ( ! function_exists('u'))
{
    /**
     * 该函数格式化打印数组
     *
     * @param unknown $data
     * @param string $type
     *        	true as 1 为不断点执行
     */
    function u($k=null) {
        $data = array();
        if(isset($_SERVER['REQUEST_METHOD'])){
            switch ($_SERVER['REQUEST_METHOD']){
                case 'POST':
                   break;
                case 'GET':
                 break;     
            }
        }else{
            return false;
        }
        //设置模式
         
        //设置获取方式

        //返回类型

        



    }

}
