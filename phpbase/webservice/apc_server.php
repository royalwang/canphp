<?php
error_reporting(0);
$allow = array('113.108.232.34',);
$ip = empty($_SERVER["HTTP_X_REAL_IP"]) ? $_SERVER["REMOTE_ADDR"] : $_SERVER["HTTP_X_REAL_IP"];
if(!in_array($ip, $allow))die('-1');
$apps = array(
	'121.9.221.134',
	//add
	'121.9.221.170',
	'121.9.221.210',
	'121.9.221.162',
	'121.9.221.235',
	'121.9.221.209',
	'121.9.221.148',
);
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8" />
<title>APC刷新</title>
</head>
<body>
<h2>APC刷新</h2>
<?php foreach($apps as $app):?>
<p>
服务器：<?php echo $app?> <iframe src="http://<?php echo $app?>/apc.php" width="50" height="10"></iframe>
</p><br />
<?php endforeach?>
</body>
</html>