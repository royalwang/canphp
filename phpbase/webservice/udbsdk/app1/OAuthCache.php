<?php
abstract class OAuthCache{
	// 成功返回相应的值, 值不存在返回"", 失败返回false
	abstract public function getValue($key);
	
	// 成功返回true, 失败返回false
	abstract public function setValue($key, $value);
}

class OAuthCache_File extends OAuthCache{
	function __construct($file){
		$this->file = $file;
	}
	
	public function getValue($key){
		if(empty($this->file) || !file_exists($this->file)){
			return false;
		}
		
		$v = parse_ini_file ($this->file);
		if(!$v){
			return false;
		}
		if(!isset($v[$key])){
			return "";
		}
		return $v[$key];
	}
	
	public function setValue($key, $value){
		if(empty($this->file)){
			return false;
		}
		
		if(file_exists($this->file)){
			$arr = parse_ini_file ($this->file);
		}
		
		if(empty($arr)){
			$arr = array();
		}
		$arr[$key] = $value;
		$str = '';
		foreach ($arr as $k => $v){
			$str .= $k . " = ". $v ."\n";
		}
		$written_bytes = file_put_contents($this->file, $str, LOCK_EX);
		if($written_bytes === FALSE){
			return false;
		}
		return true;
	}
}

//test
function test_OAuthCache_File(){
	$cache = new OAuthCache_File("c:/php_cache.dat");
	$cache->setValue("name", "php_cache.dat");
	$cache->setValue("gender", "female");
	
	$name = $cache->getValue("name");
	if($name === false){
		echo "get name value failed <br />";
	} else {
		echo "name = ". $name."<br />";
	}
	
	$gender = $cache->getValue("gender");
	if($gender === false){
		echo "get gender value failed <br />";
	} else {
		echo "gender = ". $gender."<br />";
	}
	
	$age = $cache->getValue("age");
	if($age === false){
		echo "get age value failed <br />";
	} else {
		echo "age = ". $age."<br />";
	}
	
	$cache->setValue("age", "25");
	$age = $cache->getValue("age");
	if($age === false){
		echo "get age value failed <br />";
	} else {
		echo "age = ". $age."<br />";
	}
}

// test_OAuthCache_File();
?>