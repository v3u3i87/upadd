<?php
namespace Upadd\Bin;

use Upadd\Bin\Loader;
use Upadd\Bin\UpaddException;
use Upadd\Bin\Alias;

class Application{

    public static $_config = array();

    public $_work = array();

    /**
     * 工作
     */
    public function work($callable,$argv=array()){
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
        return ($this->_work = $work);
    }

    /**
     * 获取配置文件
     */
    public function getConfig(){
        return static::$_config = $this->_work['Configuration']->getConfigLoad();
    }



    public function getAlias(){
        return (new Alias($this->setAlias()));
    }

    /**
     * 设置别名
     * @return mixed
     * @throws \Upadd\Bin\UpaddException
     */
    public function setAlias(){
        if(isset(static::$_config['start']['alias'])){
            return static::$_config['start']['alias'];
        }
        throw new UpaddException('别名设置未加载..');
    }

}