<?php
//require_once("common.inc.php");
require_once("OAuth.php");
require_once("AESHelper.php");

class OAuthClient{
	
	private static $requestTokenURL = "http://udb.duowan.com/initiate.do";
	private static $authorizeURL = "http://udb.duowan.com/authorize.do";
	private static $accessTokenURL = "http://udb.duowan.com/token.do";
	private static $validAccessTokenURL = "http://udb.duowan.com/tokenValid.do";
	private static $writeCookieURL = "http://udb.duowan.com/writecookie4oauth.do";
	private static $deleteCookieURL = "http://udb.duowan.com/deletecookie4oauth.do";
	
	private static $getSecureKeyURL = "http://udb.duowan.com/message/oauth/getSecurekey.do";
	private static $changeAccesstokenURL = "http://udb.duowan.com/message/oauth/changeAccesstoken.do";
	
	function __construct($appid, $appkey) {

		$this->app_id = $appid;
		$this->app_key = $appkey;
		$this->sig_method = new OAuthSignatureMethod_HMAC_SHA1();
		$this->app_consumer = new OAuthConsumer($this->app_id, $this->app_key, NULL);		
	}

	/**
	 * getRequestToken
	 *
	 * oauth_token
     * oauth_token_secret
	 * 
	 */
	function getRequestToken($callbackUrl){
		$req_req = OAuthRequest::from_consumer_and_token($this->app_consumer, NULL, "POST", self::$requestTokenURL);
		$req_req->set_parameter("oauth_callback", $callbackUrl);
		$req_req->sign_request($this->sig_method, $this->app_consumer, NULL);
		
		// 请求request_token
		$headers = array($req_req->to_header());
		$response = $this->__requestServer($headers, self::$requestTokenURL);
		if (!$response){
			return False;
		}
		parse_str($response,$rt);
		
		$oauth_token = $rt['oauth_token'];
		$oauth_token_secret = $rt['oauth_token_secret'];
		if(!empty($oauth_token) && !empty($oauth_token_secret) ){
			return new OAuthConsumer($oauth_token, $oauth_token_secret);
		}else{
			return False;
		}
	}
	
	function getAuthorizeURL($request_token, $denyCallbackUrl){
		$endpoint = self::$authorizeURL;
		$token = $request_token->key;
		$auth_url = $endpoint ."?oauth_token=$token&denyCallbackURL=".urlencode($denyCallbackUrl);
		return $auth_url;
	}

	/**
	 * getAccessToken
	 *
	 * oauth_token
     * oauth_token_secret
	 * 
	 */
	function getAccessToken($request_token, $oauth_verifier, &$oauth_mckey4cookie = NULL){//
		$acc_req = OAuthRequest::from_consumer_and_token($this->app_consumer, $request_token, "POST", self::$accessTokenURL);
		$acc_req->set_parameter("oauth_verifier", $oauth_verifier);
		$acc_req->sign_request($this->sig_method, $this->app_consumer, $request_token);
		
		// 请求access_token
		$headers = array($acc_req->to_header());
		$response = $this->__requestServer($headers, self::$accessTokenURL);
		if (!$response){
			return False;
		}
		parse_str($response,$rt);
		
		$access_token = $rt['oauth_token'];
		$access_token_secret = $rt['oauth_token_secret'];
		
		if (isset($oauth_mckey4cookie)){
			$oauth_mckey4cookie = $rt['oauth_mckey4cookie'];
		}
		if(!empty($access_token) && !empty($access_token_secret)){
			return new OAuthConsumer($access_token, $access_token_secret);
		} else {
			return False;
		}
	}
	
	function validAccessToken($access_token, $username){
		if(empty($username)){
			return False;
		}
		$parameters = array();
		$parameters["username"] = $username;
		
		$acc_req = OAuthRequest::from_consumer_and_token($this->app_consumer, $access_token, "POST", self::$validAccessTokenURL,$parameters);
		$acc_req->set_parameter("oauth_verifier", "0");
		$acc_req->sign_request($this->sig_method, $this->app_consumer, $access_token);
		
		// 请求access_token
		$headers = array($acc_req->to_header());
		$response = $this->__requestServer($headers, self::$validAccessTokenURL, $parameters);
		if (!$response){
			return False;
		}
		parse_str($response,$rt);
		
		if(isset($rt["validtoken"]) && $rt["validtoken"] == 1){
			return True;
		}
		return False;
	}
	
