<?php

// 人性化时间显示函数
function uetime($times){
	if( $times == '' || $times==0) return false;
	$dtime = (int)$times;
	$ptime = time() - $dtime;
	if( $ptime < 60 ){
		$pct = sprintf("%d秒前",$ptime);
	}else if( $ptime > 60 && $ptime < 3600 ){
		$pct = sprintf("%d分钟前",ceil( $ptime / 60 ));
	}else if( $ptime > 3600 && $ptime < (3600 * 24) ){
		$pct = sprintf("%d小时前", floor( $ptime / 3600 ), ceil( ( $ptime % 3600 ) / 60  ));
	}else if( $ptime > (3600 * 24) && $ptime < (3600*24*7)){
		$d = $ptime / (3600*24);
		$h = ( $ptime % (3600*24)) / 3600;
		$m = ceil((($ptime % (3600*24)) % 3600 ) / 60 );
		//$pct = sprintf("%d天%d小时%d分钟前", $d, $h, $m );
		$pct = $h>1?sprintf("%d天前", $d, $h):sprintf("%d天前",$d);
	}else if( $ptime > (3600 * 24 *7) && $ptime < (3600*24*30)){
		$w = $ptime / (3600*24*7);
		$d = ($ptime % (3600*24*7))/(3600*24);
					$h=(($ptime%(3600*24*7))%(3600*24))/3600;
		$m = ceil((($ptime % (3600*24)) % 3600 ) / 60 );
		//$pct = sprintf("%d星期%d天%d小时前",$w, $d, $h);
		$pct =$d>=1?sprintf("%d星期前",$w, $d):sprintf("%d星期前",$w);
	}else if( $ptime > (3600 * 24 *30) && $ptime < (3600*24*365)){
		$mt = $ptime / ( 3600*24*30);
		$d = ($ptime % ( 3600*24 * 30))/ (3600*24);
		$h = ( ($ptime % ( 3600*24 * 30))% (3600*24)) / 3600;
		$m = ceil( ( ($ptime % ( 3600*24 * 30))% (3600*24)) % 3600 / 60 );
		//$pct = sprintf("%d月%d天%d小时%d分钟前", $mt, $d, $h, $m );
		$pct =$d>=1?sprintf("%d个月前",$mt,$d):sprintf("%d个月前",$mt);
	}else{ //n年前
		$y = $ptime / (3600*24*365);
		$m = ($ptime % (3600*24*30*12))/(3600*24*30);
		$pct = $m>=1?sprintf("%d年前",$y,$m):sprintf("%d年前",$y);
	}
	return $pct;
}
/**
 *
 * 下载文件，代替readfile
 * @param  $fileName       下载文件的路径
 * @param  $fancyName      下载到用户电脑的文件名
 * @param  $forceDownload  是否强制下载
 * @param  $speedLimit     速度限制
 * @param  $contentType    文件MIME类型
 */
function readfile_chunked($fileName, $fancyName = '', $forceDownload = true, $speedLimit = 0, $contentType = '') {
    if (!is_readable($fileName)) {
        header("HTTP/1.1 404 Not Found");
        return false;
    }
    $fileStat = stat($fileName);
    $lastModified = $fileStat['mtime'];
    $md5 = md5($fileStat['mtime'] .'='. $fileStat['ino'] .'='. $fileStat['size']);
    $etag = '"' . $md5 . '-' . crc32($md5) . '"';
    header('Last-Modified: ' . gmdate("D, d M Y H:i:s", $lastModified) . ' GMT');
    header("ETag: $etag");
    if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) && strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) >= $lastModified) {
        header("HTTP/1.1 304 Not Modified");
        return true;
    }
    if (isset($_SERVER['HTTP_IF_UNMODIFIED_SINCE']) && strtotime($_SERVER['HTTP_IF_UNMODIFIED_SINCE']) < $lastModified) {
        header("HTTP/1.1 304 Not Modified");
        return true;
    }
    if (isset($_SERVER['HTTP_IF_NONE_MATCH']) &&  $_SERVER['HTTP_IF_NONE_MATCH'] == $etag){
        header("HTTP/1.1 304 Not Modified");
        return true;
    }
    if ($fancyName == '')$fancyName = basename($fileName);
    if ($contentType == '')$contentType = 'application/octet-stream';
    $fileSize = $fileStat['size'];
    $contentLength = $fileSize;
    $isPartial = false;
    if (isset($_SERVER['HTTP_RANGE'])) {
        if (preg_match('/^bytes=(\d*)-(\d*)$/', $_SERVER['HTTP_RANGE'], $matches)) {
            $startPos = $matches[1];
            $endPos = $matches[2];
            if ($startPos == '' && $endPos == '')return false;
            if ($startPos == ''){
                $startPos = $fileSize - $endPos;
                $endPos = $fileSize - 1;
            }elseif ($endPos == ''){
                $endPos = $fileSize - 1;
            }
            $startPos = $startPos < 0 ? 0 : $startPos;
            $endPos = $endPos > $fileSize - 1 ? $fileSize - 1 : $endPos;
            $length = $endPos - $startPos + 1;
            if ($length < 0)return false;
            $contentLength = $length;
            $isPartial = true;
        }
    }
    // send headers
    if($isPartial){
        header('HTTP/1.1 206 Partial Content');
        header("Content-Range: bytes $startPos-$endPos/$fileSize");
    }else{
        header("HTTP/1.1 200 OK");
        $startPos = 0;
        $endPos = $contentLength - 1;
    }
    header('Pragma: cache');
    header('Cache-Control: public, must-revalidate, max-age=0');
    header('Accept-Ranges: bytes');
    header('Content-type: ' . $contentType);
    header('Content-Length: ' . $contentLength);
    if ($forceDownload)header('Content-Disposition: attachment; filename="' . rawurlencode($fancyName). '"');
    header("Content-Transfer-Encoding: binary");
    $bufferSize = 2048;
    if ($speedLimit != 0)$packetTime = floor($bufferSize * 1000000 / $speedLimit);
    $bytesSent = 0;
    $fp = fopen($fileName, "rb");
    fseek($fp, $startPos);
    while ($bytesSent < $contentLength && !feof($fp) && connection_status() == 0 ){
        if ($speedLimit != 0) {
            list($usec, $sec) = explode(" ", microtime());
            $outputTimeStart = ((float)$usec + (float)$sec);
        }
        $readBufferSize = $contentLength - $bytesSent < $bufferSize ? $contentLength - $bytesSent : $bufferSize;
        $buffer = fread($fp, $readBufferSize);
        echo $buffer;
        ob_flush();
        flush();
        $bytesSent += $readBufferSize;
        if ($speedLimit != 0){
            list($usec, $sec) = explode(" ", microtime());
            $outputTimeEnd = ((float)$usec + (float)$sec);
            $useTime = ((float) $outputTimeEnd - (float) $outputTimeStart) * 1000000;
            $sleepTime = round($packetTime - $useTime);
            if ($sleepTime > 0)usleep($sleepTime);
        }
    }
    return true;
}

