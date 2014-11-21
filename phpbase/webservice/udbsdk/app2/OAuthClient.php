<?php
//require_once("common.inc.php");
require_once("OAuth.php");
require_once("AESHelper.php");
require_once("webSdkConfig.php");

define("SDK_TYPE",    "phpClient");
define("SDK_VERSION", "1.5.7");
/**
 * oauth服务客户端，聚合所有http接口调用
 */
class OAuthClient{
	
//	private static $requestTokenURL = "http://udb.duowan.com/initiate.do";
//	private static $authorizeURL = "http://udb.duowan.com/authorize.do";
//	private static $accessTokenURL = "http://udb.duowan.com/token.do";
//	private static $validAccessTokenURL = "http://udb.duowan.com/tokenValid.do";
//	private static $writeCookieURL = "http://udb.duowan.com/writecookie4oauth.do";
//	private static $deleteCookieURL = "http://udb.duowan.com/deletecookie4oauth.do";
//	private static $getSecureKeyURL = "http://udb.duowan.com/message/oauth/getSecurekey.do";
//	private static $changeAccesstokenURL = "http://udb.duowan.com/message/oauth/changeAccesstoken.do";

	private static $requestTokenURL = "http://lgn.yy.com/lgn/oauth/initiate.do";
	private static $authorizeURL    = "https://lgn.yy.com/lgn/oauth/authorize.do";
	private static $accessTokenURL  = "http://lgn.yy.com/lgn/oauth/token.do";
	private static $validAccessTokenURL = "http://lgn.yy.com/lgn/oauth/tokenValid.do";
	private static $writeCookieURL  =  "https://lgn.yy.com/lgn/oauth/wck_n.do";
	private static $writeCookieURI  =  "/lgn/oauth/wck_n.do";
	private static $deleteCookieURL = "http://lgn.yy.com/lgn/oauth/dck.do";
	
	private static $getSecureKeyURL = "http://lgn.yy.com/message/oauth/getSecurekey.do";
	private static $changeAccesstokenURL = "http://lgn.yy.com/message/oauth/changeAccesstoken.do";

	private static $isemergentURL =     "http://lgn.yy.com/message/isemergentSecurekey.do";
	private static $isforcechkURL =     "http://lgn.yy.com/message/isforcechkAccesstoken.do";	
	private static $reportversionURL =  "http://lgn.yy.com/message/rptver.do";	
	function __construct($appid, $appkey) {

		$this->app_id = $appid;
		$this->app_key = $appkey;
		$this->sig_method = new OAuthSignatureMethod_HMAC_SHA1();
		$this->app_consumer = new OAuthConsumer($this->app_id, $this->app_key, NULL);	
		$this->errorinfo = NULL;	
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
		
		$this->access_token = $rt['oauth_token'];
		$this->access_token_secret = $rt['oauth_token_secret'];
		$this->uprofile_username = $rt['username'];
		$this->uprofile_yyuid = $rt['yyuid'];
		
		if (isset($oauth_mckey4cookie)){
			$oauth_mckey4cookie = $rt['oauth_mckey4cookie'];
		}
		if(!empty($this->access_token) && !empty($this->access_token_secret)){
			return new OAuthConsumer($this->access_token, $this->access_token_secret);
		} else {
			return False;
		}
	}
	
	function getUserProfile($accesstoken) {
		if(empty($accesstoken) || $accesstoken!=$this->access_token)
			return false;
	
		return array($this->uprofile_username, $this->uprofile_yyuid);
	}
	
	function validAccessToken($access_token, $yyuid, $username){
		 $this->errorinfo = NULL;
		if(empty($access_token) || (empty($yyuid) && empty($username))){
			$this->errorinfo = "parameter[access_token or yyuid] is empty";
			return False;
		}
		try {
			$parameters = array();
			if(!empty($yyuid)){
				$parameters["yyuid"] = $yyuid;
			}else{
				$parameters["username"] = $username;
			}
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
			$this->errorinfo = $response;
			return False;
		} catch (OAuthException $e){
//			var_dump($e);
			$this->errorinfo = $e->getMessage();
			return False;
		}
	}
	