	function getWriteCookieURL($access_token, $username, $oauth_mckey4cookie){
		if(empty($access_token) or empty($username) or empty($oauth_mckey4cookie)){
			return false;
		}
		$sig_key = $this->app_key.'_'.$access_token->secret;
		$sig_content = $this->app_id.'_'.$access_token->key.'_'.$oauth_mckey4cookie.'_'.urlencode($username);
		
		$signature = base64_encode(hash_hmac('sha1', $sig_content, $sig_key, true));
		$cookieURL = self::$writeCookieURL.'?oauth_mckey4cookie='.$oauth_mckey4cookie.'&oauth_signature='.urlencode($signature);
		
		return $cookieURL;
	}
	
	function getDeleteCookieURL(){
		$timestamp = time()*1000;
		$sig_content = $this->app_id."_".$timestamp;
		$signature = base64_encode(hash_hmac('sha1', $sig_content, $this->app_key, true));
		
		$deleteCookieURL = self::$deleteCookieURL."?appid=".$this->app_id.'&oauth_mckey4cookie='.$timestamp.'&oauth_signature='.urlencode($signature);
		return $deleteCookieURL;
	}
	
	function callApi($access_token, $url, $username, $parameters){//
		if(empty($username)){
			return False;
		}
		
		$parameters = ($parameters) ?  $parameters : array();
		$parameters["username"] = $username;
		$api_req = OAuthRequest::from_consumer_and_token($this->app_consumer, $access_token, "POST", $url, $parameters);
		$api_req->set_parameter("oauth_verifier", "0");
		$api_req->sign_request($this->sig_method, $this->app_consumer, $access_token);
		
		// 请求api
		
		$headers = array($api_req->to_header());
		$response = $this->__requestServer($headers, $url, $parameters);
		if (!$response){
			return False;
		}
		parse_str($response,$rt);
		return $rt;
	}
	
	function getSecureKey(){
		$parameters = array(
			"appid" => $this->app_id
		);
		
		$api_req = OAuthRequest::from_consumer_and_token($this->app_consumer, NULL, "POST", self::$getSecureKeyURL,$parameters);
		//$api_req->set_parameter("oauth_verifier", "0");
		$api_req->sign_request($this->sig_method, $this->app_consumer, NULL);
		
		// 请求api
		$headers = array($api_req->to_header());
		$response = $this->__requestServer($headers, self::$getSecureKeyURL, $parameters);
		if (!$response){
			return False;
		}
		parse_str($response,$rt_ciper);
		
		if (empty($rt_ciper["securekeyinfo"])){
			return False;
		}
		
		$plaintext = AESHelper::decrypt($rt_ciper["securekeyinfo"], $this->app_key);
		$rt = explode(";", $plaintext);
		if(count($rt) < 3){
			return false;
		}
		return $rt;
	}
	
	function changeAccesstoken($appid_in_cookie, $access_key_in_cookie, $username_in_cookie){
		$parameters = array(
			"appid"                 => $this->app_id,
			"appidInCookie"         => $appid_in_cookie,
			"accesstokenInCookie"   => $access_key_in_cookie,
			"usernameInCookie"      => $username_in_cookie,
		);
		
		$api_req = OAuthRequest::from_consumer_and_token($this->app_consumer, NULL, "POST", self::$changeAccesstokenURL,$parameters);
		$api_req->sign_request($this->sig_method, $this->app_consumer, NULL);
		
		// 请求api
		$headers = array($api_req->to_header());
		$response = $this->__requestServer($headers, self::$changeAccesstokenURL, $parameters);
		if (!$response){
			return False;
		}
		parse_str($response,$raw_rt);
		if (empty($raw_rt["tokeninfo"])){
			return False;
		}
		$rt = explode(":", $raw_rt["tokeninfo"]);
		return $rt;
	}
	
	private function __requestServer($headers, $url, $parameters = array()){
		// echo "headers:".$headers[0]."<br />";
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, TRUE);
		curl_setopt($ch, CURLOPT_TIMEOUT, 20);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($parameters));
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_URL, $url);
		$response = curl_exec($ch);
		
		$http_code        = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$http_header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
		curl_close($ch);
		
		if($http_code != 200){
			$http_header = substr($response, 0,strpos($response,"\n"));
			list($protocal, $http_code, $http_code_message) = explode(' ', $http_header, 3);
			throw new OAuthException($http_code_message, $http_code);
		}
		$response = substr($response, $http_header_size);
		return $response;
	}
}

?>
