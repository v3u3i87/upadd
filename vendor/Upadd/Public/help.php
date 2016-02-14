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
    function getHeader($headers = array())
    {
        foreach ($_SERVER as $name => $value) {
            if (substr($name, 0, 5) == 'HTTP_') {
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
    function p($data=array(), $type = null)
    {
        header ( 'Content-Type:text/html;charset=utf-8' );
        echo '<pre>';
        print_r ( $data );
        echo '</pre>';
        !$type ? exit () : null;
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
        header ( 'Content-Type:text/html;charset=utf-8' );
        echo '<pre>';
        var_dump($data);
        echo '</pre>';
        !$type ? exit () : null;
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
                $lang = require UPADD_HOST .VENDOR. '/Lang/' . APP_LANG .'.php';
                return $lang [$key];
            } else {
                exit('Sorry, you have not set the language pack!');
            }
        }
    }
}

if(!function_exists('equal')){

    function is_equal($k,$key){
        if($k === $key){
            return true;
        }else{
            return false;
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
        $val = null;
        if (is_string($data)) {
            $val = explode($type, $data);
        } elseif (is_array($data)) {
            $val = implode($type, $data);
        }
        return $val;
    }
}

if(!function_exists('array_merge_one')) {
    /**
     * 多维数组合并成一维数组
     * @param array $data
     * @return array
     */
    function array_merge_one($data=array())
    {
        if($data) {
            static $one;
            foreach ($data as $v) {
                is_array($v) ? array_merge_one($v) : $one [] = $v;
            }
            return $one;
        }
        return array();
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
    function conf($key)
    {
        return \Upadd\Bin\Config\Configuration::get($key);
    }
}


if(!function_exists('is_json')){
    /**
     * 判断JSON是否合法
     * @param null $string
     * @return bool
     */
    function is_json($string = null)
    {
        if($string = json_decode($string)){
            return true;
        }
        return false;
    }
}

if(!function_exists('json')){

    /**
     * 对json进行编码或解码
     * @param null $data
     * @param bool $type
     * @return json/array
     */
    function json($data=null,$type=true){
        if(is_array($data)){
            return json_encode($data);
        }else{
            return json_decode($data,$type);
        }
    }
}

if(!function_exists('is_dirName'))
{
    /**
     * 判断目录是否存在，如果不存在就创建
     * @param unknown $path
     */
    function is_dirName($dirName)
    {
        // 设置总目录
        if (!is_dir($dirName) || !is_writeable($dirName)) {
            if (!mkdir($dirName, 0777)) {
                exit($dirName . lang('is_dir'));
            }
        }
    }
}


if(!function_exists('array_sort_field'))
{
    /**
     * 根据某字段多维数组排序
     * @param unknown $array
     * @param unknown $field
     * @param string $desc
     */
    function array_sort_field(array $array, $field, $desc = false)
    {
        $fieldArr = array();
        foreach ($array as $k => $v) {
            $fieldArr [$k] = $v [$field];
        }
        $sort = $desc == false ? SORT_ASC : SORT_DESC;
        array_multisort($fieldArr, $sort, $array);
    }

}

if(!function_exists('getMachineName'))
{

    /**
     * 返回机器名称
     * @return string
     */
    function getMachineName()
    {
        $os = lode(" ", php_uname());
        if('/'==DIRECTORY_SEPARATOR )
        {
            $os =  $os[1];
        }else{
            $os =  $os[2];
        }
        $osName = $os;
        return $osName;
    }

}

if(!function_exists('msg'))
{
    /**
     * 对外接口数据msg
     * @param int $code
     * @param string $message
     * @param array $data
     * @param string $type or json
     */
    function msg($code=10001,$message='Unauthorized access',$data = array(),$type='json')
    {
        header('Content-type: application/json');
        exit(json(array(
            'code'=>$code,
            'msg'=>$message,
            'data'=>$data
        )));
    }
}

if(!function_exists('jump_view')){
    /**
     * 验证信息
     * @param $url
     * @param $info
     */
    function jump_view($info='注意异常,请尽快联系开发者!',$url='',$html='error.html')
    {
        $_view = new \Upadd\Bin\View\Templates();
        $_view->setDir(VENDOR);
        $_view->setPath('Html');
        if($info){
            $_view->val('name', '提示');
            if (empty($url)) {
                $url = empty($_SERVER["HTTP_REFERER"]) ? '###' : $_SERVER["HTTP_REFERER"];
            }
            $_view->val('url', $url);
            $_view->val('info', $info);
            $_view->path($html);
            exit;
        }
    }
}

if(! function_exists('jump')) {
    /**
     * 转跳
     * @param $url
     */
    function jump($url)
    {
        if ($url) {
            header('Location: ' . $url);
            exit ();
        }
    }
}

if(! function_exists('getArgs'))
{

    /**
     * 获取CLI变量
     * @param $argv
     * @return array
     */
    function getArgs($argv,$out = array())
    {
        if(!empty($argv))
        {
            array_shift($argv);
            foreach ($argv as $arg) {
                if (substr($arg, 0, 2) == '--') {
                    $eqPos = strpos($arg, '=');
                    if ($eqPos === false) {
                        $key = substr($arg, 2);
                        $out[$key] = isset($out[$key]) ? $out[$key] : true;
                    } else {
                        $key = substr($arg, 2, $eqPos - 2);
                        $out[$key] = substr($arg, $eqPos + 1);
                    }
                } else {
                    exit('you input parameters have a problem' . "\r\n" . 'exit the program...' . "\r\n" . 'If you have questions, can contact me.' . "\r\n" . 'my email: v3u3i87@gmail.com' . "\r\n");
                }
            }
        }
        return $out;
    }

}

if(!function_exists('host'))
{
    function host(){
        return UPADD_HOST;
    }

}

if(!function_exists('is_run_evn')){
    /**
     * 判断运行环境
     * @return bool
     */
    function is_run_evn()
    {
        if(php_sapi_name() === 'cli')
        {
            return false;
        }elseif(PHP_SAPI === 'cli'){
            return false;
        }else{
            return true;
        }
    }

}

if(!function_exists('getClient_id')){

    /**
     * 获取客户端ID
     * @param null $_ip
     * @return null
     */
    function getClient_id($_ip = null)
    {

        if(isset($_SERVER['HTTP_CLIENT_IP'])
            || isset($_SERVER['HTTP_X_FORWARDED_FOR'])
            || isset($_SERVER['REMOTE_ADDR']))
        {

            if (!empty($_SERVER['HTTP_CLIENT_IP']))
            {
                //check ip from share internet
                $_ip = $_SERVER['HTTP_CLIENT_IP'];
            } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
            {
                //to check ip is pass from PRoxy
                $_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            } else {
                $_ip = $_SERVER['REMOTE_ADDR'];
            }

        }

        return $_ip;
    }

}

if(!function_exists('verificationCode'))
{
    /**
     * 随机生成6位验证码
     * @param int $num 默认是6位
     * @return string
     */
    function verificationCode($num=6)
    {
        return substr(str_shuffle('1234567890'),0,$num);
    }
}


if(!function_exists('get_hash'))
{

    /**
     * 生产哈希加密
     * @param string $key
     * @return string
     */
    function get_hash($key='upadd')
    {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*()+-';
        //Random 5 times
        $random = $chars[mt_rand(0, 73)] . $chars[mt_rand(0, 73)] . $chars[mt_rand(0, 73)] . $chars[mt_rand(0, 73)] . $chars[mt_rand(0, 73)];
        $content = uniqid() . $random;
        $content.=$key;
        return sha1($content);
    }
}