function go404(){
	header('Location: http://www.duowan.com/s/404/404.html');
}
function alert($msg, $url = ''){
	$url = empty($url) ? "window.history.back();" : "location.href=\"{$url}\";";
	echo "<html><head><meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\"><script>function sptips(){alert(\"{$msg}\");{$url}}</script></head><body onload=\"sptips()\"></body></html>";
	exit;
}

function authcode($string, $operation = 'DECODE', $key = '', $expiry = 0) {
	$ckey_length = 4;
	$key = md5($key != '' ? $key : $_SERVER["HTTP_HOST"]);
	$keya = md5(substr($key, 0, 16));
	$keyb = md5(substr($key, 16, 16));
	$keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';
	$cryptkey = $keya.md5($keya.$keyc);
	$key_length = strlen($cryptkey);
	$string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
	$string_length = strlen($string);
	$result = '';
	$box = range(0, 255);
	$rndkey = array();
	for($i = 0; $i <= 255; $i++) {
		$rndkey[$i] = ord($cryptkey[$i % $key_length]);
	}
	for($j = $i = 0; $i < 256; $i++) {
		$j = ($j + $box[$i] + $rndkey[$i]) % 256;
		$tmp = $box[$i];
		$box[$i] = $box[$j];
		$box[$j] = $tmp;
	}
	for($a = $j = $i = 0; $i < $string_length; $i++) {
		$a = ($a + 1) % 256;
		$j = ($j + $box[$a]) % 256;
		$tmp = $box[$a];
		$box[$a] = $box[$j];
		$box[$j] = $tmp;
		$result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
	}
	if($operation == 'DECODE') {
		if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
			return substr($result, 26);
		} else {
			return '';
		}
	} else {
		return $keyc.str_replace('=', '', base64_encode($result));
	}
}
function mcut($str, $length, $suffix = "..."){
	$strcut = '';
	$length = $length*3;
	if(strlen($str) > $length) {
		for($i = 0; $i < $length - 2; $i++) {
			if( ord($str[$i]) > 127 ){
				$strcut .= $str[$i].$str[++$i].$str[++$i];
			}else{
				$strcut .= $str[$i];
			}
		}
		return $strcut.$suffix;
	} else {
		return $str;
	}
}
function __mkdirs($dir, $mode = 0777)
{
	if (!is_dir($dir)) {
		__mkdirs(dirname($dir), $mode);
		return @mkdir($dir, $mode);
	}
	return true;
}
function __rmdirs($dir) {
	if (is_dir($dir)) {
		$objects = scandir($dir);
		foreach ($objects as $object) {
			if ($object != "." && $object != "..") {
			if (filetype($dir.DIRECTORY_SEPARATOR.$object) == "dir") __rmdirs($dir.DIRECTORY_SEPARATOR.$object); else unlink($dir.DIRECTORY_SEPARATOR.$object);
			}
		}
		reset($objects);
		rmdir($dir);
	}
}
function getIP(){
	if (!empty($_SERVER['HTTP_CDN_SRC_IP'])){ //ip from cdn
			$ip = $_SERVER['HTTP_CDN_SRC_IP'];
	}elseif (!empty($_SERVER['HTTP_CLIENT_IP'])){ //check ip from share internet
			$ip = $_SERVER['HTTP_CLIENT_IP'];
	}elseif (!empty($_SERVER['HTTP_X_REAL_IP'])){ //check ip from share internet
			$ip = $_SERVER['HTTP_X_REAL_IP'];
	}elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){  //to check ip is pass from proxy
		$ips = explode (", ", $_SERVER['HTTP_X_FORWARDED_FOR']);
		for ($i = 0; $i < count($ips); $i++) {
				 if (!eregi ("^(10|172\.16|192\.168)\.", $ips[$i])){
						   $ip = $ips[$i];
						   break;
				 }
		}
	}else{
			$ip = $_SERVER['REMOTE_ADDR'];
	}
	return $ip;
}