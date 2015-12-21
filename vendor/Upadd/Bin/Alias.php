<?php
namespace Upadd\Bin;

use Upadd\Bin\UpaddException;

class Alias{

    public $_aliasData = array();


    public function __construct($setAlias=array())
    {
        if(!empty($setAlias) && is_array($setAlias))
        {
            $this->_aliasData = array_merge($this->aliasList(),$setAlias);
        }else{
            $this->_aliasData = $this->aliasList();
        }
    }


    /**
     * 定义工厂包别名,全局可使用
     * use Config;
     */
    public function aliasList(){
        return array(
            'Routes'=>'Upadd\Bin\Package\Routes',
            'Config'=>'Upadd\Bin\Package\Config',
            'Session'=>'Upadd\Bin\Package\Session',
            'Log'=>'Upadd\Bin\Package\Log',
        );
    }

    /**
     * 运行加载
     */
    public function run()
    {
        try{
            /**
             * 断点调试
             * p($this->_aliasData);
             */
            foreach ($this->_aliasData as $alias => $name)
            {

                $alias = class_alias($name,$alias);

                if(!$alias)
                {
                    throw new UpaddException($alias.'别名设置失败,'.'执行的路径:'.$name);
                }

            }

        }catch(UpaddException $e){
            echo $e->getMessage();
        }
    }




}