<?php
namespace apps\main\model;
class Demo extends \apps\base\model\BaseModel{

	public function getTitle(){
		return '默认首页';
	}
	
	public function getHello(){
		return 'Hello, 欢迎使用CPAPP';
	}

}