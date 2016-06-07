<?php

namespace Upadd\Bin\Session;

use Upadd\Bin\UpaddException;

class SessionFile{

    public $_save_path = null;

    public $_session_name = null;

    public function __construct()
    {
        $this->is_dir();
        $new_is_path  = session_save_path($this->_save_path);
        if(!$new_is_path)
        {
            ini_set('session.save_path',$this->_save_path);
        }
    }

    public function is_dir()
    {
        $this->_save_path = host() .'data/'.APP_NAME.'/session';
        if(!is_dir($this->_save_path))
        {
            is_create_dir($this->_save_path);
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

    /**
     * 读取数据
     * @param null $id
     * @return null|string
     */
    public function read($id=null)
    {
        $file = $this->getFile($id);
        if(!is_readable($file))
        {
            throw  new UpaddException('不可读取');
        }
        //打开文件
        $fp = fopen($file, "r");
        if ($fp)
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

    /**
     * 写入数据
     * @param $id
     * @param $sess_data
     * @return bool|int
     */
    public function write($id,$sess_data)
    {
        $file = $this->getFile($id);
        if(is_writable($file))
        {
            //打开文件
            $fp = fopen($file,"w");
            if ($fp)
            {
                if(fwrite($fp, $sess_data))
                {
                    fclose($fp);
                }
                return true;
            }
            return false;
        }else{
            throw new UpaddException('session写入失败');
        }


    }

    /**
     * 注销函数
     * @param $id
     * @return bool
     */
    public function destroy($id)
    {
        $file = $this->getFile($id);
        if($file)
        {
            //删除session文件
            return (unlink($file));
        }
    }


    /**
     * 获取文件
     * @param $id
     * @return bool|string
     */
    public function getFile($id=null)
    {
        //设置文件路径
        $sess_file = $this->_save_path .'/sess_'.$id;
        if(file_exists($sess_file))
        {
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
        return true;
    }


}