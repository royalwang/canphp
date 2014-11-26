<?php
namespace framework\base;

class Cache{
	protected $config =array();
	protected $tag = 'default';
	protected static $objArr = array();
	
    public function __construct( $tag = 'default' ) {
		if( $tag ){
			$this->tag = $tag;
		}
		$this->config = Config::get('CACHE.' . $this->tag);
		if( empty($this->config) || !isset($this->config['CACHE_TYPE']) ) {
			throw new Exception($this->tag.' cache config error', 500);
		}
    }

	public function __call($method, $args){
		if( !isset(self::$objArr[$this->tag]) ){		
			$cacheDriver = 'cache\\' . ucfirst( $this->config['CACHE_TYPE'] ).'Driver';
			self::$objArr[$this->tag] = new $cacheDriver( $this->config );
		}
		
		return call_user_func_array(array(self::$objArr[$this->tag], $method), $args);		
	}
}