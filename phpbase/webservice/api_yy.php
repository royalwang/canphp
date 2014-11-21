<?php
define('APP_ID', 902);
define('API_URL', 'http://121.14.43.116:8080/sysmsg/SysMsgIntf');
define('LOG_PATH', './tmp/');//日志存放目录，后面带斜杠"/"

//ip白名单
$white_ip_array = array(
	'183.60.177.224/27',//公司内网
	'58.248.138.0/28',//公司内网
	'113.108.232.32/28',//公司内网	

	'183.136.136.171',//大神带玩，前台正式
	'183.136.136.175',//大神带玩，前台正式
	'183.136.136.178',//大神带玩，前天正式
	'115.231.33.147',//大神带玩，前台正式
	'115.231.33.148',//大神带玩，前台正式
	'115.231.33.149',//大神带玩，前天正式
	
	'183.61.6.39',//大神带玩，后台
	'183.61.6.41',//大神带玩，后台	
);
 
if( !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ) $_SERVER['REMOTE_ADDR'] = $_SERVER['HTTP_X_FORWARDED_FOR'];
if( !check_ip($white_ip_array, $_SERVER['REMOTE_ADDR']) ){
	return_code(-100);
}

if(empty($_POST['uid']) || empty($_POST['msg'])){
	return_code(-101);
}

$uid = trim($_POST['uid']);
$msg = trim($_POST['msg']);
$title = empty($_POST['title']) ? '温馨提示' : trim($_POST['title']);
$button_text = empty($_POST['button_text']) ? '关闭' : trim($_POST['button_text']);
$button_action = empty($_POST['button_action']) ? '' : trim($_POST['button_action']);

$ret = send($uid, $msg, $title, $button_text, $button_action);

return_code( $ret );

function send($uid, $msg, $title='温馨提示', $button_text='关闭', $button_action=''){
	if( empty($uid) || empty($msg) ) return -101;
	if( !empty($button_action) ) $button_action = 'action="'.$button_action.'"';

	$xml_msg = '<?xml version="1.0" ?>
			　　<sysmessage>
			　　<display_type>100</display_type>
			　　<message><!--frameattribute-->
			　　<poptype>common_pop</poptype>
			　　<effecttype>FadeEffect</effecttype>
			　　<closetime>20000</closetime>
			　　<style>RightBottomDirection</style>
			　　<width>280</width>
			　　<height>190</height>
			　　<!--widget attribute-->
			　　<title textColor="0,0,0">'. $title .'</title>
			<context actionClose="true" actionLogin="false" >
			<![CDATA[<html><body>'. $msg . '</body></html>]]></context>
			　　<logo>:/theme/mainframe/YYboy16.png</logo>
			　　<bgcolor>6699BB</bgcolor>
			　　<buttonItemSpace>1</buttonItemSpace>
			<yesButton '. $button_action .' textColor="0,0,0" iconPath=":/theme/mainframe/icon_find_indicator.png" transparentBackground="true" actionClose="true" actionLogin="false">'. $button_text .'</yesButton>
			　</message>
			</sysmessage>';

	$xml_data = '<?xml version="1.0"?>
			<sysmessage>
				<type>1</type>
				<app_id>'. APP_ID .'</app_id>
				<uid>'. $uid .'</uid>
				<saveOffline>1</saveOffline>
				<message>'.$xml_msg.'</message>
			</sysmessage>';	
	$status = curlPost(API_URL, array('msg'=>$xml_data) );
	$status = trim($status);
	$file = LOG_PATH . 'yy_' . date("Y-m") . '.log';
	$date = date('Y-m-d H:i:s');
	$log_info = "$uid\t $msg\t $date\t $status\r\n";
	file_put_contents($file, $log_info, FILE_APPEND);
	return $status;
}
	
function curlPost($url, $data = array()) {
	$c = curl_init(); 
	curl_setopt($c, CURLOPT_RETURNTRANSFER, true); 
	curl_setopt($c, CURLOPT_URL, $url); 
	curl_setopt($c, CURLOPT_POST, true); 
	curl_setopt($c, CURLOPT_TIMEOUT, 5); 
	curl_setopt($c, CURLOPT_POSTFIELDS, http_build_query($data)); 	
	$ret = curl_exec($c); 
	curl_close($c); 
	return $ret;
}


function check_ip($ip_array = array(), $remote_ip = ''){
	$remote_ip = empty($remote_ip) ? $_SERVER['REMOTE_ADDR'] : $remote_ip;
	//判断ip是否在白名单
	foreach($ip_array as $ip){
		$ip_info = explode('/', $ip);
		$mask = isset($ip_info[1]) ? $ip_info[1] : 32;
		if(substr(sprintf("%032b", ip2long($ip_info[0])), 0, $mask) === substr(sprintf("%032b", ip2long($remote_ip)), 0, $mask)){
			return true;
		}
	}
	return false;
}

function return_code($code){
	echo $code;
	exit;
}