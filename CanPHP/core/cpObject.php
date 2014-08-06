<?php
namespace canphp\core;
use namespace canphp\core\cpEvent;

class cpObject{
	static public $objStorage = array();
	public $__data = array();
	public $__method = array();
	private $_obj = null;
	
	public function __construct($obj){
		$this->_obj = $obj;
	}
	
	//获取属性
	public function __get($name){
		return isset($this->__data[$name]) ? $this->__data[$name] : null;
	}
	
	//设置属性数据或方法
	public function __set($name, $value){
		is_callable($value)? $this->__method[$name]=$value : $this->__data[$name]=$value;
	}
	
	//执行方法
	public function __call($method, $args){
		//前置调用
		$event = cpEvent::emit("call_{$class}_{$method}_before", array($this->_obj, $method), $args);
		if(false==$event) return ;
		
		if( method_exists($this->_obj, $method) ){ //调用普通方法
			$ret = call_user_func_array(array($this->_obj, $method), $args);
		}else if( isset($this->__method[$method]) ){ //调用闭包方法
			$ret = call_user_func_array($this->__method[$method], $args);
		}else{ //方法不存在		
			$event = cpEvent::emit("call_{$class}_{$method}_error", array($this->_obj, $method), $args);
			if(false==$event) return ;
		}
		
		//后置调用
		$event = cpEvent::emit("call_{$class}_{$method}_after", array($this->_obj, $method), $args, $ret);
		if(false==$event) return ;
		
		return $ret;
	}
	
	//实例化一个对象
	public static function newInstance(){
		$class = get_called_class();
		$obj = call_user_func_array(array(new \ReflectionClass($class), 'newInstance'), func_get_args());
		$selfClass = __CLASS__;
		self::$objStorage[$class] = new $selfClass($obj);
		return self::$objStorage[$class];
	}
	
	//获取一个对象，不存在则实例化，存在则直接返回，单例模式
	public static function getInstance(){
		$class = get_called_class();
		if( isset(self::$objStorage[$class]) ){
			return self::$objStorage[$class];
		}
		return call_user_func_array('self::newInstance', func_get_args());
	}
}
