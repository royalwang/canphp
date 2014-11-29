<?php
namespace app\main\controller;
class IndexController extends \app\base\controller\BaseController{
	
	public function Index(){
		echo url('c/a');
		//$this->title = model('main')->getTitle();
		//$this->hello = model('main')->getHello();
		$this->display();
	}
}