	/**
	 * reqDomainArray 要写cookie的域名数组 
	 */
	function getWriteCookieURL($access_token, $yyuid, $oauth_mckey4cookie,$reqDomainArray){
		if(empty($access_token) or empty($yyuid) or empty($oauth_mckey4cookie)){
			return false;
		}
		$sig_key = $this->app_key.'_'.$access_token->secret;
		$sig_content = $this->app_id.'_'.$access_token->key.'_'.$oauth_mckey4cookie.'_'.urlencode($yyuid);
		$signature = base64_encode(hash_hmac('sha1', $sig_content, $sig_key, true));
		
		$reqDomainArray = $this->getDefaultDomainArray($reqDomainArray);
		$cookieURL = "https://".$reqDomainArray[0].self::$writeCookieURI;
		$cookieURL = $cookieURL.'?oauth_mckey4cookie='.$oauth_mckey4cookie.'&oauth_signature='.urlencode($signature);
		for ($i = 1;$i < count($reqDomainArray); $i++){
			$reqDomain = $reqDomainArray[$i];
			if($i == 1){
				$cookieURL = $cookieURL.'&reqDomainList='.$reqDomain;
			}else{
				$cookieURL = $cookieURL.','.$reqDomain;
			}
		}
		
		return $cookieURL;
	}
	
	/**
	 * 获取默认写cookie域名数组：array(DOMAIN_DUOWAN,DOMAIN_YY,DOMAIN_KUAIKUAI)
	 */
	function getDefaultDomainArray($reqDomainArray){
		if(count($reqDomainArray) > 0){
			return $reqDomainArray;
		}else{
			return array(DOMAIN_YY,DOMAIN_DUOWAN,DOMAIN_KUAIKUAI);
		}
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
			"appid" => $this->app_id,
			"type" => SDK_TYPE,
			"version" => SDK_VERSION			
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
	
	function changeAccesstoken($appid_in_cookie, $access_key_in_cookie, $yyuid_in_cookie){
		
		try {
			$parameters = array(
				"appid"                 => $this->app_id,
				"appidInCookie"         => $appid_in_cookie,
				"accesstokenInCookie"   => $access_key_in_cookie,
				"usernameInCookie"      => $yyuid_in_cookie,
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
		} catch (Exception $e){
//			var_dump($e);
			return False;
		}
	}

	/**
	 * 检查是否要紧急更新udbkey
	 */
	function isemergentSecureKey(){
		$parameters = array(
			"appid" => $this->app_id
		);
		$api_req = OAuthRequest::from_consumer_and_token($this->app_consumer, NULL, "POST", self::$isemergentURL,$parameters);
		$api_req->sign_request($this->sig_method, $this->app_consumer, NULL);
		
		// 请求api
		$headers = array($api_req->to_header());
		$response = $this->__requestServer($headers, self::$isemergentURL, $parameters);
		if (!$response){
			return false;
		}
		if(strcasecmp($response, "true") == 0){
			return true;
		}
		return false;
	}
	/**
	 * 检查是否要强制验证accesstoken时效性
	 */
	function isforcechkAccesstoken(){
		$parameters = array(
			"appid" => $this->app_id
		);
		$api_req = OAuthRequest::from_consumer_and_token($this->app_consumer, NULL, "POST", self::$isforcechkURL,$parameters);
		$api_req->sign_request($this->sig_method, $this->app_consumer, NULL);
		
		// 请求api
		$headers = array($api_req->to_header());
		$response = $this->__requestServer($headers, self::$isforcechkURL, $parameters);
		if (!$response){
			return false;
		}
		if(strstr($response, "true")){
			return true;
		}
		return false;
	}
	/**
	 * 上报sdk版本信息,每2小时上报一次
	 */
	function reportVersion(){
		$storeKey = "sdk_rptver_flag";
		$storeValue = apc_fetch($storeKey); 
		if(empty($storeValue)){
			apc_store($storeKey, "1", 7200);//每2小时上报一次
		
			$parameters = array(
				"appid" => $this->app_id,
				"type" => SDK_TYPE,
				"version" => SDK_VERSION
			);
			$api_req = OAuthRequest::from_consumer_and_token($this->app_consumer, NULL, "POST", self::$reportversionURL,$parameters);
			$api_req->sign_request($this->sig_method, $this->app_consumer, NULL);
			
			// 请求api
			$headers = array($api_req->to_header());
			$response = $this->__requestServer($headers, self::$reportversionURL, $parameters);
			if (!$response){
				return false;
			}
			return $response;
		}else{
			return "no report";
		}
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
		
		 $SSL = substr($url, 0, 8) == "https://" ? true : false;  
		if($SSL){
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 信任任何证书  
        	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1); // 检查证书中是否设置域名  
		}
		
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
