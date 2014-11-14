<?php
/**
	+----------------------------------------------------------------------
	| UPADD [ Can be better to Up add]
	+----------------------------------------------------------------------
	| Copyright (c) 20011-2014 http://upadd.cn All rights reserved.
	+----------------------------------------------------------------------
	| Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
	+----------------------------------------------------------------------
	| Author: Richard.z <v3u3i87@gmail.com>
 **/
is_upToken();
class Plugin extends Action{
	/**
	 * @var Request
	 */
	public $_request = null;
	
	/**
	 * 控制器名称
	 * @var string
	 */
	protected $_actionName = null;
	
	/**
	 * 插件名称
	 * @var string
	 */
	protected $_pluginName = null;
	
	/**
	 * 项目名称
	 * @var string
	 */
	protected $_projectName = null;
	
	/**
	 * 视图
	 * @var unknown
	 */
	public $_html;
	
	/**
	 * 验证对象
	 * @var unknown
	 */
	public $_check;
	
	/**
	 * 逻辑对象
	 * @var unknown
	 */
	public $_logic;
	
	public function __construct($actionName, $pluginName, $projectName){
		//控制器
		$this->_actionName = $actionName;
		
		//插件名称
		$this->_pluginName = $pluginName;
		
		//项目名称
		$this->_projectName = $projectName;

		//获取模板
		$this->_html = Templates::getHtml($actionName);
		
		//验证对象处理
		self::check();
		//逻辑层对象处理
		self::logic();
	}
	
	/**
	 * 初始化的入口
	 *
	 * @return Request
	 */
	public function init(Request $request = null)
	{
		$this->_request = $request;
	}
	
	/*
	 * 获取插件名称
	*/
	public function getPluginName()
	{
		return $this->_pluginName;
	}
	
	/*
	 * 获取项目名称
	*/
	public function getProjectName()
	{
		return $this->_projectName;
	}
	
	/*
	 * 对应调用验证层对象
	 */
	public function check()
	{
		if ($this->_check === null) {
			$className = $this->_actionName . 'Check';
			
			$pluginFile= ucwords(strtolower($this->getPluginName()));
			$projectFile= ucwords(strtolower($this->getProjectName()));
			$fileName = PLUGIN_PAHT . $pluginFile . '/' . $projectFile . '/Check/' . $className . IS_EXP;
			if (!is_file($fileName)) {
				return;
			}
		
			require_once $fileName;
			if (class_exists($className)) {
				$this->_check = new $className();
			}
		}
		return $this->_check;
	}

	/*
	 * 对应调用逻辑层对象
	*/
	public function logic()
	{
		if ($this->_logic === null) {
			$className = $this->_actionName . 'Logic';
			
			$pluginFile= ucwords(strtolower($this->getPluginName()));
			$projectFile= ucwords(strtolower($this->getProjectName()));
			$fileName = PLUGIN_PAHT . $pluginFile . '/' . $projectFile . '/Logic/' . $className . IS_EXP;
			if (!is_file($fileName)) {
				return;
			}
	
			require_once $fileName;
			if (class_exists($className)) {
				$this->_logic = new $className();
			}
		}
		return $this->_logic;
	}
	
}
//End Action class