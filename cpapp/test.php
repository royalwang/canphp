<?php

function create(){
	file_put_contents('cache.key.php', str_repeat(chr(0), 10009*2) );
}

//create();

function time33($str) { 
	$hash = 0; 
	$len = min(strlen($str),8);
	for($i=0; $i<$len; $i++) { 
		$hash = $hash*33 + ord($str[$i]); 
	} 
	return $hash; 
}
	
//echo crc32(1235555);
function test_func($func){
	$t1 = microtime(true);
	$arr = array();
	for($i=0; $i<100000; $i++){
		//time33(md5('wan_uid_1112'.$i));
		$key = time33(md5('wan_key_uid_11'.$i))%10009;
		if($key>0){
			$arr[$key]=1;
		}
	}
	echo count($arr);
	$t2 = microtime(true);
	echo 'time:'.($t2-$t1)*1000;
}

test_func('time33');