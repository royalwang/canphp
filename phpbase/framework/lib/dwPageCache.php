<?php

class dwPage
{
	private $mmc = null;
	function __construct(){
		$this->mmc = new Memcache();
		if(defined('DWAE_MMCPAGE_HOST_1'))$this->mmc->addServer(DWAE_MMCPAGE_HOST_1, DWAE_MMCPAGE_PORT_1);
		if(defined('DWAE_MMCPAGE_HOST_2'))$this->mmc->addServer(DWAE_MMCPAGE_HOST_2, DWAE_MMCPAGE_PORT_2);
	}
	
	function init(){
		if(!$this->mmc)return;
		$contents = $this->mmc->get($_SERVER['REQUEST_URI']);
		if($contents){
			echo $contents;
			exit(0);
		}
	}
	
	function cache($url, $contents, $expire = 3600){
		if(!$this->mmc)return;
		$this->mmc->set($url, $contents, 0, $expire);
	}
	
	function clear($pages){
		if(is_array($pages)){
			foreach($pages as $url){
				$this->mmc->delete($url);
			}
		}
	}
	
	protected function clearAll(){
		$this->mmc->flush();
	}
}
