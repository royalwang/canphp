<?php
class indexMod extends commonMod
{
	//显示管理后台首页
	public function index()
	{	
			$this->assign('config',$this->config);	
			$this->display('index/index');
	}

	//登录页面
	public function login()
	{
		if(!$this->isPost())
		{
			
			$this->display('index/login');
			return;
		}

		//数据验证
		 $msg=Check::rule(array(
								array(check::must($_POST['username']),'请输入用户名'),
								array(check::must($_POST['password']),'请输入密码'),
								array(check::must($_POST['checkcode']),'请输入验证码'),  
								array(check::same($_POST['checkcode'],$_SESSION['verify']),'验证码错误，请重新输入'),
						   )); 
         //如果数据验证通不过，返回错误信息						   
		 if($msg!==true)
		 {                
			 $this->error($msg);
		 } 
		
		$username=in($_POST['username']);
		$password=md5($_POST['password']);
		//数据库操作
		if($this->_login($username,$password))
		{
			$this->redirect(__APP__);
		}
		else
		{
			$this->error('用户名或密码错误，请重新输入');
		}	
	}
//用户登录
	private function _login($username,$password)
	{
		$condition=array();
		$condition['username']=$username;
		$user_info=$this->model->table('admin')->where($condition)->find();
		//用户名密码正确且没有锁定
		if(($user_info['password']==$password)&&($user_info['lock']==0))
		{			
			//更新帐号信息
			$data=array();			
			$data['login_time']=time();
			$data['login_ip']=get_client_ip();
			$this->model->table('admin')->data($data)->where($condition)->update();
			
				//设置登录信息
			$_SESSION['admin_uid']=$user_info['id'];
			$_SESSION['admin_groupid']=$user_info['groupid'];
			$_SESSION['admin_username']=$user_info['username'];
			Auth::set($user_info['groupid']);//设置用户组，用来权限验证
			$_SESSION['__ROOT__']=__ROOT__;		
			return true;
		}	
		return false;
	}	
	
	//用户退出
	public function logout()
	{
		unset($_SESSION['admin_uid']);
		unset($_SESSION['admin_username']);
		unset($_SESSION['admin_groupid']);
		Auth::clear();//清除权限验证
		unset($_SESSION['__ROOT__']);
		$this->success('您已成功退出系统',__APP__);
	}
	//生成验证码
	public function verify()
	{
		function header2($str){
			echo $str;
		}
		header("Cache-Control:max-age=864000");
		header("Expires: " . gmdate('D, d M Y H:i:s T', time()+864000));
		header("Last-Modified: Wed, 20 Jun 2012 07:43:51 GMT");
		Image::buildImageVerify();
	}

	//欢迎页面
	public function welcome()
	{
		$this->display();
	}
}
?>