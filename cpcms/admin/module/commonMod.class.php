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
		//实例化数据库模型和模板	
		if(!isset(self::$global['config']))
		{
			global $config;
			session_start();//开启session		
			$this->config=self::$global['config']=$config;//配置
			$this->model=self::$global['model']=new cpModel($this->config);//实例化数据库模型类				
		    $this->tpl=self::$global['tpl']=new cpTemplate($this->config);//实例化模板类	
	
			//权限验证
			//登录页面地址
			$config['AUTH_LOGIN_URL']=__APP__.'/index/login.html';
			//不需要登录的模块或操作，比如登录页面和验证码显示页面
			$config['AUTH_LOGIN_NO']=array('index'=> array('login','verify'));
			//是否缓存权限信息，设置false,每次从数据库读取，开发时建议设置为false
			$config['AUTH_POWER_CACHE']=false;
			//自动创建数据库表，自动插入模块数据，发布时，可以去掉
			 Auth::getModule($this->model,$config);
			Auth::check($this->model,$config);//检查是否登录	
		}
		else
		{   //使用module()函数调用的时候生效
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
	//判断是否是post提交
	protected function isPost()
	{
		return $_SERVER['REQUEST_METHOD']=='POST';
	
	}
	protected function _upload($upload_dir)
    {
			$upload = new UploadFile();
			//设置上传文件大小
			$upload->maxSize=1024*1024*2;//最大2M
			//设置上传文件类型
			$upload->allowExts  = explode(',','jpg,gif,png,bmp');
		
		    // 使用对上传图片进行缩略图处理
   			$upload->thumb   =  true;
   			// 缩略图最大宽度
   			$upload->thumbMaxWidth=400;
    		// 缩略图最大高度
   			$upload->thumbMaxHeight=240;
    
			//设置附件上传目录
			$upload->savePath ='../upload/'.$upload_dir."/";
			$upload->saveRule = cp_uniqid;
	
			if(!$upload->upload())
			 {
				//捕获上传异常
				$this->error($upload->getErrorMsg());
			}
			else 
			{
				//取得成功上传的文件信息
				return $upload->getUploadFileInfo();
			}
	}
}
?>