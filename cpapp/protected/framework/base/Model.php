<?php
namespace framework\base;

class Model{
	protected $config =array();
    protected $options = array('field'=>'','where'=>'','order'=>'','limit'=>'','data'=>'');
	protected $database = 'default';	
	protected $table = ''; //表名		
	protected static $objArr = array();

	
    public function __construct( $database = 'default' ) {
		if( $database ){
			$this->database = $database;
		}
		$this->config = Config::get('DB.' . $this->database);
		if( empty($this->config) || !isset($this->config['DB_TYPE']) ) {
			throw new Exception($this->database.' cache config error', 500);
		}
    }
	
	public function getDb() {
		if( empty(self::$objArr[$this->database]) ){
			$dbDriver = 'db\\' . ucfirst( $this->config['CACHE_TYPE'] ).'Driver';
			self::$objArr[$this->database] = new $dbDriver( $this->config );
		}
		return self::$objArr[$this->database];
	}
	
	//设置表，$ignore_prefix为true的时候，不加上默认的表前缀
	public function table($table, $ignorePre = false) {
		$this->$table = $ignorePre ? $table : $this->config['DB_PREFIX'] . $table;
		return $this;
	}
	
    public function __call($method, $args) {
		$method = strtolower($method);
        if ( in_array($method, array('field','data','where','order','limit')) ) {
            $this->options[$method] = $args[0];	//接收数据
			return $this;	//返回对象，连贯查询
        } else{
			throw new Exception("{$method}方法在cpModel类中未定义");
		}
    }
	

    public function query($sql, $params = array(), $is_query = false) {
        $sql = trim($sql);
		if ( empty($sql) ) return array();
		$sql = str_replace('{pre}', $this->config['DB_PREFIX'], $sql);	//表前缀替换

		if ( $is_query ||  0=== stripos($sql, 'select') || 0=== stripos(trim($sql), 'show') ) {
			return $this->getDb()->query($this->sql, $params);				
		} else {
			return $this->getDb()->execute($this->sql, $params); //不是查询条件，直接执行
		}
    }
	
	public function count() {
		$condition = $this->options['where'];
		$this->options['where']= '';	

		return $this->getDb()->count($this->table, $condition);
	}
	
    public function find() {
		$this->options['limit'] = 1;	//限制只查询一条数据
		$data = $this->select();
		return isset($data[0]) ? $data[0] : array();
     }
	 

     public function select() {
		$condition = $this->options['where'];
		$this->options['where'] = '';
		
		$field = $this->options['field'];
		if( $field =='' ) $field  = '*'; 
		$this->options['field'] = '*';
		
		$order = $this->options['order'];
		$this->options['order'] = '';

		$limit = $this->options['limit'];
		$this->options['limit'] = '';
		
		return $this->getDb()->select($this->table, $condition, $field, $order, $limit);		
     }
	 	
    public function insert() {
		if( empty($this->options['data']) || !is_array($this->options['data']) ) {
			throw new Exception('待插入的数据不能为空');
		}
		
		$data = $this->options['data'];
		$this->options['data']= array();	
		
		return $this->getDb()->insert($this->table, $data);
    }
	
    public function update() {
		if( empty($this->options['where']) ) {
			throw new Exception('修改操作必须指定条件，防止误操作');
		}	
		if( empty($this->options['data']) || !is_array($this->options['data']) ) {
			throw new Exception('修改操作数据不能为空');
		}
		
		$condition = $this->options['where'];
		$this->options['where']= '';
		
		$data = $this->options['data'];
		$this->options['data']= array();	

		return $this->getDb()->update($this->table, $condition, $data);
    }
	
    public function delete() {
		if( empty($this->options['where']) ) {
			throw new Exception('删除操作必须指定条件，防止误操作!');
		}	
		
		$condition = $this->options['where'];
		$this->options['where']= array();	

		return $this->getDb()->delete($this->table, $condition);
    }

	public function getFields() {
		return $this->getDb()->getFields($this->table);
	}
	
    public function getSql() {
        return $this->getDb()->sql;
    }

	public function cache($expire=1800){
		 $cache = new Cache($this->config['DB_CACHE']);
		 return $cache;
	}
	
    public function clear() {
		 $cache = new cpCache($this);
		 return $cache;
    }
}