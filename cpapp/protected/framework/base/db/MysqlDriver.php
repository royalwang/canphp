<?php
namespace framework\base\db;

class MysqlDriver implements DbInterface{
	protected $config =array();
	protected $_writeLink = NULL; //主
	protected $_readLink = NULL; //从
	protected $sql = "";
	
	public function __construct( $config = array() ){
		$this->config = $config;
	}
		
	//执行sql查询	
	public function query($sql, array $params = array()) {
		foreach((array)$params as $k => $v){
			$sql = str_replace(':'.$k, $this->escape($v), $sql);
		}
		$this->sql = $sql;
		if( $query = mysql_query($sql, $this->_getReadLink()) )
			return $query;
		else
			$this->error('MySQL Query Error');
	}
	
	//执行sql命令
	public function execute($sql, array $params = array()) {
		foreach($params as $k => $v){
			$sql = str_replace(':'.$k, $this->escape($v), $sql);
		}
		$this->sql = $sql;
		if( $query = mysql_query($sql, $this->_getWriteLink()) )
			return $query;
		else
			$this->error('MySQL Query Error');
	}
	
	//从结果集中取得一行作为关联数组，或数字数组，或二者兼有 
	public function fetchArray($query, $result_type = MYSQL_ASSOC) {
		return $this->unEscape( mysql_fetch_array($query, $result_type) );
	}	
	
	//取得前一次 MySQL 操作所影响的记录行数
	public function affectedRows() {
		return mysql_affected_rows( $this->_getWriteLink() );
	}
	
	public function insert($table, $data){}
	//获取上一次插入的id
	public function lastId() {
		return ($id = mysql_insert_id( $this->_getWriteLink() )) >= 0 ? $id : mysql_result($this->execute("SELECT last_insert_id()"), 0);
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
	
	//数据过滤
	public function escape($value) {
		if( isset($this->_readLink) ) {
            $link = $this->_readLink;
        } elseif( isset($this->_writeLink) ) {
            $link = $this->_writeLink;
        } else {
            $link = $this->_getReadLink();
        }

		if( is_array($value) ) { 
		   return array_map(array($this, 'escape'), $value);
		} else {
		   if( get_magic_quotes_gpc() ) {
			   $value = stripslashes($value);
		   } 
			return	"'" . mysql_real_escape_string($value, $link) . "'";
		}
	}
	
	//数据过滤
	public function unEscape($value) {
		if (is_array($value)) {
			return array_map('stripslashes', $value);
		} else {
			return stripslashes($value);
		}
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
		
		$link =null;
		foreach($dbArr as $db) {
			if( $link = @mysql_connect($db['DB_HOST'] . ':' . $db['DB_PORT'], $db['DB_USER'], $db['DB_PWD']) ){
				break;
			}
		}
		
		if(!$link){
			throw new Exception('connect database error :'.mysql_error(), 500);
		}

		$version = mysql_get_server_info($link);
		if($version > '4.1') {
			mysql_query("SET character_set_connection = " . $db['DB_CHARSET'] . ", character_set_results = " . $db['DB_CHARSET'] . ", character_set_client = binary", $link);		
				
			if($version > '5.0.1') {
				mysql_query("SET sql_mode = ''", $link);
			}
		}		
        mysql_select_db($db['DB_NAME'], $link);
        return $link;
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
			@mysql_close($this->_writeLink);
		}
		if($this->_readLink) {
			@mysql_close($this->_readLink);
		}
	} 
}