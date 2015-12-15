<?php
/**
 * About:Richard.z
 * Email:v3u3i87@gmail.com
 * Blog:https://www.zmq.cc
 * Date: 15/12/12
 * Time: 17:01
 * Name:
 */

namespace Upadd\Bin\Tool;

class Session{

    public $_data = array();


    /**
     * 保存路径
     */
    public function save_path(){
        //获取当前session的保存路径
        $_session_path = session_save_path();
        echo $_session_path;
        //如果路径中存在分号
        if(strpos($_session_path,";")!==false)
        {
            //设置新的路径
            $sessionpath=substr($_session_path,strpos($_session_path,";")+1);
        }
    }


    function open($save_path,$session_name)      //定义打开函数
    {
        global $sess_save_path,$sess_session_name;     //预定义session路径及名称
        $sess_save_path=$save_path;        //定义保存路径
        $sess_session_name=$session_name;       //定义session名称
        return(true);            //返回真值
    }

    //定义关闭函数
    function close()
    {
        return(true);            //直接返回真值
    }

    //定义读取函数
    function read($id)
    {
        global $sess_save_path,$sess_session_name;     //预定义保存路径与名称
        $sess_file="$sess_save_path/sess_$id";      //定义文件
        if($fp=@fopen($sess_file,"r"))        //打开文件
        {
            $sess_data=fread($fp,filesize($sess_file));      //读取文件
            return($sess_data);          //返回读取内容
        }
        else
        {
            return("");            //如果读取失败必须返回空值
        }
    }

    //定义写入函数
    function write($id,$sess_data)
    {
        global $sess_save_path,$sess_session_name;     //预定义保存路径与名称
        $sess_file="$sess_save_path/sess_$id";      //定义文件
        if($fp=@fopen($sess_file,"w"))        //打开文件
        {
            return(fwrite($fp,$sess_data));        //执行写操作
        }
        else
        {
            return(false);           //如果打开失败返回错误
        }
    }

    //定义注销函数
    function destroy($id)
    {
        global $sess_save_path,$sess_session_name;
        $sess_file="$sess_save_path/sess_$id";      //指明文件
        return(@unlink($sess_file));         //删除session文件
    }

    //定义过期函数
    function gc($maxlifetime)
    {
        return true;            //直接返回真值
    }


}