<?php
namespace framework\base\db;
//db������ӿ�
interface DbInterface{
	
	//���캯������������
	public function __construct($config);

	//��ѯsql�����ض�ά����
	public function query($sql, array $params);
	
	//ִ��sql������Ӱ������
	public function execute($sql, array $params);
	
	//��ѯ ���ض�ά����
	public function select($table, array $condition, $field, $order, $limit);
	
	//���룬���ز���id
	public function insert($table, array $data);
	
	//���£�����Ӱ������
	public function update($table, array $condition, array $data);
	
	//ɾ��������Ӱ������
	public function delete($table, array $condition);

	//ͳ�ƣ���������
	public function count($table, array $condition);	
	
	//��ȡ���ֶ�
	public function getFields($table);
	
	//��ȡ���ִ�е�sql��䣬���ڵ���
	public function getSql();
	
	//��ʼ����
	public function beginTransaction();
	
	//�ύ����
	public function commit();
	
	//�ع�����
	public function rollBack();
}