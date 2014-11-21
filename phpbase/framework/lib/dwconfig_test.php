<?php
define('DWAE_TMP_PATH', '/tmp');
define('DWAE_API_URL', 'http://api.dwae.duowan.com');

define('DWAE_MYSQL_HOST', 'mysqlmaster1.dwae.duowan.com');
define('DWAE_MYSQL_PORT', isset($_SERVER['DWAE_MYSQL_PORT']) ? $_SERVER['DWAE_MYSQL_PORT'] : '');
define('DWAE_MYSQL_USER', isset($_SERVER['DWAE_MYSQL_USER']) ? $_SERVER['DWAE_MYSQL_USER'] : '');
define('DWAE_MYSQL_PASS', isset($_SERVER['DWAE_MYSQL_PASS']) ? $_SERVER['DWAE_MYSQL_PASS'] : '');
define('DWAE_MYSQL_DB', isset($_SERVER['DWAE_MYSQL_DBNAME']) ? $_SERVER['DWAE_MYSQL_DBNAME'] : '');

define('DWAE_MYSQL_HOST_SLAVE_1', 'mysqlslave1.dwae.duowan.com');
define('DWAE_MYSQL_PORT_SLAVE_1', isset($_SERVER['DWAE_MYSQL_PORT']) ? $_SERVER['DWAE_MYSQL_PORT'] : '');

define('DWAE_MMC_HOST_1', 'mmc1.dwae.duowan.com');
define('DWAE_MMC_PORT_1', isset($_SERVER['DWAE_MMC_PORT']) ? $_SERVER['DWAE_MMC_PORT'] : '');
define('DWAE_MMC_HOST_2', 'mmc2.dwae.duowan.com');
define('DWAE_MMC_PORT_2', isset($_SERVER['DWAE_MMC_PORT']) ? $_SERVER['DWAE_MMC_PORT'] : '');

define('DWAE_MONGO_HOST', 'mongo.dwae.duowan.com');
define('DWAE_MONGO_PORT', isset($_SERVER['DWAE_MONGO_PORT']) ? $_SERVER['DWAE_MONGO_PORT'] : '27017');
define('DWAE_MONGO_DB', isset($_SERVER['DWAE_MONGO_DB']) ? $_SERVER['DWAE_MONGO_DB'] : '');

define('DWAE_TT_HOST_1', 'tt1.dwae.duowan.com');
define('DWAE_TT_PORT_1', isset($_SERVER['DWAE_TT_PORT']) ? $_SERVER['DWAE_TT_PORT'] : '');

define('DWAE_API_MAIL', DWAE_API_URL.'/api_mail.php');
define('DWAE_API_TASK', DWAE_API_URL.'/api_task.php');
define('DWAE_API_UDB',  DWAE_API_URL.'/api_udb.php');
define('DWAE_API_CDN',  DWAE_API_URL.'/api_cdn.php');
define('DWAE_API_BBS',  DWAE_API_URL.'/api_bbs.php');
define('DWAE_API_STORAGE', DWAE_API_URL.'/api_storage.php');

abstract class dwaeObject
{
	function fetchURL($url, $method='GET'){
		/**
		$ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_NOSIGNAL, 1);
        //curl_setopt($ch, CURLOPT_TIMEOUT_MS, 200);
		if( 'POST' == strtoupper($method) ){
			curl_setopt($ch, CURLOPT_POST, TRUE);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
		}
        $data = curl_exec($ch);
		return $data;
		**/
		$url_parsed = parse_url($url);
		if(!isset($url_parsed["host"]))return false;
		$host = $url_parsed["host"];
		$port = isset($url_parsed["port"]) ? $url_parsed["port"] : 80;
		$path = isset($url_parsed["path"]) ? $url_parsed["path"] : '';
		if(isset($url_parsed["query"]) && $url_parsed["query"] != "")$path .= "?".$url_parsed["query"];
		$out = "$method $path HTTP/1.0\r\nHost: $host\r\n\r\n";
		$fp = fsockopen($host, $port, $errno, $errstr, 30);
		fwrite($fp, $out);
		$body = false;$in = '';
		while (!feof($fp)) {
			$s = fgets($fp, 1024);
			if($body)$in .= $s;
			if($s == "\r\n")$body = true;
		}
		fclose($fp);
		return $in;
		
	}
}
