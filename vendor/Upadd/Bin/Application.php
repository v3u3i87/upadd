<?php
namespace Upadd\Bin;

use Upadd\Bin\Config\Configuration;
use Upadd\Bin\Loader;
use Upadd\Bin\Alias;
use Upadd\Bin\Tool\Log;
use Upadd\Bin\UpaddException;

class Application{

    public static $_config = array();

    public $_work = array();

    /**
     * 运行
     */
    public function run($callable,$argv)
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
            $this->runRequest();
            $this->request()->run_cgi();
        }else{
            $this->request()->run_cli();
            $this->getTimeConsuming();
        }
    }

    /**
     * 获取执行时间
     */
    private function getTimeConsuming()
    {
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
        Log::request([
            'method'=>(isset($_SERVER["REQUEST_METHOD"]) ? $_SERVER["REQUEST_METHOD"] : 'cli'),
            'header'=>$_header,
            'run_time'=>$endtime,
            'param'=>$_REQUEST,
        ]);
    }

    /**
     * 获取请求对象
     * @return mixed
     */
    public function request()
    {
        return $this->_work['Request'];
    }


    /**
     * 实例化全局工作模块
     * @param $work
     */
    public function getWorkModule()
    {
        return ($this->_work = array(
            'GetConfiguration'=>new \Upadd\Bin\Config\GetConfiguration,
            'Request'=>new \Upadd\Bin\Http\Request,
            'Route'=>new \Upadd\Bin\Http\Route,
            'getSession'=>\Upadd\Bin\Session\getSession::init(),
            'Log'=>new \Upadd\Bin\Tool\Log,
            'Data'=>new \Upadd\Bin\Http\Data,
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
    protected function getConfiguration()
    {
        return ($this->_work['Configuration'] = new Configuration());
    }

    /**
     * 获取别名
     * @return \Upadd\Bin\Alias
     * @throws \Upadd\Bin\UpaddException
     */
    public function getAlias()
    {
        return (new Alias(static::$_config));
    }


    /**
     * 获取Session配置状态
     * @return mixed
     */
    private function getSessionStatus()
    {
        return static::$_config['sys']['is_session'];
    }

    /**
     * 设置 session
     * @return bool
     */
    public function setSession()
    {
        if(is_run_evn())
        {
            if ($this->getSessionStatus())
            {
                $config = static::$_config['sys']['session'];
                if ($config['domain'])
                {
                    ini_set('session.cookie_domain', $config['domain']);
                }
                if ($config['expire'])
                {
                    ini_set('session.gc_maxlifetime', $config['expire']);
                    ini_set('session.cookie_lifetime', $config['expire']);
                }
                if ($config['use_cookies'])
                {
                    ini_set('session.use_cookies', $config['use_cookies'] ? 1 : 0);
                }
                if ($config['cache_limiter'])
                {
                    session_cache_limiter($config['cache_limiter']);
                }
                if ($config['cache_expire']) {
                    session_cache_expire($config['cache_expire']);
                }

                $seeion = new \Upadd\Bin\Session\SessionFile();
                session_set_save_handler(
                    array($seeion, 'open'),
                    array($seeion, 'close'),
                    array($seeion, 'read'),
                    array($seeion, 'write'),
                    array($seeion, 'destroy'),
                    array($seeion, 'gc')
                );
                session_start();
            }
        }
    }


    /**
     * 创建配置文件目录
     * @return bool
     */
    private function is_create_confg_dir()
    {
        if( $env = $this->getConfiguration()->get('sys@environment'))
        {
            //merge in config array
            $oneEnv = array_merge_one($env);
            $osName = getMachineName();
            $configDir = $this->getConfiguration()->get('sys@config_dir');
            // 总目录
            is_create_dir($configDir);
            foreach ($env as $k => $v) {
                // 不是数字类型执行
                if (!is_numeric($k)) {
                    // 创建配置目录
                    if (!is_dir($configDir . $k)) {
                        if ($k) {
                            is_create_dir($configDir . $k);
                        }
                    }
                } else {
                    return true;
                }
                //end for
            }
            return true;
        }
    }

    /**
     * 判断是否创建
     */
    public function is_create_data_dir()
    {
        header('X-Powered-By:'.$this->getConfiguration()->get('sys@upadd_version'));
        $is_data = true;
        if($is_data)
        {
            $this->is_create_confg_dir();
            $_data_dir = $this->getConfiguration()->get('sys@data_dir');

            // 数据资源文件夹
            if (!is_dir($_data_dir))
            {
                is_create_dir($_data_dir);
            }

            // 数据资源文件夹
            if (!is_dir($_data_dir . APP_NAME))
            {
                is_create_dir($_data_dir . APP_NAME);
            }

            // 日记目录
            if (!is_dir($_data_dir . APP_NAME . '/log'))
            {
                is_create_dir($_data_dir . APP_NAME . '/log');
            }

            //创建编译文件夹
            if (!is_dir($_data_dir . APP_NAME . '/compiled'))
            {
                is_create_dir($_data_dir . APP_NAME . '/compiled');
            }

            //创建缓存文件夹
            if (!is_dir($_data_dir . APP_NAME . '/cache'))
            {
                is_create_dir($_data_dir . APP_NAME . '/cache');
            }

            //上传文件目录
            if (!is_dir($_data_dir . 'upload'))
            {
                is_create_dir($_data_dir . 'upload');
            }

        }


    }


}