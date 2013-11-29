<?php
class demoApi extends baseApi{
	
	public function getMenu(){
		return array(
					'sort'=>1,
					'title'=>'示例页面',					
					'list'=>array(
						'添加页面'=>url('demo/index/add'),
						'列表页面'=>url('demo/index/index'),
						'配置页面'=>url('demo/index/config'),
					)
			);
	}
}