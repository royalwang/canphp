<?php
namespace apps\main\controller;
class IndexController{
	public function actionIndex(){
		$this->title = model('main')->getTitle();
		$this->hello = model('main')->getHello();
		$this->display();
	}
}