<?php
namespace apps\main;
class indexController{
	public function index(){
		$this->title = model('main')->getTitle();
		$this->hello = model('main')->getHello();
		$this->display();
	}
}