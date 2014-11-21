<?php
/**
 *  任务抛出程序
 *
 * 使用方法：
$url = 'http://tu.duowan.com/admin/default/compute.html';
include('dwTask.php');
$task = new dwTask;
$task->set($url);
$task->send();
 */
class dwTask extends dwaeObject
{
    var $tasks = array();

    function set($url){
        $this->tasks[] = $url;
    }

    function send(){
		foreach($this->tasks as $url){
			$url = DW_API_TASK.'?task='.urlencode($url);
			$this->fetchURL($url);
		}
		$this->tasks = array();
    }
}
?>