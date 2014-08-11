<?php
namespace canphp\core;
use canphp\core\cpConfig;

//模型类，加载了外部的数据库驱动类和缓存类
class cpModel{
	protected $config =array(); //配置
    protected $options = array('field'=>'','where'=>'','order'=>'','limit'=>'','data'=>''); //参数
	
	protected static $db = array(); //存储数据库实例数组
	protected $database = 'default'; //数据库名称	
	protected $table = ''; //表名	
	
    public function __construct($dbConfig = array(), $database='default') {
		$this->config = array_merge(cpConfig::$DB, (array)$dbConfig);	//参数配置	
		$this->database = $database;
    }
	
	//连接数据库
	public function getDb() {
		if( empty(self::$db[$this->database]) ){
			$dbDriver = 'cp' . ucfirst( $this->config['DB_TYPE'] );
			self::$db[$this->database] = new $dbDriver( $this->config );	//实例化数据库驱动类
		}
		return self::$db[$this->database];
	}
	
	//设置表，$ignore_prefix为true的时候，不加上默认的表前缀
	public function table($table, $ignorePre = false) {
		$this->$table = $ignorePre ? $table : $this->config['DB_PREFIX'] . $table;
		return $this;
	}
	
	 //回调方法，连贯操作的实现
    public function __call($method, $args) {
		$method = strtolower($method);
        if ( in_array($method, array('field','data','where','order','limit')) ) {
            $this->options[$method] = $args[0];	//接收数据
			return $this;	//返回对象，连贯查询
        } else{
			throw new Exception("{$method}方法在cpModel类中未定义");
		}
    }
	
	//执行原生sql语句，如果sql是查询语句，返回二维数组
    public function query($sql, $params = array(), $is_query = false) {
        $sql = trim($sql);
		if ( empty($sql) ) return array();
		$sql = str_replace('{pre}', $this->config['DB_PREFIX'], $sql);	//表前缀替换

		//判断当前的sql是否是查询语句
		if ( $is_query ||  0=== stripos($sql, 'select') || 0=== stripos(trim($sql), 'show') ) {
			return $this->getDb()->query($this->sql, $params);				
		} else {
			return $this->getDb()->execute($this->sql, $params); //不是查询条件，直接执行
		}
    }
	
	//统计行数
	public function count() {
		$condition = $this->options['where'];
		$this->options['where']= '';	

		return $this->getDb()->count($this->table, $condition);
	}
	
	//只查询一条信息，返回一维数组	
    public function find() {
		$this->options['limit'] = 1;	//限制只查询一条数据
		$data = $this->select();
		return isset($data[0]) ? $data[0] : array();
     }
	 
	//查询多条信息，返回数组
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
	 	
	//插入数据
    public function insert() {
		if( empty($this->options['data']) || !is_array($this->options['data']) ) {
			throw new Exception('待插入的数据不能为空');
		}
		
		$data = $this->options['data'];
		$this->options['data']= array();	
		
		return $this->getDb()->insert($this->table, $data);
    }
	
	//修改更新
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
	
	//删除
    public function delete() {
		if( empty($this->options['where']) ) {
			throw new Exception('删除操作必须指定条件，防止误操作!');
		}	
		
		$condition = $this->options['where'];
		$this->options['where']= array();	

		return $this->getDb()->delete($this->table, $condition);
    }

	//获取一张表的所有字段
	public function getFields() {
		return $this->getDb()->getFields($this->table);
	}
	
	//返回sql语句
    public function getSql() {
        return $this->getDb()->sql;
    }

	public function cache($expire=1800){
		 $cache = new cpCache($this);
		 return $cache;
	}
	
	//删除数据库缓存
    public function clear() {
		 $cache = new cpCache($this);
		 return $cache;
    }
}