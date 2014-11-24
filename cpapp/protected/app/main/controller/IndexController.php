<?php
namespace app\main\controller;
class IndexController extends \app\base\ontroller\BaseController{
	
	public function actionIndex(){
		$this->title = model('main')->getTitle();
		$this->hello = model('main')->getHello();
		$this->display();
	}
}