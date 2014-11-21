<?php
class OAuthUdbKey{
	var $hashkey;
	var $hashkey2;
	var $passtime;
	var $starttime;
	
	function __construct($keyArr) {
		if (count($keyArr) < 3){
			return;
		}
		$this->hashkey = $keyArr[0];
		$this->hashkey2 = $keyArr[1];
		$this->passtime = $keyArr[2];
		if(count($keyArr) > 3){
			$this->starttime = $keyArr[3];
		}
	}
	
	function isLegal(){
		$timestamp = time()*1000;
		if(empty($this->hashkey) || empty($this->hashkey2) || empty($this->passtime) || $this->passtime <= $timestamp){
			return false;
		}
		return true;
	}
}

class OAuthUdbKeyCache{
	var $udb_key;
	var $secure_key;
}
?>
