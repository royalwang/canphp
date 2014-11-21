<?php
/**
 * 缓存
 *
 *
 * 使用方法：
include('dwCache.php');
$mc = new dwCache('dwtu'); // 要有域
$mc->set('word', 'hello world', 900);
echo $mc->get('word');
$mc->delete('word');
echo $mc->get('word');

$mc->set('counter', 1, 290000);
echo $mc->get('counter');

$mc->incr('counter');
$mc->incr('counter');
echo $mc->get('counter');

$mc->decr('counter');
echo $mc->get('counter');

$mc->flush(); // 按域删

$obj->cache(100)->xxx();

$model->get();

dwCache->find();

 */
class dwCache
{
	public $linked_object = null;
	public $cached_time = 3600;
	private $mmc = null;
	private $domain = null;
	private $version;

	function __construct($domain){
		if( !class_exists('Memcached') ){
		    $this->mmc = new Memcache();
		}else{
		    $this->mmc = new Memcached();
			$this->mmc->setOptions(array(
							Memcached::OPT_CONNECT_TIMEOUT=>200,
							Memcached::OPT_RETRY_TIMEOUT=>200,
							Memcached::OPT_POLL_TIMEOUT=>200,
					));
		}
		if(defined('DWAE_MMC_HOST_1'))$this->mmc->addServer(DWAE_MMC_HOST_1, DWAE_MMC_PORT_1);
		if(defined('DWAE_MMC_HOST_2'))$this->mmc->addServer(DWAE_MMC_HOST_2, DWAE_MMC_PORT_2);
		$this->domain = $domain;
		if(!$this->version = $this->mmc->get('version_'.$domain)){
			$this->mmc->set('version_'.$domain, 1);
			$this->version = 1;
		}
	}

	function __call($name, $args){
		$cache_id = get_class($this->linked_object) . '@' . $name. '#' . print_r($args, 1);
		$result = $this->get($cache_id);
		if(DEBUG || !$result){
			$result = call_user_func_array(array($this->linked_object, $name), $args);
			$this->set($cache_id, $result, $this->cached_time);
		}
		return $result;
	}

	function set($key, $var, $expire=3600){
		if(!$this->mmc)return;
		if( !class_exists('Memcached') ){
		    return $this->mmc->set($this->domain.'_'.$this->version.'_'.$key, $var, 0, $expire);
		}else{
		    return $this->mmc->set($this->domain.'_'.$this->version.'_'.$key, $var, $expire);
		}
	}

	function get($key){
		if(!$this->mmc)return;
		return $this->mmc->get($this->domain.'_'.$this->version.'_'.$key);
	}
	
	function add($key, $var, $expire=3600){		
		if(!$this->mmc)return;		
		if( !class_exists('Memcached') ){
			return $this->mmc->add($this->domain.'_'.$this->version.'_'.$key, $var, false, $expire);		    
		}else{			
		    return $this->mmc->add($this->domain.'_'.$this->version.'_'.$key, $var, $expire);
		}
	}
	
	function incr($key, $value=1){
		if(!$this->mmc)return;
		return $this->mmc->increment($this->domain.'_'.$this->version.'_'.$key, $value);
	}

	function decr($key, $value=1){
		if(!$this->mmc)return;
		return $this->mmc->decrement($this->domain.'_'.$this->version.'_'.$key, $value);
	}

	function delete($key){
		if(!$this->mmc)return;
		return $this->mmc->delete($this->domain.'_'.$this->version.'_'.$key);
	}

	function flush(){
		if(!$this->mmc)return;
		++$this->version;
		$this->mmc->set('version_'.$this->domain, $this->version);
	}
}
