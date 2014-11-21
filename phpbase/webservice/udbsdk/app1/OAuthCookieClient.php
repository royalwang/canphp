<?php
require_once ('OAuthUdbKey.php');
require_once ('OAuthClient.php');
require_once ('OAuthUdbKeyMgr.php');

class OAuthCookieClient{
	function __construct($appid, $appkey, $cookie, $user_agent, $domain, $cache) {
		$this->validated = NULL;
		$this->access_token = NULL;
		
		$this->app_id = $appid;
		$this->app_key = $appkey;
		$this->user_agent = $user_agent;
		$this->domain = $domain;
		
		$this->udb_key_mgr = new OAuthUdbKeyMgr($appid, $appkey, $cache);
		$this->oauth_client = new OAuthClient($appid, $appkey);
		
		$this->username = NULL;
		$this->yyuid    = NULL;
		$this->passwd = NULL;
		$this->osinfo = NULL;
		$this->udb_login_flag = NULL;
		$this->oauth_cookie = NULL;
		$this->oauth_cookie_private = NULL;
		$this->udb_key = NULL;
		$this->oauth_udb_key = NULL;
		
		$cookie = array_change_key_case($cookie, CASE_LOWER );
		if(isset($cookie['username'])){
			$this->username = $cookie['username'];
		}
		if(isset($cookie['password'])){
			$this->passwd = $cookie['password'];
		}
		if(isset($cookie['osinfo'])){
			$this->osinfo = $cookie['osinfo'];
		}
		if(isset($cookie['udbloginflag'])){
			$this->udb_login_flag = $cookie['udbloginflag'];
		}
		if(isset($cookie['oauthcookie'])){
			$this->oauth_cookie = $cookie['oauthcookie'];
		}
		if(isset($cookie['oauthcookieprivate'])){
			$this->oauth_cookie_private = $cookie['oauthcookieprivate'];
		}
	}
	
	function validate(){
		// 已校验过，直接返回
		if (isset($this->validated)){
			return $this->validated;
		}
		
		$this->validated = false;
		
		if(!$this->__checkParameters()){
			return false;
		}
		
		// traditional udb key		
		$this->udb_key = $this->udb_key_mgr->getUdbKey();
		if(empty($this->udb_key) || !$this->udb_key->isLegal()){
			return false;
		}
		
		// oauth udb key
		$this->oauth_udb_key = $this->udb_key_mgr->getSecureKey();
		if(empty($this->oauth_udb_key) || !$this->oauth_udb_key->isLegal()){
			return false;
		}
		
		// verify traditional cookie
		if(!$this->__udbValidate()){
			return false;
		}
		
		// verify oauth cookie
		if(!$this->__oauthUdbValidate()){
			return false;
		}
		
		$this->validated = true;
		return true;
	}
	
	function getUserName(){
		if(!empty($this->username)){
			return $this->username;
		}
		return false;
	}
	
	function getYYUID(){
		if(!empty($this->yyuid)){
			return $this->yyuid;
		}
		return false;
	}
	
	function getAccessToken(){
		if(!empty($this->access_token)){
			return $this->access_token;
		}
		return false;
	}
	
	private function __checkParameters(){
		if(empty($this->username) || empty($this->passwd) || empty($this->osinfo)
		|| empty($this->oauth_cookie)){
			return false;
		}
				
		return true;
	}
	
	private function __oauthUdbValidate(){
		if($this->__oauthUdbValidate_i($this->oauth_udb_key->hashkey)){
			return true;
		}
		if($this->__oauthUdbValidate_i($this->oauth_udb_key->hashkey2)){
			return true;
		}
		return false;
	}
	
