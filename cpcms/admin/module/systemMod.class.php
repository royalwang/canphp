<?php
class systemMod extends commonMod
{
	//清空缓存
	public function clearCache()
	{	
		del_dir('../data/db_cache');
		del_dir('../data/tpl_cache');
		del_dir('../data/html_cache');
		$this->success('缓存已清空',__APP__.'/article/index.html');
	}

}
?>