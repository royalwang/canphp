<?php
error_reporting(0);
$allow = array('113.108.232.34',);
$ip = empty($_SERVER["HTTP_X_REAL_IP"]) ? $_SERVER["REMOTE_ADDR"] : $_SERVER["HTTP_X_REAL_IP"];
if(!in_array($ip, $allow)){
	header("Status: 404 Not Found");exit(0);
}

if(apc_clear_cache()){
?>
<html><body style="background:green;"></body></html>
<?php
}else{
?>
<html><body style="background:red;"></body></html>
<?php 
}