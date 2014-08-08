<?php
namespace canphp\core;
use \canphp\core\cpEvent;

class cpObject{
	static public $objStorage = array();
	public $__data = array();
	public $__method = array();
	
	public function __construct(){
	
	}
	
	//获取属性
	public function __get($name){
		return isset($this->__data[$name]) ? $this->__data[$name] : null;
	}
	
	//设置属性数据或方法
	public function __set($name, $value){
		is_callable($value)? $this->__method[$name]=$value : $this->__data[$name]=$value;
	}
	
	//实例化一个对象
	public static function newInstance(){
		$class = get_called_class();		
		$obj = call_user_func_array(array(new \ReflectionClass($class), 'newInstance'), func_get_args());
		self::$objStorage[$class] = $obj;
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
