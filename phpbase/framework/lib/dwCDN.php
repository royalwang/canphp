<?php
/**
 * Ò³Ãæ»º´æ
 * 
 */
class dwCDN extends dwaeObject
{
	var $dirs = array();
	var $pages = array();
	
	public function setDir($dir){
		$this->dirs[] = $dir;
	}

	public function setPage($page){
		$this->pages[] = $page;
	}
	
	public function fresh(){
		return $this->fetchURL(DW_API_CDN.'?page='. urlencode($this->_url($this->pages)) . '&dirs=' . urlencode($this->_url($this->dirs)));
	}
	
	private function _url($arr){
		$urls = ""; 
		$num = count($arr);
		$cnt = 1;
		foreach($arr as $a) {
			$a = trim($a);
			if(!empty($a)) {
				if($cnt < $num) {
						$urls .= $a."%0D%0A"; 
						$cnt++;
				} else {
						$urls .= $a;
				}
			}
		}
		return $urls;
	}
}