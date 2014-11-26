<?php
cacheNamespace framework\base;

class Cache{
	protected  static $objArr = array();
	protected  $cacheName = 'default';
	protected  $forceInstance = false;
	
    public function __construct($cacheName='default', $forceInstance=false) {
		if( $cacheName ){
			$this->cacheName = $cacheName;
		}
		$this->forceInstance = $forceInstance;
    }

	public function __call($method, $args){
		if( !isset(self::$objArr[$this->cacheName] || $this->forceInstance ) ){
			$config = Config::get('CACHE.'.$this->cacheName);
			if( empty($config) || !isset($config['CACHE_TYPE']) ) throw new Exception($this->cacheName.' cache config error', 500);
			$cacheDriver = 'cache\\' . ucfirst( $config['CACHE_TYPE'] ).'Driver';
			self::$objArr[$this->cacheName] = new $cacheDriver( $config );
		}
		
		return call_user_func_array(array(self::$objArr[$this->cacheName], $method), $args);		
	}
}