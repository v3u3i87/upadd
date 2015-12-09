<?php
namespace Upadd\Bin;

use Upadd\Bin\Loader;

class Application{

    public static $_config = array();

    public $_work = array();

    /**
     * 工作
     */
    public function work($_work,$callable,$argv=array()){
        $this->getWork($_work);
        $this->getConfig();
        if(is_callable($callable))
        {
            call_user_func_array($callable,func_get_args());
        }
        $loader = new Loader();
        $loader->Run();
        $this->_work['Request']->init($this->_work,$argv);
    }

    /**
     * 获取资源
     * @param $work
     */
    public function getWork($work){
        $this->_work = $work;
    }

    /**
     * 获取配置文件
     */
    public function getConfig(){
        static::$_config = $this->_work['Configuration']->getConfigLoad();
    }




}