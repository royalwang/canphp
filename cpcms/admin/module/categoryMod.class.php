<?php
//栏目管理
class categoryMod extends commonMod
{
	//获取分类栏目的模型
	public function getModules($name='')
	{
		 $models=array(
						'article'=>'文章管理',
						'page'=>'单页管理',
						'tag'=>'标签管理',
					);
					
		if(empty($name))
			return $models;
		else if(isset($models[$name]))
			return $models[$name];
		else 
			return array();
	}
	
	public function __construct()
	{
		parent::__construct();
	}
//分类首页
	public function index()
	{
		$this->assign('cat',$this->getCat());
		$this->display();
	}
	
//添加分类
	public function add()
	{	
		//显示添加分类页面
		if(empty($_POST['do']))
		{
			$pid=intval($_GET[0]);
			$this->assign('pid',$pid);
			$this->assign('cat',$this->getCat());//获取格式化后的分类树			
			$this->assign('modules',$this->getModules());//获取格式化后的分类树	
			
			$this->display();
			return;
		}
		//获取数据
		$data=array();
		$data['pid']=intval($_POST['pid']);//上一级分类id
		$data['name']=in($_POST['name']);//分类名次
		//$data['keywords']=in($_POST['keywords']);//关键词，目的分类列表页面SEO优化
		//$data['description']=in($_POST['description']);//关键词描述，目的分类列表页面SEO优化
		
		
		//验证数据
		if(empty($data['name']))
		{
			$this->error('栏目名称不能为空');
		}
		
		//数据库操作
		if($this->model->table('category')->data($data)->insert())
			$this->success('栏目添加成功',__URL__);
		else
			$this->error('栏目添加失败');
	}
	
//编辑分类
	public function edit()
	{
		//没有提交数据，显示编辑页面
		if(empty($_POST['do']))
		{
			$id=intval($_GET[0]);
			if(empty($id))
			{
				$this->error('参数传递错误');
			}		
			$condition['id']=$id;
			$info=$this->model->table('category')->where($condition)->find();//获取当前分类信息
			if(empty($info))
			{
				$this->error('该分类不存在或者已被删除');
			}
			$this->assign('info',$info);
			$this->assign('cat',$this->getCat());//获取格式化后的分类树
			$this->display();
			return;
		}
		
		$data=array();
		$condition=array();
		$data['id']=intval($_POST['id']);//当前分类id
		$data['pid']=intval($_POST['pid']);//上一级分类id
		$data['name']=in($_POST['name']);//分类名次
		//$data['keywords']=in($_POST['keywords']);//关键词
		//$data['description']=in($_POST['description']);//关键词描述
		//验证数据
		if(empty($data['name']))
		{
			$this->error('栏目名称不能为空');
		}

		if($data['pid']==$data['id'])
		{
			$this->error('不可以将当前栏目设置为上一级栏目');	
		}
		
		//不能将自己的上一级分类，移动到自己的子栏目中
		$cat=$this->getCat($data['id']);//获取$data['id']的所有下级栏目
		if(!empty($cat))
		{
			foreach($cat as $vo)
			{
				if($data['pid']==$vo['id'])
				{
					$this->error('不可以将上一级栏目移动到子栏目');
				}
			}
		}
		
		$condition['id']=$data['id'];
		//数据库操作
		if($this->model->table('category')->data($data)->where($condition)->update())
			$this->success('修改成功',__URL__);
		else
			$this->error('修改失败');
	}
	
//删除分类
	public function del()
	{
		$id=intval($_GET[0]);
		if(empty($id))
		{
			$this->error('参数传递错误');
		}
		$condition=array();
		//检测子栏目是否存在
		$condition['pid']=$id;
		if($this->model->table('category')->where($condition)->count())
		{
			$this->error('请先删除该栏目下面的子栏目');	
		}
		unset($condition);//将上一次查询条件清空
		$condition['id']=$id;
		if($this->model->table('category')->where($condition)->delete())
			$this->success('删除成功',__URL__);//删除成功后跳转到分类首页
		else
			$this->error('删除失败');
	}
	
	//获取分类树，$id，分类id,$id=0，获取所有分类结构树
	public function getCat($id=0)
	{
		require(CP_PATH.'lib/Category.class.php');//导入Category.class.php无限分类
		//查询分类信息
		$data=$this->model->field('id,pid,name')->table('category')->select();		
		//array('id','pid','name','cname'),字段映射，格式化后的分类名次问cname
		$cat=new Category(array('id','pid','name','cname'));//初始化无限分类
		
		return $cat->getTree($data,$id);//获取分类数据树结构
	}
}
?>