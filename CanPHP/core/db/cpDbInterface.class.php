<?php
namespace canphp\core\db;

//db驱动类接口
interface cpDbInterface{
	//连接数据库
	public function __construct($dbConfig);
	
	//查询 返回二维数组
	public function select($table, $condition, $field, $order, $limit);
	
	//插入，返回插入id
	public function insert($table, $data);
	
	//更新，返回影响行数
	public function update($table, $condition, $data);
	
	//删除，返回影响行数
	public function count($table, $condition);

	//统计，返回行数
	public function delete($table, $condition);
	
	//查询sql，返回二维数组
	public function query($sql, array $params);
	
	//执行sql，返回影响行数
	public function execute($sql, array $params);
	
	//获取表字段
	public function getFields($table);
	
	//获取最后执行的sql语句，用于调试
	public function getSql();
}