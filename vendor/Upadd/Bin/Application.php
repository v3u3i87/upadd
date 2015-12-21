<?php
namespace Upadd\Bin;

use Upadd\Bin\Config\Configuration;
use Upadd\Bin\Loader;
use Upadd\Bin\UpaddException;
use Upadd\Bin\Alias;
use Upadd\Bin\Tool\Log;

class Application{

    public static $_config = array();

    public $_work = array();


    /**
     * 工作
     */
    public function work($callable,$argv)
    {
        /**
         * 加载组件
         */
        Loader::Run();

        /**
         * 实例化对象
         */
        $this->request()->getInit($this->_work,$argv);

        /**
         * 判断运行环境
         */
        if(is_run_evn())
        {
            if(is_callable($callable))
            {
                call_user_func_array($callable,func_get_args());
            }
            $this->setSession();
            $this->runRequest();
            $this->request()->setCgi();
        }else{
            $this->request()->setCli();
            $this->getTimeConsuming();
        }

    }

    /**
     * 获取执行时间
     */
    private function getTimeConsuming(){
        echo "\n";
        $endtime = (microtime(true)) - RUNTIME;
        echo 'Time consuming '.round($endtime,3).' second';
        echo "\n";
    }


    /**
     * 记录运行时间
     * @pamer
     */
    private function runRequest()
    {
        $endtime = (microtime(true)) - RUNTIME;
        $_header = getHeader();
        Log::request(array(
            'method'=>(isset($_SERVER["REQUEST_METHOD"]) ? $_SERVER["REQUEST_METHOD"] : 'cli'),
            'header'=>$_header,
            'param'=>$_REQUEST,
            'run_time'=>$endtime,
        ));
    }

    /**
     *
     * @return mixed
     */
    public function request(){
        return $this->_work['Request'];
    }


    /**
     * 设置工作模块
     * @param array $_data
     */
    public function setWorkModule($_data=array())
    {
        if(!is_array($_data))
        {
            throw new UpaddException('新设置的工作模块无法工作,因为不是数组类型');
        }

        if(!empty($_data))
        {

            foreach($_data as $k=>$v)
            {
                $this->_work[$k] = $v;
            }

        }

    }


    /**
     * 实例化全局工作模块
     * @param $work
     */
    public function getWorkModule(){
        return ($this->_work = array(
            'GetConfiguration'=>new \Upadd\Bin\Config\GetConfiguration,
            'Request'=>new \Upadd\Bin\Http\Request,
            'Route'=>new \Upadd\Bin\Http\Route,
            'getSession'=>\Upadd\Bin\Session\getSession::init(),
            'Log'=>new \Upadd\Bin\Tool\Log,
        ));
    }




    /**
     * 获取配置文件
     */
    public function getConfig()
    {
        return (static::$_config = $this->getConfiguration()->getConfigLoad());
    }

    /**
     * 实例化全局配置文件
     * @return Configuration
     */
    private function getConfiguration()
    {
        return ($this->_work['Configuration'] = new Configuration());
    }

    /**
     * 获取别名
     * @return \Upadd\Bin\Alias
     * @throws \Upadd\Bin\UpaddException
     */
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
    }


    /**
     * 设置 $seeion
     * @return bool
     */
    public function setSession(){
        $seeion = new \Upadd\Bin\Session\SessionFile();
        session_set_save_handler(
            array($seeion ,'open'),
            array($seeion ,'close'),
            array($seeion ,'read'),
            array($seeion ,'write'),
            array($seeion ,'destroy'),
            array($seeion ,'gc')
        );
        session_start();
    }


}