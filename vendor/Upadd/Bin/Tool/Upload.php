<?php
namespace Upadd\Bin\Tool;


class Upload {

    private $_fileTypeArr;
    private $_path = null;
    private $_today; //子目录

    private $_linkPath;
    private $_linkDir;
    private $_max_file_size;//超出设置最大值

    private $_name;//文件名称
    private $_tmp_name;//临时文件
    private $_error;//错误代码
    private $_size;//文件大小
    private $_type;//类型



    public function __construct($file,$max_file_siz=6120,$_fileType=array(),$_setPath=null)
    {
        //获取文件参数
        $this->_error = $file['error'];
        $this->_size = $file['size'];
        $this->_type = $file['type'];
        $this->_name = $file['name'];
        $this->_tmp_name = $file['tmp_name'];
        //则算以下
        $this->_max_file_size = $max_file_siz / 1024;
        //设置文件上传路径
        $this->_path = $_setPath;

        $this->setLoadFilePath();

        //设置文件允许的类型
        $this->_fileTypeArr = $_fileType;
        //获取连接目录设置
        $this->_linkDir = date('Ymd') . '/';
        //创建子目录名称
        $this->_today = $this->_path .'/'. $this->_linkDir;
        $this->checkErrorCode();
        $this->checkType();
        $this->checkPath();
        $this->moveload();
    }

    /**
     * 设置文件上传路径
     */
    public function setLoadFilePath(){
        if($this->_path===null){
            $this->_path = host().'data/upload';
        }
    }

    //返回路径
    public function getpath(){
        $this->_linkPath = $this->_linkPath;
        return $this->_linkPath;
    }


    //移动文件
    private function moveload(){
        if (is_uploaded_file($this->_tmp_name)){
            umask(0022);
            chmod($this->_tmp_name,0777);
            if (!move_uploaded_file($this->_tmp_name, $this->setFileNewName())){
                msg(206,'警告:上传失败!');
            }
        }else {
            msg(206,'警告:临时文件不存在!');
        }
    }

    //获取名称后重立名
    private function setFileNewName(){
        $fileArray = lode('.', $this->_name);
        $fileAttr =  $fileArray[count($fileArray)-1];
        //设置文件新名称
        $newName = md5(date('YmdHis')).date('YmdHis').mt_rand(100, 1000).'.'.$fileAttr;
        //返回的是文件目录，不含有根目录
        $this->_linkPath = $this->_linkDir.$newName;
        return $this->_today.$newName;
    }


    //判断目录
    private function checkPath(){
        //设置总目录
        if (!is_dir($this->_path) || !is_writeable($this->_path)){
            if(!mkdir($this->_path,0777)){
                msg(206,'警告:处理的总目录没有创建成功!');
            }
        }

        //设置子目录
        if (!is_dir($this->_today) || !is_writeable($this->_today)){
            if(!mkdir($this->_today,0777)){
                msg(206,'警告:处理的子目录没有创建成功!');
            }
        }
    }

    //判断类型
    private function checkType(){
        $type = lode('/',$this->_type);
        $this->_type = $type[1];
        if(!in_array($this->_type,$this->_fileTypeArr)){
            msg(206,'警告:上传的类型不合法');
        }
    }

    //判断上传错误代码
    private function checkErrorCode(){
        switch ($this->_error >=1 ){
            case 1 :
                msg(206,'警告:上传值超过了约定最大值!');
                break;

            case 2 :
                msg(206,'警告:上传值超过了最大'.$this->_max_file_size.'KB');
                break;

            case 3 :
                msg(206,'警告:上传值超过了约定最大值!');
                break;

            case 4 :
                msg(206,'警告:没有任何文件被上传!');
                break;

        }
    }

}