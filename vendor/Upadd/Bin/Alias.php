<?php
namespace Upadd\Bin;

use Upadd\Bin\UpaddException;

class Alias
{

    /**
     * 配置文件
     * @var array
     */
    protected $_config = [];

    /**
     * 系统别名
     * @var array
     */
    protected $_alias = [
            'Routes'=>'Upadd\Bin\Package\Routes',
            'Config'=>'Upadd\Bin\Package\Config',
            'Session'=>'Upadd\Bin\Package\Session',
            'Log'=>'Upadd\Bin\Package\Log',
            'Data'=>'Upadd\Bin\Package\Data',
            'Model'=>'Upadd\Frame\Model',
    ];

    public function __construct(array $config)
    {
        $this->_config = $config['sys'];
    }

    /**
     * 定义工厂包别名,全局可使用
     * use Config;
     */
    public function aliasList()
    {
        return $this->setAliasList();
    }

    /**
     * 设置别名
     */
    public function setAliasList()
    {
        if($this->_config['is_alias'])
        {
            return array_merge($this->_alias,$this->_config['alias']);
        }
        return $this->_alias;
    }

    /**
     * 运行加载
     */
    public function run()
    {
        try
        {
            /**
             * 断点调试
             * p($this->_aliasData);
             */
            foreach ($this->aliasList() as $alias => $name)
            {

                $alias = class_alias($name,$alias);

                if(!$alias)
                {
                    throw new UpaddException($alias.'别名设置失败,'.'执行的路径:'.$name);
                }
            }
        }catch(UpaddException $e)
        {
            p($e->getMessage());
        }
    }




}