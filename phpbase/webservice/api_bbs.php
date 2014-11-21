<?php
//error_reporting(0);
require(dirname(__FILE__).'/config.php');

if( 'pm' == $_GET['do'] ){
	$udb_name = $_GET['udb'];
	$title = $_GET['title'];
	$content = $_GET['content'];
	
	$bbs_uid = @file_get_contents('http://bbs.duowan.com/api/makeRandomUser.php?udbname='.$udb_name);
        $bbs_uid = unserialize($bbs_uid);
	$bbs_uid = (int)$bbs_uid['uid'];
	if($bbs_uid > 0){
		$str = "uid=".$bbs_uid."&subject=".$title."&message=".$content;
		$useragent = 'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; CIBA; .NET CLR 2.0.50727; .NET CLR 3.0.04506.648; .NET CLR 3.5.21022)';

    	$ch = curl_init();
    	curl_setopt($ch,CURLOPT_URL,"http://bbs.duowan.com/api/pm.php");
    	curl_setopt($ch,CURLOPT_HEADER,1);
    	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    	curl_setopt($ch,CURLOPT_POST,true);
    	curl_setopt($ch,CURLOPT_NOBODY,false);
    	curl_setopt($ch,CURLOPT_USERAGENT,$useragent);
    	curl_setopt($ch,CURLOPT_POSTFIELDS,$str);

    	$str = curl_exec($ch);
    	curl_close($ch);

		echo $str;
	}else{
		echo '-1';
	}
}elseif( 'getid' == $_GET['do'] ){
	$udb_name = $_GET['udb'];
	echo @file_get_contents('http://bbs.duowan.com/api/makeRandomUser.php?udbname='.$udb_name);

}else{
	echo '-2';
}

