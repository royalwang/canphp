<?php
class indexController extends baseController{
	public function index(){
		$this->title = model('default')->getTitle();
		$this->hello = model('default')->getHello();
		$this->display();
	}
}