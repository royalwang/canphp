<?php
namespace canphp\core\cache;
class cpCacheInterface {

	//读取缓存
    public function get($key);
	
	//设置缓存
    public function set($key, $value, $expire = 1800);
	
	//自增1
	public function inc($key, $value = 1);
	
	//自减1
	public function des($key, $value = 1);
	
	//删除
	public function del($key);
	
	//全部清空
    public function clear();
	
}