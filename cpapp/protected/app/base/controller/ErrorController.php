<?php
namespace app\base\controller;
class ErrorController extends \framework\base\Controller{

	public function error403($e=null){
		$this->error($e);
	}
	
	public function error404($e=null){
		$this->error($e);
	}
	
	public function error500($e=null){
		$this->error($e);
	}
	
	public function error($e=null){
		print_r($e);
	}
}