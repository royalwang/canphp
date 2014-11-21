<?php
require_once ('AESHelper.php');

class OAuthUdbKeyMgr {
	
	private static $udbKeyPasswd = "51b7cded89665b21899b3232ce1ff460575db680";
	private static $udbKeyName = "UDBKEY";
	
	function __construct($appid, $appkey, $cache){
		$this->cache = $cache;
		$this->app_id = $appid;
		$this->app_key = $appkey;
		
		$this->udb_key_cache = null;
		$this->need_save = false;
	}
	
	function getUdbKey(){
		if (empty($this->udb_key_cache) && !$this->__initUdbKeyCache()){
			return false;
		}
		return $this->udb_key_cache->udb_key;
	}
	
	function getSecureKey(){
		if (empty($this->udb_key_cache) && !$this->__initUdbKeyCache()){
			return false;
		}
		return $this->udb_key_cache->secure_key;
	}
	
	private function __initUdbKeyCache(){
		if(!empty($this->udb_key_cache)){
			return true;
		}
		
		$this->udb_key_cache = new OAuthUdbKeyCache();
		$key = $this->__getKeyFromFile();
		
		$to_update_secure_key = true;
		if($key){
			if(!empty($key->secure_key) && $key->secure_key->isLegal()){
				$this->udb_key_cache->secure_key = $key->secure_key;
				$to_update_secure_key = false;
			}
		}
		
		if($to_update_secure_key){
			$this->udb_key_cache->secure_key = $this->__getSecureKey();
		}
		
		if (!empty($this->udb_key_cache->secure_key) && $this->udb_key_cache->secure_key->isLegal()) {
			$this->__saveKeyToFile();
			return true;
		}
		
		return false;
	}
	
	private function __saveKeyToFile(){
		if(empty($this->cache) || !$this->need_save || empty($this->udb_key_cache)){
			return false;
		}
		
		$s = serialize($this->udb_key_cache);
		$cs = AESHelper::encrypt($s, self::$udbKeyPasswd);
		
		return $this->cache->setValue(self::$udbKeyName, $cs);
	}
	
	private function __getKeyFromFile(){
		if(empty($this->cache)){
			return false;
		}
		
		$cs = $this->cache->getValue(self::$udbKeyName);
		if(empty($cs)){
			return false;
		}
		$s = AESHelper::decrypt($cs, self::$udbKeyPasswd);		
		return unserialize($s);
	}

	private function __getSecureKey(){
		$uclient = new OAuthClient($this->app_id, $this->app_key);
		$oauthKeyArr = $uclient->getSecureKey();
		if(!$oauthKeyArr){
			return false;	
		}
		$this->need_save = true;
		return new OAuthUdbKey($oauthKeyArr);
	}
}
?>