<?php
//下载模块
class manualMod extends commonMod
{
	//下载首页
	public function _empty()
	{	
		$filename=empty($_GET['_action'])?'index':trim($_GET['_action']);
		if(isset($_GET['_action'])&&!empty($_GET['_action'])&&preg_match('/^[a-zA-Z]+$/',$_GET['_action']))
		{
			$filename=$_GET['_action'].'.html';
		}
		else
		{
			$filename='index.html';
		}
	
		$manual_menu=include('template/manual/manual_menu.php');
		$info['title']=$this->_getTitle($filename,$manual_menu);
		
		$file="template/manual/manual_file/".$filename;
		if(file_exists($file))
		{
			$info['manual_file']=file_get_contents($file);
		}
		else
		{
			$info['manual_file']="内容为空";
		}
		
		$this->assign('manual_menu',$manual_menu);
		$this->assign('info',$info);
		$this->display('manual/index');
	}
	
	private function _getTitle($filename,$manual_menu)
	{
		foreach($manual_menu as $list)
		{
			if(isset($list[$filename]))
			{
				return $list[$filename];
			}
		}
		return '';
	}

}
?>