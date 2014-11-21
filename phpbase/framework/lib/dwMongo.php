<?php
function dwMongo($table, $database=null){
	return new dwMongo($table, $database);
}
class dwMongo
{
	private $__collection = null;
	private $__params_table = null;
	private $__params_database = null;
	public function __construct($table, $database=null)
	{
		$database = (null != $database) ? $database : DWAE_MONGO_DB;
		$this->__params_table = $table;
		$this->__params_database = $database;
		$this->__collection = $this->__selectConlletion(DWAE_MONGO_HOST, DWAE_MONGO_PORT);
	}
	
	public function __call($name, $args)
	{
		try{
			return call_user_func_array(array($this->__collection, $name) , $args);
		}catch( Exception $e ){
			error_log($e);
			$this->__slave();
			return call_user_func_array(array($this->__collection, $name) , $args);
		}
	}
	
	private function __slave()
	{
		$this->__collection = $this->__selectConlletion(DWAE_MONGO_HOST_SLAVE, DWAE_MONGO_PORT_SLAVE);
	}

	private function __selectConlletion($host, $port)
	{
		try{
			$m = new Mongo('mongodb://'.$host.':'.$port);
			$db = $m->selectDB($this->__params_database);
			return new MongoCollection($db, $this->__params_table);
		}catch(Exception $e){
			error_log($e);
			return false;
		}
	}

}
?>