<?php
error_reporting(0);
$interface_url = 'ccms.chinacache.com';
$user = 'duowan-zq';
$pass = 'Duowan@123';

$ref_url = urldecode($_GET['page']);
$ref_dirs = urldecode($_GET['dirs']);
$match = array();
$str = ""; 
$message = '';
$fp = fsockopen($interface_url, 80, $errno, $errstr, 30);
if (!$fp) {
	$message = "$errstr($errno)<br/>\n";
} else {
	$out = "HEAD /index.jsp?user=$user&pswd=$pass&ok=ok&urls=$ref_url&dirs=$ref_dirs HTTP/1.0\r\n";
	$out .= "Host: ccms.chinacache.com\r\n";
	$out .= "Connection: Close\r\n\r\n";
	fwrite($fp, $out);
	while (!feof($fp)) {
		$str .= fgets($fp, 128);
	}   
}   
if(preg_match('/(whatsup: content=)(\"[a-z]+\")/', $str, $match)) {
	$message = $match[0]."\n";
} elseif (preg_match('/urlexceed:([0-9]+)/', $str, $match)) {
	$message = "$match[0]\n";
} elseif (preg_match('/direxceed:([0-9]+)/', $str, $match)) {
	$message = $match[0]."\n";
}   
fclose($fp);
echo $message;
