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
		$this->oauth_cookie = NULL;
		$this->oauth_cookie_private = NULL;
		$this->oauth_udb_key = NULL;
		$this->acctinfo = NULL;
		$this->accttoken = NULL;
		$this->errorinfo = NULL;
		
		$cookie = array_change_key_case($cookie, CASE_LOWER );
		
		if(isset($cookie['oauthcookie'])){
			$this->oauth_cookie = $cookie['oauthcookie'];
		}
		if(isset($cookie['oauthcookieprivate'])){
			$this->oauth_cookie_private = $cookie['oauthcookieprivate'];
		}
		
		if(isset($cookie['udb_l'])){
			$this->acctinfo = $cookie['udb_l'];
		}
		if(isset($cookie['udb_n'])){
			$this->accttoken = $cookie['udb_n'];
		}
		if(isset($cookie['yyuid'])){
			$this->yyuid = $cookie['yyuid'];
		}
		if(isset($cookie['username'])){
			$this->username = $cookie['username'];
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
		
		// oauth udb key
		$this->oauth_udb_key = $this->udb_key_mgr->getSecureKey();
		if(empty($this->oauth_udb_key) || !$this->oauth_udb_key->isLegal()){
			return false;
		}
		
		// verify oauth cookie
		if(!$this->__oauthUdbValidate()){
			return false;
		}
		
		$this->validated = true;
		// trigger to reportVersion
		$this->oauth_client->reportVersion();
		
		return true;
	}
	
	function getUserName(){
		if($this->validated && !empty($this->username) && strcasecmp($this->username, "*") != 0){
			return $this->username;
		}
		return false;
	}
	
	function getYYUID(){
		if($this->validated && !empty($this->yyuid)){
			return $this->yyuid;
		}
		return false;
	}
	function getAcctinfo(){
		if(!empty($this->acctinfo)){
			return $this->acctinfo;
		}
		return false;
	}
	function getAccttoken(){
		if(!empty($this->accttoken)){
			return $this->accttoken;
		}
		return false;
	}	
	function getAccessToken(){
		if(!empty($this->access_token)){
			return $this->access_token;
		}
		return false;
	}
	function getErrorinfo(){
		if(!empty($this->errorinfo)){
			return "appid=".$this->app_id.";yyuid=".$this->yyuid.";username=".$this->username.";oauthCookie=".
					$this->oauth_cookie.";errorinfo=".$this->errorinfo;
		}
		return false;
	}	
	private function __checkParameters(){
		if((empty($this->yyuid) && empty($this->username))){
			$this->errorinfo = "both yyuid and username are empty";
			return false;
		}	
		if(empty($this->oauth_cookie)){
			$this->errorinfo = "oauthCookie is empty";
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
		// step1:解密公共域 oauth_cookie	
		$oauth_cookie = AESHelper::decrypt($this->oauth_cookie, $key);
		$oauth_cookie_arr = explode(":", $oauth_cookie);//格式：username:appid:access_key:access_secret:yyuid:*
		if(count($oauth_cookie_arr)<4 ){
			return false;
		}
		$username = urldecode($oauth_cookie_arr[0]);
		$appid = $oauth_cookie_arr[1];
		$access_key = $oauth_cookie_arr[2];
		$access_secret = $oauth_cookie_arr[3];
		$yyuid = null;
		if(count($oauth_cookie_arr)>4 && !empty($oauth_cookie_arr[4])){
			$yyuid = $this->__mytrim($oauth_cookie_arr[4]); 
		}
		//step2:比较cookie的yyuid 与 解密出的yyuid是否一致,为了兼容新老sdk登录态，优先比较yyuid,如果不一致再比较username
		if(!empty($this->yyuid)){//优先比较yyuid
			if(strcasecmp($yyuid, $this->yyuid) != 0){
				$this->errorinfo = "yyuid not equal which in oauthCookie";
				return false;
			}else{
				$this->username = $username;
			}
		}else{//若无yyuid则比较username
			if(strcasecmp($username, $this->username) != 0){
				$this->errorinfo = "username not equal which in oauthCookie";
				return false;				
			}else{
				$this->yyuid = $yyuid;
			}
		}
		
		
		//step3: 判断公共域cookie是否有效，不区分appid
		$this->access_token = new OAuthConsumer($access_key, $access_secret);
		//step3.1:判断是否强制验证accesstoken时效性
		if($this->oauth_client->isforcechkAccesstoken()){//
			if($this->oauth_client->validAccessToken($this->access_token, $this->yyuid,$username)){
				return true;
			}else{
				$this->errorinfo = $this->oauth_client->errorinfo;
				return false;
			}
		}else{
			return true;
		}

	}
	
	/**
	 * 过滤yyuid中乱码信息，仅返回数字字符
	 */
	private function __mytrim($yyuid){
		$retYyuid = "";
		for($i=0;$i<strlen($yyuid);$i++){  
			$tmp = substr($yyuid,$i,1);
			if(is_numeric($tmp)){
				$retYyuid = $retYyuid.$tmp;
			}
		} 
		return $retYyuid;
	}
	
	private function __getOAuthCookie(){
		return $this->username.":".
			$this->app_id.":".
			$this->access_token->key.":".
			$this->access_token->secret.":".
			$this->yyuid.":*";
	}
}
?>
