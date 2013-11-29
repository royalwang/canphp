<?php
//模板管理
class tplMod extends commonMod
{
	public $tplPath="../template/";
	//列出模板文件
	public function index()
	{
		$list=$this->listFile($this->tplPath);//获取模板文件名
		
		$this->assign('list',$list);
		$this->display();
	}
	
	protected function listFile($dir)
	{
		static $result=array();
		if (!is_dir($dir))
		{
			return false;
		}
		$handle = opendir($dir);
		while (($file = readdir($handle)) !== false)
		{
			if ($file != "." && $file != "..")
			{
				$filename="$dir/$file";
				if(is_dir($filename))
				{
					$this->listFile($filename);
				}
				else 
			 	{
			 		$pathinfo=pathinfo($filename);
					if($pathinfo['extension']=='php')
					{
						$result[]=array('filename'=>str_replace($this->tplPath."/","", $filename),'filemtime'=>filemtime($filename));
					}
			 	}
			}
		}
		
		if (readdir($handle) == false)
		{
			closedir($handle);
		}
		return $result;
	}
	
	//编辑模板
	public function edit()
	{
		$filename=in($_GET['filename']);
		$filename=str_replace('..','',$filename);//避免文件包含漏洞
			
		if(empty($filename)||!file_exists($this->tplPath.$filename))
		{
			$this->error('该模板文件不存在');
		}	
		
		//显示资讯编辑页面
		if(empty($_POST['do']))
		{			

			
			$info['filename']=$filename;
			$info['content']=file_get_contents($this->tplPath.$filename);//读取模板
		
			$this->assign('info',$info);	
			$this->display();
		}
		else
		{
			$cache=new cpCache();
			$cache->set($filename,file_get_contents($this->tplPath.$filename),-1);//缓存编辑前的数据
			
			if(file_put_contents($this->tplPath.$filename,$_POST['content']))
				$this->success('修改成功');
			else
				$this->error('修改失败');
		}
	}

	public function restore()
	{
		$filename=in($_GET['filename']);
		$filename=str_replace('..','',$filename);//避免文件包含漏洞
			
		if(empty($filename)||!file_exists($this->tplPath.$filename))
		{
			$this->error('该模板文件不存在');
		}	
		
		$cache=new cpCache();
		
		$content=$cache->get($filename);
		if(empty($content))
		{
			$this->error('该模板文件不存在备份，不能恢复');
		}
	
		//写入文件
		if(file_put_contents($this->tplPath.$filename,$content))
			$this->success('恢复成功');
		else
			$this->error('恢复失败');
	}
}
?>