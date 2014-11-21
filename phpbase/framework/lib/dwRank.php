<?php
/**
 *  点击和排行系统
 *
 * 使用方法
include('dwRank.php');
$tt = new dwRank('应用MONGODB名称');
 */
class dwRank
{
	private $mongo_obj = null;
    function __construct($table, $dbname=null){
		include_once('dwMongo.php');
		$this->mongo_obj = dwMongo("rank_data_".$table, $dbname);
    }
	// key是id，i是增加的量，counter是排序的字段，addtions是附加值
	function setIncr($key, $counter, $i,  $addtions = array())
	{
		$info = $this->mongo_obj->findOne(array('key'=>(string)$key));
		if(isset($info['key'])){
			return $this->mongo_obj->update(array('key'=>(string)$key), array('$inc' => array($counter => (int)$i)));
		}else{
			$newrows = array_merge($addtions, array('key'=>(string)$key,$counter=>(int)$i));
			return $this->mongo_obj->insert($newrows);
		}
	}
	
	function getRank($sort, $limit)
	{
		$this->mongo_obj->ensureIndex(array($sort => 1));
		$result = $this->mongo_obj->find()->sort(array($sort => -1))->limit($limit);
		return iterator_to_array($result);
	}
	
	function getOne($key)
	{
		return $this->mongo_obj->findOne(array('key'=>(string)$key));
	}
}
?>