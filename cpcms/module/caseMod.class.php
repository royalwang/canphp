<?php
//案例模块
class caseMod extends commonMod
{
	//案例首页
	public function index()
	{	
		$url=__URL__.'/index-{page}.html';//分页基准网址
		$listRows=15;//每页显示的信息条数
		$page=new Page();		
		$cur_page=$page->getCurPage($url);
		$limit_start=($cur_page-1)*$listRows;
		$limit=$limit_start.','.$listRows;
		
		$condition=array();		
		
		$count=$this->table('case')->where($condition)->count();	//获取行数
		$list=$this->table('case')->where($condition)->order('id asc')->limit($limit)->select();

		$this->assign('list',$list);
		$this->assign('page',$page->show($url,$count,$listRows));

		$this->display();
	}

}
?>