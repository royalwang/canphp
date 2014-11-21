<?php
abstract class ManagerBase extends Nodel {
	public $udb_name = null;
	public function check(){
		$userInfo = $this->getUser();
		if(!$userInfo){
			// session不存在则检查UDB
			$userInfo = obj('dwUDB')->isLogin();
			if(!$userInfo){
				// udb也不存在则返回出错
				return false;
			}else{
				// udb登录的，则设置session资料
				$this->setUser($this->setLogon($userInfo['username']));
			}
		}
		$this->udb_name = $userInfo['username'];
		return true;
	}
	
	public function getUser() {
		return isset($_SESSION[$GLOBALS['app_id']]['userInfo']) ?
			$_SESSION[$GLOBALS['app_id']]['userInfo'] : false;
	}
	
	public function setUser($data) {
		@$_SESSION[$GLOBALS['app_id']]['userInfo'] = $data;
	}
	
	public function getMenuNode() {
		return 'DEFAULT';
	}
	
	public function setLogon($udb_name) {
		return $udb_name;
	}
}
