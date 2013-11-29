<?php
class appmanageApi extends baseApi{
  public function getMenu(){
		return array(
					'sort'=>1,
					'title'=>'应用管理',					
					'list'=>array(
						'我的应用'=>url('appmanage/index/index'),
						'应用商城'=>'http://appstore.canphp.com/?domain='. $_SERVER['HTTP_HOST']. '&callback=' . urlencode( url('appmanage/index/onlineinstall') ),
					)
			);
	} 
}