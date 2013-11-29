<?php
//资讯管理
class articleMod extends commonMod
{
//资讯列表页面
	public function index()
	{				
		$listRows=20;//每页显示的信息条数
		
		$where="";
		$condition=array();
		$cat_id=intval($_GET[0]);
		$url=__URL__.'/index-page-{page}.html';
		
		//某分类下的文章
		if(!empty($cat_id))
		{			
			$condition['id']=$cat_id;
			$cur_cat=$this->model->table('category')->where($condition)->find();				
			$this->assign('cur_cat',$cur_cat);//当前分类
			unset($condition);
			$where='WHERE A.cat_id='.$cat_id;
			$condition['cat_id']=$cat_id;
			$url=__URL__.'/index-'.$cat_id.'-page-{page}.html';
		}
		$page=new Page();
		$cur_page=$page->getCurPage($url);
		$limit_start=($cur_page-1)*$listRows;
		$limit=$limit_start.','.$listRows;
		
		//获取行数
		$count=$this->model->table('article')->where($condition)->count();	
		
		//联合查询,通过分类id，查询出分类名称
		$sql="SELECT A.id,A.cat_id,A.title,A.views,A.create_time,A.create_uid,A.create_username,A.top,A.recommend,A.status,B.name as cat_name
  FROM {$this->model->pre}article A LEFT JOIN {pre}category B ON A.cat_id = B.id {$where} ORDER BY A.id DESC LIMIT {$limit}";
		//$this->model->pre,数据表前缀
		
		$list=$this->model->query($sql);//执行查询
		
		$this->assign('list',$list);
		$this->assign('page',$page->show($url,$count,$listRows));
		$this->display();
	}
	
	//资讯搜索
	public function search()
	{
		$keyword=in($_GET['keyword']);
		//如果关键词为空，跳转到资讯列表页面
		if(empty($keyword))
		{
			$this->redirect(__URL__);
		}
		
		$listRows=20;//每页显示的信息条数
		$cur_page=intval($_GET['page'])?intval($_GET['page']):1;//获取当前分页
		$limit_start=($cur_page-1)*$listRows;
		$limit=$limit_start.','.$listRows;
		$where="";
		$condition=array();

		$url=__URL__.'/search-keyword-'.urlencode($keyword).'.html';//分页基准网址
		
		$where='WHERE A.title like "%'.$keyword.'%"';
		$condition=' title like "%'.$keyword.'%"';
		
		$count=$this->model->table('article')->where($condition)->count();	
		
		//联合查询
		$sql="SELECT A.id,A.cat_id,A.title,A.views,A.create_time,A.create_uid,A.create_username,A.top,A.recommend,A.status,B.name as cat_name
  FROM {$this->model->pre}article A LEFT JOIN {$this->model->pre}category B ON A.cat_id = B.id {$where} ORDER BY A.id DESC LIMIT {$limit}";

		$list=$this->model->query($sql);
		
		$this->assign('keyword',$keyword);
		$this->assign('list',$list);
		$this->assign('page',$this->page($url,$count,$listRows));
		$this->display();
		
	}
	
//发布资讯
	public function add()
	{	
		if(empty($_POST['do']))
		{
			$cat=module('category')->getCat();//获取格式化后的分类树
			$info['cat_id']=intval($_GET[0]);//文章分类
			
			$this->assign('info',$info);
			$this->assign('cat',$cat);			
			$this->display();
			return;
		}
		//获取数据
		$data=array();
		$data['title']=in($_POST['title']);//文章标题
		$data['cat_id']=intval($_POST['cat_id']);//文章分类
		$data['keywords']=in($_POST['keywords']);//关键词
		$data['description']=in($_POST['description']);//摘要
		$data['content']=html_in($_POST['content']);//内容
		$data['status']=html_in($_POST['status']);//内容
		$data['create_time']=time();
		$data['create_uid']=$_SESSION['admin_uid'];
		$data['create_username']=$_SESSION['admin_username'];
		
		//验证数据
		if(empty($data['title']))
		{
			$this->error('标题不能为空');
		}
		if(empty($data['cat_id']))
		{
			$this->error('请选择文章分类');
		}
		if(empty($data['content']))
		{
			$this->error('内容不能为空');
		}
		//数据库操作		
		if($this->model->table('article')->data($data)->insert())
			$this->success('资讯发布成功');
		else
			$this->error('资讯发布失败');
	}
//编辑资讯
	public function edit()
	{
		//显示资讯编辑页面
		if(empty($_POST['do']))
		{
			$id=intval($_GET[0]);
			if(empty($id))
			{
				$this->error('参数传递错误');
			}
			$condition['id']=$id;
			$info=$this->model->table('article')->where($condition)->find();//获取当前文章信息
			if(empty($info))
			{
				$this->error('该分类不存在或者已被删除');
			}
			
			//调用category分类模块的getCat方法
			$cat=module('category')->getCat();//获取格式化后的分类树
			
			$this->assign('info',$info);
			$this->assign('cat',$cat);	
			$this->display();
			return;
		}
		//获取数据
		$data=array();
		$condition=array();
		$condition['id']=intval($_POST['id']);
		$data['title']=in($_POST['title']);//文章标题
		$data['cat_id']=intval($_POST['cat_id']);//文章分类
		$data['keywords']=in($_POST['keywords']);//关键词
		$data['description']=in($_POST['description']);//摘要
		$data['content']=html_in($_POST['content']);//内容
		$data['status']=html_in($_POST['status']);//内容
		$data['create_time']=time();
		$data['create_uid']=$_SESSION['admin_uid'];
		$data['create_username']=$_SESSION['admin_username'];
		
		//验证数据
		if(empty($data['title']))
		{
			$this->error('标题不能为空');
		}
		if(empty($data['cat_id']))
		{
			$this->error('请选择文章分类');
		}
		if(empty($data['content']))
		{
			$this->error('内容不能为空');
		}
		//数据库操作
		if($this->model->table('article')->data($data)->where($condition)->update())
			$this->success('修改成功');
		else
			$this->error('修改失败');
	}
//删除资讯
	public function del()
	{
		$id=intval($_GET[0]);
		if(empty($id))
		{
			$this->error('参数传递错误');
		}
		$condition['id']=$id;
		if($this->model->table('article')->where($condition)->delete())
			$this->success('删除成功');
		else
			$this->error('删除失败');
	}
	
//修改状态
	public function state()
	{
		if(empty($_GET[0])||empty($_GET[1])||(!isset($_GET[2])))
		{
			$this->error('参数传递错误');
		}
		if(in_array($_GET[0],array('top','recommend'))&&in_array($_GET[2],array(0,1)))
		{
			$field=$_GET[0];	
			$data[$field]=intval($_GET[2]);
			$condition['id']=intval($_GET[1]);
			if($this->model->table('article')->data($data)->where($condition)->update())
			{
				$this->success('修改成功');
			}
			else
			{
				$this->error('修改失败');
			}
		}
		else
		{
			$this->error('非法操作');
		}
	}
}
?>