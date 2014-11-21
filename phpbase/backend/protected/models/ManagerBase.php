<?php
abstract class ManagerBase extends Nodel {
	public $udb_name = null;
	public function check(){
		$userInfo = $this->getUser();
		if(!$userInfo){
			// session����������UDB
			$userInfo = obj('dwUDB')->isLogin();
			if(!$userInfo){
				// udbҲ�������򷵻س���
				return false;
			}else{
				// udb��¼�ģ�������session����
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
