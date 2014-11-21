<?php
class Nodel{
	protected $table_name;

	//public function cache($cached_time = 3600) {
	//	
	//	return new DBCache;
	//}
	
	protected function table($table_name=null, $db_name=null) {
		$db_name = ($db_name == null) ? $GLOBALS['mongo']['MONGO_DB'] : $db_name;
		$table_name = ($table_name == null) ? $this->table_name : $table_name;
		
		if(!isset($GLOBALS['db_tmp_mongo_collection'][$db_name][$table_name])){
			if(!isset($GLOBALS['db_tmp_mongo_collection'])) $GLOBALS['db_tmp_mongo_collection'] = array();
			if(!isset($GLOBALS['db_tmp_mongo_collection'][$db_name])) $GLOBALS['db_tmp_mongo_collection'][$db_name] = array();
			
			$GLOBALS['db_tmp_mongo_collection'][$db_name][$table_name] = new MongoCollection($this->db($db_name), $table_name);
		}
		return $GLOBALS['db_tmp_mongo_collection'][$db_name][$table_name];
	}
	
	protected function db($db_name=null) {
		if(!isset($GLOBALS['db_tmp_mongo']))$this->_init();
		
		$db_name = ($db_name == null) ? $GLOBALS['mongo']['MONGO_DB'] : $db_name;
		if(!isset($GLOBALS['db_tmp_mongodb'][$db_name])){
			if(!isset($GLOBALS['db_tmp_mongodb'])) $GLOBALS['db_tmp_mongodb'] = array();
			if( isset( $GLOBALS['mongo']['MONGO_USERNANE'] ) && isset( $GLOBALS['mongo']['MONGO_PASSWD'] ) ){
			    $GLOBALS['db_tmp_mongo']->selectDB($db_name)->authenticate( $GLOBALS['mongo']['MONGO_USERNANE'], $GLOBALS['mongo']['MONGO_PASSWD'] );
			    $GLOBALS['db_tmp_mongodb'][$db_name] = $GLOBALS['db_tmp_mongo']->selectDB($db_name);
			}else{
			    $GLOBALS['db_tmp_mongodb'][$db_name] = $GLOBALS['db_tmp_mongo']->selectDB($db_name);
			}
			
		}
		return $GLOBALS['db_tmp_mongodb'][$db_name];
	}

	private function _init() {
		$param = array();
		if( isset( $GLOBALS['mongo']['MONGO_USERNANE'] ) && isset( $GLOBALS['mongo']['MONGO_PASSWD'] )  ){
		    $param = array( 'username'=> $GLOBALS['mongo']['MONGO_USERNANE'] , 'password'=>$GLOBALS['mongo']['MONGO_PASSWD'] );
		}
		try{
			$GLOBALS['db_tmp_mongo'] = new Mongo('mongodb://'.$GLOBALS['mongo']['MONGO_HOST'].':'.$GLOBALS['mongo']['MONGO_PORT'], $param );
		}catch(Exception $e){
			throw new Exception('mongodb cannot connect on'.$GLOBALS['mongo']['MONGO_HOST'].':'.$GLOBALS['mongo']['MONGO_PORT']);
		}
	}
}
