<?php
/**
 * About:Richard.z
 * Email:v3u3i87@gmail.com
 * Blog:https://www.zmq.cc
 * Date: 15/12/18
 * Time: 13:28
 * Name:
 */

namespace Upadd\Bin\Session;

class SessionFile{

    public $_save_path = null;

    public $_session_name = null;

    public function __construct(){
        $this->is_dir();
        $new_is_path  = session_save_path($this->_save_path);
        if(!$new_is_path){
            ini_set('session.save_path',$this->_save_path);
        }
    }


    public function is_dir(){
        $this->_save_path =  host() .'data/session';
        if(!is_dir($this->_save_path)){
            is_dirName($this->_save_path);
        }
    }


    //定义打开函数
    public function open($save_path,$session_name)
    {
        //定义保存路径
        $this->_save_path = $save_path;
        //定义session名称
        $this->_session_name = $session_name;

        return true;
    }

    //定义关闭函数
    public function close()
    {
        return true;
    }

    //定义读取函数
    public function read($id='')
    {
        $file = $this->getSessFile($id);
        if(!is_readable($file))
        {
            exit('不可读取');
        }
        //打开文件
        if ($fp = fopen($file, "r"))
        {
            if($sess_data = @fread($fp, filesize($file)))
            {
                fclose($fp);
                //返回读取内容
                return $sess_data;
            }
        }

        return null;
    }

    //定义写入函数
    public function write($id,$sess_data)
    {
        $file = $this->getSessFile($id);
        //打开文件
        if ($fp = fopen($file,"w"))
        {
            //执行写操作
            return (fwrite($fp, $sess_data));
        }
        return false;
    }

    //定义注销函数
    public function destroy($id)
    {
        $file = $this->getSessFile($id);
        if($file)
        {
            //删除session文件
            return (unlink($file));
        }
    }


    /**
     * @param $id
     * @return bool|string
     */
    public function getSessFile($id=''){
        //设置文件路径
        $sess_file = $this->_save_path .'/sess_'.session_id();
        if(file_exists($sess_file)) {
            return $sess_file;
        }else{
            //新建文件命令
            $fopen = fopen($sess_file,'wd');
            fclose($fopen);
        }
        return $sess_file;
    }

    //定义过期函数
    function gc($maxlifetime)
    {
        return true;            //直接返回真值
    }


}