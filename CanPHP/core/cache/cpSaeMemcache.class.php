<?php
class cpSaeMemcache {
	private $mmc = NULL;
    private $group = ''; 
    private $ver = 0;
    public function __construct( $memConfig = array() ) {
		$this->mmc = memcache_init();
		$this->group = $memConfig['SAE_MEM_GROUP'];
		$this->ver = intval( memcache_get($this->mmc, $this->group.'_ver') ); 
    }

	//读取缓存
    public function get($key) {
		$expire = memcache_get($this->mmc, $this->group.'_'.$this->ver.'_time_'.$key);
		if(intval($expire) > time() ) {
			 return memcache_get($this->mmc, $this->group.'_'.$this->ver.'_'.$key);
		} else {
			return false;
		}
    }
	
	//设置缓存
    public function set($key, $value, $expire = 1800) {
		$expire = ($expire == -1)? time()+365*24*3600 : time() + $expire;
		memcache_set($this->mmc, $this->group.'_'.$this->ver.'_time_'.$key, $expire);//写入缓存时间
		return memcache_set($this->mmc, $this->group.'_'.$this->ver.'_'.$key, $value);
    }
	
	//自增1
	public function inc($key, $value = 1) {
		  return $this->set($key, intval($this->get($key)) + intval($value), -1);
    }
	
	//自减1
	public function des($key, $value = 1) {
		 return $this->set($key, intval($this->get($key)) - intval($value), -1);
    }
	
	//删除
	public function del($key) {
		return $this->set($key, '', 0);
	}
	
	//全部清空
    public function clear() {
        return  memcache_set($this->mmc, $this->group.'_ver', $this->ver+1); 
    }
	
}