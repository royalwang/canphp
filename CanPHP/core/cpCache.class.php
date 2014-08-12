<?php
namespace canphp\core;
use canphp\core\cpConfig;

//缓存类
class cpCache{
	public $link_obj =NULL;
	public $cache_time =1800;
	
	protected  static $cache = array();
	protected  $config = array();
	protected  $type = 'FileCache';
	protected  $forceInstance = false;
	
    public function __construct( $cacheConfig = array(), $type = 'FileCache', $forceInstance=false) {
		$this->config = array_merge(cpConfig::$CACHE, (array)$cacheConfig); //参数配置	
		$this->forceInstance = $forceInstance;
		$this->type = $type;
    }

	public function __call($method, $args){
		if( $this->forceInstance || !isset(self::$cache[$type] ){
			$cacheDriver = 'cp' . ucfirst( $this->type );
			self::$cache[$type] = new $cacheDriver($this->config );
		}
		$cache_obj = self::$cache[$type];
		
		if( null==$this->link_obj ){
			return call_user_func_array(array($cache_obj, $method), $args);
		}
		
		if( 'cpModel'==get_class($this->link_obj) || is_subclass_of($this->link_obj, 'cpModel') ){
			//特殊方法，不缓存
			if( in_array($method, array('table','field','data','where','order','limit')) ){
				return call_user_func_array(array($this->link_obj, $method), $args);
			}
		}
		$cache_key = md5(get_class($this->link_obj).'_'.$method.'_'.var_export($args, true));
		$content = $cache_obj->get($cache_key);
		if( empty($content) ){
			$content = call_user_func_array(array($this->link_obj, $method), $args);
			$cache_obj->set($cache_key, $content, $this->cache_time);
		}
		
		return $content;		
	}
	//读取缓存
    public function get($key) {
		return $this->cache->get($key);   
    }
	
	//设置缓存
    public function set($key, $value, $expire = 1800) {
		return $this->cache->set($key, $value, $expire);
    }
	
	//自增1
	public function inc($key, $value = 1) {
		return $this->cache->inc($key, $value);    
	}
	
	//自减1
	public function des($key, $value = 1) {
		return $this->cache->des($key, $value);    
	}
	
	//删除
	public function del($key) {
		return $this->cache->del($key);
	}
	
	//清空缓存
    public function clear() {
		return $this->cache->clear();    
	}
}