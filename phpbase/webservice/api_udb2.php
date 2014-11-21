<?php
//error_reporting(0);
define('REQUEST_TOKEN', 'REQUEST_TOKEN');
define('APPID', '5421');
define('APPKEY', 'VhSIHEHg84fHAsFaYcWU0hcl7dePgP7H');
define('DWAE_MMC_HOST_1', '183.61.6.50');
define('DWAE_MMC_PORT_1', '11219');
if( !function_exists('apc_store') ){
	function apc_store(){}
}
if( !function_exists('apc_fetch') ){
	function apc_fetch(){}
}
//参数错误
if( empty($_POST['COOKIE']) || empty($_POST['HTTP_USER_AGENT']) || empty($_POST['HTTP_HOST']) ){
	echo -2;
	exit;
}
//处理通行证名称含有中文的问题
$_POST['COOKIE']['username'] = urldecode($_POST['COOKIE']['username']);
$cookie = $_POST['COOKIE'];
$user_agent = $_POST['HTTP_USER_AGENT'];
$domain     = $_POST["HTTP_HOST"];
$key_file="";
$ver = @$_POST["ver"];
if( empty($ver) || !in_array($ver, array(1,2)) ) $ver = 2;

require(dirname(__FILE__).'/udbsdk/dwCache.php');
$cache = new dwCache('udb');
$cache_key = md5(json_encode($cookie)); 

$result = $cache->get($cache_key);
if( empty($result) || empty($result["username"]) || empty($result["yyuid"]) ){
	require(dirname(__FILE__)."/udbsdk/app2/OAuthCookieClient.php");
	require(dirname(__FILE__)."/udbsdk/app2/OAuthCache.php");
	$result=array();
	$cookieClient = new OAuthCookieClient(APPID, APPKEY, $cookie, $user_agent, $domain, new OAuthCache_File($key_file));
	if($cookieClient->validate()){
		$result["username"] =$cookieClient->getUserName();
		$result["yyuid"]= preg_replace("/[^0-9]/", '', $cookieClient->getYYUID());
		$cache->set($cache_key, $result, 3600*24);
	}
}

if(isset($_POST['format']) && 'json' == $_POST['format']){
	echo json_encode($result);
}else{
	echo  serialize($result);
}
?>
