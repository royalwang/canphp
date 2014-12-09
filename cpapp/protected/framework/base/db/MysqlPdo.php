<?php
namespace framework\base\db;

class MysqlPdoDriver {
	private $_writeLink = NULL; //主
	private $_readLink = NULL; //从
	private $_replication = false; //标志是否支持主从
	private $dbConfig = array();
	public $sql = "";
	protected $affectedRows = -1;
	
	public function __construct( $dbConfig = array() ){
		$this->dbConfig = $dbConfig;
	}

	//执行sql查询
	public function query($sql, $params=array() ){
		$sth = $this->_bindParams( $sql, $params, $this->_getReadLink());
		if( $sth->execute() ) return $sth->fetchAll(PDO::FETCH_ASSOC);
		$err = $sth->errorInfo();
		throw new Exception('Database SQL: "' . $sql. '". ErrorInfo: '. $err[2], 1);
	}
	
	//执行sql操作语句
	public function execute( $sql, $params=array() ){
		$sth = $this->_bindParams( $sql, $params, $this->_getWriteLink() );
		if( $sth->execute() ) return $sth->rowCount();
		$err = $sth->errorInfo();
		throw new Exception('Database SQL: "' . $sql. '". ErrorInfo: '. $err[2], 1);
	}
	
	public function insert($table, array $data){
		$values = array();
		foreach($data as $k=>$v){
			$keys[] = "`{$k}`"; 
			$values[":{$k}"] = $v; 
			$marks[] = ":{$k}";
		}
		$this->execute("INSERT INTO `{$table}` (".implode(', ', $keys).") VALUES (".implode(', ', $marks).")", $values);
		return $this->_getWriteLink()->lastInsertId();
	}
	
	public function update($table, array $condition, array $data){
		$values = array();
		foreach ($data as $k=>$v){
			$keys[] = "`{$k}`=:__{$k}";
			$values[":__".$k] = $v;			
		}
		$condition = $this->_where( $condition );
		return $this->execute("UPDATE `{$table}` SET ".implode(', ', $keys).$condition["_where"], $condition["_bindParams"] + $values);
	}
	
	public function delete( array $condition ){
		$condition = $this->_where( $condition );
		return $this->execute("DELETE FROM `{$table}` {$condition['_where']}", $condition["_bindParams"]);
	}
	
	//从结果集中取得一行作为关联数组，或数字数组，或二者兼有 
	public function fetchArray($sth, $result_type = PDO::FETCH_ASSOC) {
		return $this->unEscape( $sth->fetch($result_type) );
	}	
	
	//取得前一次 MySQL 操作所影响的记录行数
	public function affectedRows() {
		return $this->affectedRows;
	}
	
	//获取上一次插入的id
	public function lastId() {
		return $this->_getWriteLink()->lastInsertId();
	}
	
	//获取表结构
	public function getFields($table) {
		$this->sql = "SHOW FULL FIELDS FROM {$table}";
		$query = $this->query($this->sql);
		$data = array();
		while($row = $this->fetchArray($query)){
			$data[] = $row;
		}
		return $data;
	}
	
	//获取行数
	public function count($table, $where) {
		$this->sql = "SELECT count(*) FROM $table $where";
		$query = $this->query($this->sql);
        $data = $this->fetchArray($query);
		return $data['count(*)'];
	}
		
	//解析待添加或修改的数据
	public function parseData($options, $type) {
		//如果数据是字符串，直接返回
		if(is_string($options['data'])) {
			return $options['data'];
		}
		if( is_array($options) && !empty($options) ) {
			switch($type){
				case 'add':
						$data = array();
						$data['fields'] = array_keys($options['data']);
						$data['values'] = $this->escape( array_values($options['data']) );
						return " (`" . implode("`,`", $data['fields']) . "`) VALUES (" . implode(",", $data['values']) . ") ";
				case 'save':
						$data = array();
						foreach($options['data'] as $key => $value) {
								$data[] = " `$key` = " . $this->escape($value);
						}
						return implode(',', $data);
			default:return false;
			}
		}
		return false;
	}
	
