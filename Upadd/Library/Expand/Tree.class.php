<?php
/**
* 树形类 
* Upcms-v-0.0.1 是由Upadd.cn开发和维护的一套采用MVC结构的upadd框架开发的php程序!
*-------------------------------------------------------------------------------
* 版权所有: CopyRight By upadd.cn
* 您可以自由使用该源码，但是在使用过程中，请保留作者信息。尊重他人劳动成果就是尊重自己
*-------------------------------------------------------------------------------
* @author:Richard.Z
* @Blog:http://www.zmq.cc
* @Dtime:2014/02/09/16:01
*/
defined('UPADD_HOST') or exit();
class Tree{

	/**
	 * 对象类型递归树形
	 * 第一个遍历的if判断did是父ID
	 * 第二个遍历的自建值是sun
	 * 自增ID是nid,方法中默认的值得有,nid,did,sun
	 * @param array $data 
	 * @param 默认 $pid = 0 
	 * @return NULL|multitype:unknown
	 */	
	public static function ByObjTree($data,$pid = 0){
		if(is_array($data)){
			$tree = array();
			foreach ($data as $k => $v) { 
				if ($v->did == $pid) $tree[] = $v; 
			}
			if (empty($tree)) return null;
			foreach ($tree as $k => $v) {
				$tree[$k]->sub = self::ByObjTree($data, $v->nid);
				if(!isset($tree[$k]->sub)) unset($tree[$k]->sub);
			}
			return $tree;
		}
	}
	
	/**
	 * 数组类型递归树形
	 * 第一个遍历的if判断did是父ID
	 * 第二个遍历的自建值是sun
	 * 自增ID是nid,方法中默认的值得有,nid,did,sun
	 * @param array $data 
	 * @param 默认 $pid = 0 
	 * @return NULL|multitype:unknown
	 */
	public static function ByArrTree($data,$pid = 0){
		if(is_array($data)){
			$tree = array();
			foreach ($data as $k => $v) { if ($v['did'] == $pid) $tree[] = $v; }
			if (empty($tree)) return null;
			foreach ($tree as $k => $v) {
				$tree[$k]['sub'] = self::ByArrTree($data, $v['nid']);
				if(!isset($tree[$k]['sub'])) unset($tree[$k]['sub']);
			}
			return $tree;
		}
	}
	
	/**
	 * 
	 * @param unknown $arr
	 * @param number $pid
	 * @return NULL|multitype:unknown
	 */
	static public function WxMune($arr,$pid = 0){
		$tree = array();
		//如果相等归类数组
		foreach ($arr as $k => $v) {
			if ($v->did == $pid) {
				$tree[] = $v;
			}
		}
		if (empty($tree)) {
			return null;
		}
		foreach ($tree as $k => $v) {
			//增加一个下标
			$tree[$k]->sub_button = self::WxMune($arr, $v->mid);
			//判断不存在就删除
			if(!isset($tree[$k]->sub_button)){
				unset($tree[$k]->sub_button);
			}
		}
		return $tree;
	}

	/**
	 * 对象类型递归树形 菜单
	 * 第一个遍历的if判断did是父ID
	 * 第二个遍历的自建值是sun 子数组
	 * 自增ID是mid,方法中默认的值得有,mid,did,sun
	 * @param array $data
	 * @param 默认 $pid = 0
	 * @return NULL|multitype:unknown
	 */
	public static function ByObjMenu($data,$pid = 0){
		if(is_array($data)){
			$tree = array();
			foreach ($data as $k => $v) {
				if ($v->did == $pid) $tree[] = $v;
			}
			if (empty($tree)) return null;
			foreach ($tree as $k => $v) {
				$tree[$k]->sub = self::ByObjMenu($data, $v->mid);
				if(!isset($tree[$k]->sub)) unset($tree[$k]->sub);
			}
			return $tree;
		}
	}
	
	/**
	 * 对象类型递归树形 城市
	 * 第一个遍历的if判断did是父ID
	 * 第二个遍历的自建值是zid 子数组
	 * 自增ID是mid,方法中默认的值得有,cid,did,zid
	 * @param array $data
	 * @param 默认 $pid = 0
	 * @return NULL|multitype:unknown
	 */
	public static function ByObjCity($data,$pid = 0){
		if(is_array($data)){
			$tree = array();
			foreach ($data as $k => $v) {
				if ($v->did == $pid) $tree[] = $v;
			}
			if (empty($tree)) return null;
			foreach ($tree as $k => $v) {
				$tree[$k]->zid = self::ByObjCity($data, $v->cid);
				if(!isset($tree[$k]->zid)) unset($tree[$k]->zid);
			}
			return $tree;
		}
	}
	
}
