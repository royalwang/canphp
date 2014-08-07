<?php
namespace canphp\core;
class cpEvent extends \canphp\core\cpObject{
	protected public globalEventMap = array();//全局事件绑定
	protected objEventMap = array();//实例化对象的时间绑定
	
	//绑定事件，支持星号通配符
	static public function on($event, callble $callback){
		$eventMap = isset($this) ? &$this->objEventMap : &self::eventMap;
		$eventMap[$event][] = $callback;
	}
	
	//接触事件
	static public function off($event, callble $callback){
		$eventMap = isset($this) ? &$this->objEventMap : &self::eventMap;
		if( empty($eventMap[$event]) ) return false;
		
		//删除该事件下的所有绑定
		if(null==$callback){
			unset($eventMap[$event]);
			return true;
		}
		
		//删除该事件下的某个绑定
		foreach($eventMap[$event] as $k=>$v){
			if($v==$callback){
				unset($eventMap[$event][$k]);
				return true;
			}
		}
		return false;
	}
	
	//触发执行事件
	static public function emit($event){
		$eventMap = isset($this) ? &$this->objEventMap : &self::eventMap;
		foreach($eventMap as $k=>$v){
			$pattern = $str_replace('*', '.*?', preg_quote($k, '/'))
			if( preg_match("/{$pattern}/", $event) ){
				foreach($v as $v2){
					$ret = call_user_func_array($v2, func_get_args());
					if(false === $ret) return false;
				}
			}
		}
		return true;
	}
}
