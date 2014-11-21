<?php
include(dirname(__FILE__).'/ManagerBase.php');
class Manager extends ManagerBase {
	public function setLogon($udb_name) {
		return array(
			'name' => $udb_name,
			'admin' => '1',
		);
	}
	
	public function add($udb_name) {
		if(empty($udb_name))return;
		$this->table('udbs')->insert(array(
			'udb_name' => $udb_name,
			'admin' => 0,
			'created' => time(),
		));
	}
	
	public function del($udb_name) {
		if(empty($udb_name))return;
		$this->table('udbs')->remove(array(
			'udb_name' => $udb_name,
			'admin' => 0,
		));
	}
	
	public function all() {
		return $this->table('udbs')->find()->sort(array("created" => -1));;
	}
}
