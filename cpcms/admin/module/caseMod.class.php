<?php
//案例管理
class caseMod extends commonMod
{
	public function __construct()
	{
		parent::__construct();
	}
	//案例列表页面
	public function index()
	{	
		$url=__URL__.'/index-{page}.html';//分页基准网址
		$listRows=10;//每页显示的信息条数
		$page=new Page();		
		$cur_page=$page->getCurPage($url);
		$limit_start=($cur_page-1)*$listRows;
		$limit=$limit_start.','.$listRows;
		
		$condition=array();		
		
		$count=$this->table('case')->where($condition)->count();	//获取行数
		$list=$this->table('case')->where($condition)->order('id desc')->limit($limit)->select();

		$this->assign('list',$list);
		$this->assign('page',$page->show($url,$count,$listRows));

		$this->display();
	}
	

	//发布案例
	public function add()
	{	
		if(empty($_POST['do']))
		{		
			$this->display();
			return;
		}
		//获取数据
		$data=array();
		$data['name']=in($_POST['name']);//案例名称
		$data['intro']=in($_POST['intro']);//简介
		$data['url']=in($_POST['url']);//链接地址
		//数据验证
		 $msg=Check::rule(array(
								array(check::must($data['name']),'请填写案例名称'),
								array(check::must($data['intro']),'请填写案例简介'),
								array($_FILES['image']['error']==0,'请上传案例图片'),
						   )); 
         //如果数据验证通不过，返回错误信息						   
		 if($msg!==true)
		 {                
			 $this->error($msg);
		 } 

		$upload=$this->_upload('case');
		$data['image']=$upload[0]['savename'];
	
		//数据库操作		
		$this->table('case')->data($data)->insert();
		$this->success('发布成功');

	}
	//修改案例
	public function edit()
	{
		//显示案例编辑页面
		if(empty($_POST['do']))
		{
			$id=intval($_GET[0]);
			if(empty($id))
			{
				$this->error('参数传递错误');
			}
			$condition['id']=$id;
			$info=$this->table('case')->where($condition)->find();//获取当前文章信息
			if(empty($info))
			{
				$this->error('该信息不存在或者已被删除');
			}
			
			$this->assign('info',$info);
			$this->display();
			return;
		}
		//获取数据
		$data=array();
		$condition=array();
		$condition['id']=intval($_POST['id']);
		$data['name']=in($_POST['name']);//案例名称
		$data['intro']=in($_POST['intro']);//简介
		$data['url']=in($_POST['url']);//链接地址
		
		//数据验证
		 $msg=Check::rule(array(
								array(check::must($data['name']),'请输入案例名称'),
								array(check::must($data['intro']),'请填写案例简介'),
						   )); 
         //如果数据验证通不过，返回错误信息						   
		 if($msg!==true)
		 {                
			 $this->error($msg);
		 } 

		 //只有上传图片之后，才会更新图片字段
		if($_FILES['image']['error']==0)
		{
			$upload=$this->_upload('case');
			$data['image']=$upload[0]['savename'];
		}
		
		//数据库操作
		$this->table('case')->data($data)->where($condition)->update();
		$this->success('修改成功');

	}
	
	//删除案例
	public function del()
	{
		$id=intval($_GET[0]);
		if(empty($id))
		{
			$this->error('参数传递错误');
		}
		$condition['id']=$id;
		$info=$this->table('case')->where($condition)->find();//获取当前文章信息
		if(empty($info))
		{
			$this->error('该信息不存在或者已被删除');
		}

		if($this->table('case')->where($condition)->delete())
		{	
			$image='../upload/case/'.$info['image'];
			is_file($image)?unlink($image):'';//图片文件存在，同时删除案例图片
			$this->success('删除成功');
		}
		else
		{
			$this->error('删除失败');
		}
	}
	
}
?>