<?php
namespace canphp\core;
class cpEvent{
	static public eventMap = array();
	
	//绑定事件，支持星号通配符
	static public function on($event, callble $callback){
		self::eventMap[$event][] = $callback;
	}
	
	//接触事件
	static public function off($event, callble $callback){
		if( !empty(self::eventMap[$event]) ){
			if(null==$callback){
				unset(self::eventMap[$event]);
				return true;
			}else{
				foreach(self::eventMap[$event] as $k=>$v){
					if($v==$callback){
						unset(self::eventMap[$event][$k]);
						return true;
					}
				}
			}
		}
		return false;
	}
	
	//执行事件
	static public function emit($event){
		foreach(self::eventMap as $k=>$v){
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
