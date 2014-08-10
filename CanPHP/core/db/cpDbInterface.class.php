<?php
namespace canphp\core\db;

//db������ӿ�
interface cpDbInterface{
	//�������ݿ�
	public function __construct($dbConfig);
	
	//��ѯ ���ض�ά����
	public function select($table, $condition, $field, $order, $limit);
	
	//���룬���ز���id
	public function insert($table, $data);
	
	//���£�����Ӱ������
	public function update($table, $condition, $data);
	
	//ɾ��������Ӱ������
	public function count($table, $condition);

	//ͳ�ƣ���������
	public function delete($table, $condition);
	
	//��ѯsql�����ض�ά����
	public function query($sql, array $params);
	
	//ִ��sql������Ӱ������
	public function execute($sql, array $params);
	
	//��ȡ���ֶ�
	public function getFields($table);
	
	//��ȡ���ִ�е�sql��䣬���ڵ���
	public function getSql();
}