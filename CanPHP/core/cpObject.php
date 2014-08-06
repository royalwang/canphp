<?php
namespace canphp\core;
class cpObject{
	static protected $objStorage = array();
	protected $_obj = null;
		
	public function __construct($obj){
		$this->_obj = $obj;
	}
	
	public function __call($method, $args){
		$rfMethod = new \ReflectionMethod($this->_obj, $method);
		$ret = call_user_func_array(array($this->_obj, $method), $args);
		$end_time = microtime(true);
		return $ret;
	}
	
	public static function newInstance(){
		$class = get_called_class();
		$obj = call_user_func_array(array(new \ReflectionClass($class), 'newInstance'), func_get_args());
		$selfClass = __CLASS__;
		self::$objStorage[$class] = new $selfClass($obj);
		return self::$objStorage[$class];
	}
	
	public static function getInstance(){
		$class = get_called_class();
		if( isset(self::$objStorage[$class]) ){
			return self::$objStorage[$class];
		}
		return call_user_func_array('self::newInstance', func_get_args());
	}
}