	//解析查询条件
	public function parseCondition($options) {
		$condition = "";	
		if(!empty($options['where'])) {
			$condition = " WHERE ";
			if(is_string($options['where'])) {
				$condition .= $options['where'];
			} else if(is_array($options['where'])) {
					foreach($options['where'] as $key => $value) {
						 $condition .= " `$key` = " . $this->escape($value) . " AND ";
					}
					$condition = substr($condition, 0,-4);	
			} else {
				$condition = "";
			}
		}
		
		if( !empty($options['group']) && is_string($options['group']) ) {
			$condition .= " GROUP BY " . $options['group'];
		}
		if( !empty($options['having']) && is_string($options['having']) ) {
			$condition .= " HAVING " .  $options['having'];
		}
		if( !empty($options['order']) && is_string($options['order']) ) {
			$condition .= " ORDER BY " .  $options['order'];
		}
		if( !empty($options['limit']) && (is_string($options['limit']) || is_numeric($options['limit'])) ) {
			$condition .= " LIMIT " .  $options['limit'];
		}
		if( empty($condition) ) return "";
        return $condition;
	}
	
	private function _bindParams($sql, $params=array(), PDO $pdo){
		$sth = $pdo->prepare($sql);
		foreach((array)$params as $k=>&$v){
			$sth->bindValue($k, $v);
		}				
		return $sth;
	}

	private function _where( array $condition ){
		$result = array( '_where' => '', '_bind' => array());
		if(is_array($condition) && !empty($condition)){
			$fields = array(); $sql = null; $join = array();
			if(isset($condition[0]) && $sql = $condition[0]) unset($condition[0]);
			foreach( $condition as $key => $condition ){
				if(substr($key, 0, 1) != ":"){
					unset($condition[$key]);
					$condition[":".$key] = $condition;
				}
				$join[] = "`{$key}` = :{$key}";
			}
			if(!$sql) $sql = join(" AND ",$join);

			$result["_where"] = " WHERE ". $sql;
			$result["_bindParams"] = $condition;
		}
		return $result;
	}
	
	//数据库链接
	protected  function _connect( $isMaster = true ) {
		$dbArr = array();
		if( false==$isMaster && !empty($this->config['DB_SLAVE']) ) {	
			$master = $this->config;
			unset($master['DB_SLAVE']);
			for($this->config['DB_SLAVE'] as $k=>$v) {
				$dbArr[] = array_merge($master, $this->config['DB_SLAVE'][$k]);
			}
			shuffle($dbArr);
		} else {
			$dbArr[] = $this->config; //直接连接到主机
		}

		$pdo = null;
		$error = '';
		foreach($dbArr as $db) {
			$dsn = "mysql:host={$db['DB_HOST']};port={$db['DB_PORT']};dbname={$db['DB_NAME']};charset=$db['DB_CHARSET']";
			try{
				$pdo = new PDO($dsn, $db['DB_USER'], $db['DB_PWD']);
				break;
			}catch(PDOException $e){
				$error = $e->getMessage();
			}
		}
		
		if(!$pdo){
			throw new Exception('connect database error :'.$error, 500);
		}
		
		return $pdo;
	}

	//获取从服务器连接
    protected function _getReadLink() {
		if( !isset( $this->_readLink ) ) {
			$this->_readLink = $this->_connect( false );               
		}
		return $this->_readLink;
    }
	
	//获取主服务器连接
    protected function _getWriteLink() {
        if( !isset( $this->_writeLink ) ) {
            $this->_writeLink = $this->_connect( true );
        }
		return $this->_writeLink;
    }
	
	//关闭数据库
	public function __destruct() {
		if($this->_writeLink) {
			$this->_writeLink = NULL;
		}
		if($this->_readLink) {
			$this->_readLink = NULL;
		}
	}	
}