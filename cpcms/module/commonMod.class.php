<?php
//公共类
class commonMod
{
	public $model;//数据库模型对象
	public $tpl;//模板对象
	public $config;//全局配置
	static $global;//静态变量，用来实现单例模式
	public function __construct()
	{	
		//如果还没有安装，则跳转到安装页面
		if(!file_exists('data/install.lock'))
		{
			$this->redirect(__ROOT__.'/install/');
		}
		
		//实例化数据库模型和模板	
		if(!isset(self::$global['config']))
		{
			global $config;
					
			$this->config=self::$global['config']=$config;//配置
			$this->model=self::$global['model']=new cpModel($this->config);//实例化数据库模型类				
		    $this->tpl=self::$global['tpl']=new cpTemplate($this->config);//实例化模板类		
		}
		else
		{
			$this->config=self::$global['config'];//配置
			$this->model=self::$global['model'];//数据库模型对象
			$this->tpl=self::$global['tpl'];//模板类对象
		}

	}
	
	//模板变量解析
	protected function assign($name, $value)
	{
		return $this->tpl->assign($name, $value);
	}
	
	//模板输出
	protected  function display($tpl='')
	{	
		return $this->tpl->display($tpl);	
	}
	
	//设置表名
	protected function table($table,$ignore_prefix=false)
	{
		return $this->model->table($table,$ignore_prefix);
	}
		
	//直接跳转
	protected   function redirect($url,$code=301)
	{
		header('location:'.$url,false,$code);
		exit;
	}
	
	//操作成功之后跳转,默认三秒钟跳转
	protected   function success($msg,$url=NULL,$waitSecond=3)
	{
		if($url==NULL)
			$url=__URL__;
		$this->assign('message',$msg);
		$this->assign('url',$url);
		$this->assign('waitSecond',$waitSecond);
		$this->display('success');
		exit;
	}
	
	//出错之后跳转，后退到前一页
	protected   function error($msg)
	{
		header("Content-type: text/html; charset=utf-8"); 
		$msg="alert('$msg');";
		echo "<script>$msg history.go(-1);</script>";
		exit;
	}
}
?>