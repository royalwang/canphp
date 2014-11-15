<?php
namespace apps\main\controller;
class IndexController extends \apps\base\controller\BaseController{
	
	public function actionIndex(){
		$Demo = new \apps\main\model\demo();
		echo $this->title = $Demo->getTitle();
		echo $this->hello = $Demo->getHello();
		//$this->display();
	}
}