	private function __oauthUdbValidate_i($key){
		$oauth_cookie = AESHelper::decrypt($this->oauth_cookie, $key);
		$oauth_cookie_arr = explode(":", $oauth_cookie);
		if(count($oauth_cookie_arr)<4 || count($oauth_cookie_arr)>5){
			return false;
		}
		$username = urldecode($oauth_cookie_arr[0]);
		$appid = $oauth_cookie_arr[1];
		$access_key = $oauth_cookie_arr[2];
		$access_secret = $oauth_cookie_arr[3];
		if(count($oauth_cookie_arr)>4 && !empty($oauth_cookie_arr[4])){
			$this->yyuid = $oauth_cookie_arr[4];
		}
		
		if(strcasecmp($username, $this->username) != 0){
			return false;
		}
		
		// 如果是本域的cookie,直接OK
		if(!(strcasecmp($appid, $this->app_id))){
			$this->access_token = new OAuthConsumer($access_key, $access_secret);
			return true;
		}
		
		// 检查私有域
		if(!empty($this->oauth_cookie_private)){
			$oauth_cookie_private = AESHelper::decrypt($this->oauth_cookie_private, $key);
			$oauth_cookie_private_arr = explode(":", $oauth_cookie_private);
			if(count($oauth_cookie_private_arr) == 4){
				$username_private      = urldecode($oauth_cookie_private_arr[0]);
				$appid_private         = $oauth_cookie_private_arr[1];
				$access_key_private    = $oauth_cookie_private_arr[2];
				$access_secret_private = $oauth_cookie_private_arr[3];
				if(!(strcasecmp($username_private, $this->username)) && 
				!(strcasecmp($appid_private, $this->app_id))){
					$this->access_token = new OAuthConsumer($access_key_private, $access_secret_private);
					return true;
				}
			}
		}
		
		// 还不行的话，交换cookie
		$token_arr = $this->oauth_client->changeAccesstoken($appid, $access_key, $username);
		if(count($token_arr) < 2){
			return false;
		}
		$this->access_token = new OAuthConsumer($token_arr[0], $token_arr[1]);
		$cookie_private_value = AESHelper::encrypt($this->__getOAuthCookie(), $key);
		// $this->append_cookie = array("oauthCookiePrivate" => $cookie_private_value);
		// set cookie
		setcookie("oauthCookiePrivate",$cookie_private_value, 0, "/", $this->domain);
		
		return true;
	}
	
	private function __udbValidate(){
		$userAgentArr = array();
		$rawUserAgent = $this->user_agent;
		$userAgentArr[] = $rawUserAgent;
		if(!(stripos("MSIE 7.0", $rawUserAgent) === FALSE)){
			$userAgentArr[] = str_ireplace("MSIE 7.0", "MSIE 8.0", $rawUserAgent);
		}
		if(!(stripos("MSIE 8.0", $rawUserAgent) === FALSE)){
			$userAgentArr[] = str_ireplace("MSIE 8.0", "MSIE 7.0", $rawUserAgent);
		}
		
		foreach ($userAgentArr as $userAgent){
			if ($this->__udbValidate_i($this->udb_key->hashkey, $userAgent)){
				return true;
			}
			
			if ($this->__udbValidate_i($this->udb_key->hashkey2, $userAgent)){
				return true;
			}
			
			// IE兼容浏览器也能拿到IE产生的cookie
			$newUserAgent = $this->__getNewUserAgent($userAgent);
			if ($this->__udbValidate_i($this->udb_key->hashkey, $newUserAgent)){
				return true;
			}
			
			if ($this->__udbValidate_i($this->udb_key->hashkey2, $newUserAgent)){
				return true;
			}
		}
		return false;
	}
	private function __udbValidate_i($key, $userAgent){
		$username = urlencode($this->username);
		$passwd = sha1($username.$key);
		return strcasecmp($passwd, $this->passwd) == 0;
		if(strcasecmp($passwd, $this->passwd) == 0){
			$osinfo = sha1($userAgent.$username.$key);
			if(strcasecmp($osinfo, $this->osinfo) == 0){
				return true;
			}
		}
		return false;
	}
	
	private function __getNewUserAgent($userAgent){
		$userAgent = str_ireplace(array("; TheWorld","; 360SE"), "", $userAgent);
		return $userAgent;
	}
	
	private function __getOAuthCookie(){
		return $this->username.":".
			$this->app_id.":".
			$this->access_token->key.":".
			$this->access_token->secret;
	}
}
?>
