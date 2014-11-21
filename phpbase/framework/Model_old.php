<?php
class Model{
	public $page = null;
	public $sql = '';
	protected $table_name;
	private static $_db;

	public function select( $conditions=array(), $field='', $order=null, $limit=null ){
		$field = !empty($field) ? $field : '*';
		$order = !empty($order) ? ' ORDER BY '.$order : '';
		$sql = ' FROM '.$this->table_name.$this->_where($conditions);
		if(is_array($limit)){
			if(! $total = $this->query('SELECT COUNT(*) as dw_counter '.$sql))return null;
			$limit = $limit + array(1, 10, 10);
			$limit = $this->pager($limit[0], $limit[1], $limit[2], $total[0]['dw_counter']);
			$limit = empty($limit) ? '' : ' LIMIT '.$limit['offset'].','.$limit['limit'];			
		}else{
			$limit = !empty($limit) ? ' LIMIT '.$limit : '';
		}
		return $this->query('SELECT '. $field . $sql . $order . $limit);
	}
	
	public function find( $conditions=array(),$field='', $order=null ){
		$field = !empty($field) ? $field : '*';
		$res = $this->select($conditions,$field, $order,1);
		return !empty($res) ? array_pop($res) : false;
	}
	
	public function update( $conditions=array(),$new_data=array() ){
		if( empty($conditions) ) return false;
		foreach ($new_data as $k=>$v){
			$setstr[] = $k."=".$this->escape($v);
		}
		return $this->execute("UPDATE ".$this->table_name." SET ".implode(', ', $setstr).$this->_where($conditions));
	}

	function incr($conditions=array(), $field, $optval = 1) {
		if( empty($conditions) ) return false;
		return $this->execute("UPDATE ".$this->table_name." SET `{$field}` = `{$field}` + {$optval} ".$this->_where($conditions));
	}
	
	public function delete( $conditions=array() ){
		if( empty($conditions) ) return false;
		return $this->execute("DELETE FROM ".$this->table_name.$this->_where( $conditions ));
	}
	
	public function insert($data){
		foreach($data as $k=>$v){
			$keys[] = $k;
			$values[] = $this->escape($v);
		}
		$this->execute("INSERT INTO ".$this->table_name." (".implode(', ', $keys).") VALUES (".implode(', ', $values).")");
		return Model::$_db->lastInsertId();
	}
	
	public function count( $conditions=null ){
		$count = $this->query("SELECT COUNT(*) AS total FROM ".$this->table_name.$this->_where($conditions));
		return isset($count[0]['total']) && $count[0]['total'] ? $count[0]['total'] : 0;
	}
	
	public function query($sql=null, $params=array() ){
		$this->sql = $sql;
		$sth = $this->_bindParams( $sql, $params );
		if( $sth->execute() ) return $sth->fetchAll(PDO::FETCH_ASSOC);
		$err = $sth->errorInfo();
		throw new Exception('Database SQL: "' . $sql. '". ErrorInfo: '. $err[2], 1);
	}
	
	public function execute( $sql=null, $params=array() ){
		$this->sql = $sql;
		$sth = $this->_bindParams( $sql, $params );
		if( $sth->execute() ) return $sth->rowCount();
		$err = $sth->errorInfo();
		throw new Exception('Database SQL: "' . $sql. '". ErrorInfo: '. $err[2], 1);
	}

	public function escape($str){
        if(is_null($str))return 'null';
		if(is_bool($str))return $str ? 1 : 0;
		if(is_int($str))return (int)$str;
		if(@get_magic_quotes_gpc())$str = stripslashes($str);
		return Model::$_db->quote($str);
	}
	
	public function __construct($table_name = null){
		if( $table_name )$this->table_name = $table_name;
		if( !is_object(Model::$_db) ) $this->_connect();
	}
	
	public function pager($page, $pageSize = 10, $scope = 10, $total)
	{
		$total_page = ceil( $total / $pageSize );
		$page = max(intval($page), 1);
		$this->page = array(
			'total_count' => $total, 
			'page_size'   => $pageSize,
			'total_page'  => $total_page,
			'first_page'  => 1,
			'prev_page'   => ( ( 1 == $page ) ? 1 : ($page - 1) ),
			'next_page'   => ( ( $page == $total_page ) ? $total_page : ($page + 1)),
			'last_page'   => $total_page,
			'current_page'=> $page,
			'all_pages'   => array(),
			'offset'      => ($page - 1) * $pageSize,
			'limit'       => $pageSize,
		);
		$scope = (int)$scope;
		
		if($total <= $pageSize){
			$this->page['all_pages'] =  array();
		}elseif($total_page <= $scope ){
			$this->page['all_pages'] = range(1, $total_page);
		}elseif( $page <= $scope/2) {
			$this->page['all_pages'] = range(1, $scope);
		}elseif( $page <= $total_page - $scope/2 ){
			$right = $page + (int)($scope/2);
			$this->page['all_pages'] = range($right-$scope+1, $right);
		}else{
			$this->page['all_pages'] = range($total_page-$scope+1, $total_page);
		}
		return $this->page;
	}

	public function __destruct(){
		Model::$_db = null;	
	}
	
	public function cache($cached_time = 3600){
		$cache = obj(__CLASS__);
		$cache->linked_object = $this;
		$cache->cached_time = $cached_time;
		return $cache;
	}
	
	private function _connect(){
		if( !isset($GLOBALS['mysql']) || empty($GLOBALS['mysql']) ) return FALSE;
		Model::$_db = new PDO('mysql:dbname='.$GLOBALS['mysql']['MYSQL_DB'].';host='.$GLOBALS['mysql']['MYSQL_HOST'].';port='.$GLOBALS['mysql']['MYSQL_PORT'], $GLOBALS['mysql']['MYSQL_USER'], $GLOBALS['mysql']['MYSQL_PASS'], array(PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES \''.$GLOBALS['mysql']['MYSQL_CHARSET'].'\''));
	}

	private function _bindParams($sql, $params=array()){
		$sth = Model::$_db->prepare($sql);
		if( is_array($params) && !empty($params) ){
			foreach($params as $k=>&$v){
				$sth->bindParam($k, $v);
			}		
		}
		return $sth;
	}
	
	private function _where( $conditions=array() ){
		if(is_array($conditions) && !empty($conditions)){
			$join = array();
			foreach( $conditions as $key => $condition ){
				$condition = $this->escape($condition);
				$join[] = "{$key} = {$condition}";
			}
			return " WHERE ".join(" AND ",$join);
		}
		return ' ';
	}
}