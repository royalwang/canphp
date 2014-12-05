<?php
namespace framework\base;
//缓存类
class Cache{
	protected $config =array();
	protected $cache = 'default';
	protected static $objArr = array();
	
    public function __construct( $cache = 'default' ) {
		if( $cache ){
			$this->cache = $cache;
		}
		$this->config = Config::get('CACHE.' . $this->cache);
		if( empty($this->config) || !isset($this->config['CACHE_TYPE']) ) {
			throw new Exception($this->cache.' cache config error', 500);
		}
    }

	public function __call($method, $args){
		if( !isset(self::$objArr[$this->cache]) ){		
			$cacheDriver = __NAMESPACE__.'\cache\\' . ucfirst( $this->config['CACHE_TYPE'] ).'Driver';
			if( !class_exists($cacheDriver) ) {
				throw new \Exception("Cache Driver '{$cacheDriver}' not found'", 500);
			}	
			self::$objArr[$this->cache] = new $cacheDriver( $this->config );
		}
		
		return call_user_func_array(array(self::$objArr[$this->cache], $method), $args);		
	}
}