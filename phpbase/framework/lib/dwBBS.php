<?php
/**
 * ��̳�ӿ�
 *
 *
 *	include('dwBBS.php');
 *	$bbs = new dwBBS;
 *	echo $bbs->pm('UDB����', '���ű���', '��������');
 *
 **/
class dwBBS extends dwaeObject
{
	function pm($udb_name, $title, $content){
		$url = DWAE_API_BBS."?do=pm&udb={$udb_name}&title={$title}&content={$content}";
		return $this->fetchURL($url);
	}
	
	function id($udb_name){
		$url = DWAE_API_BBS."?do=getid&udb={$udb_name}";
		return unserialize($this->fetchURL($url));
	}
}
