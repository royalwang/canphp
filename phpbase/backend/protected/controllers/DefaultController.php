<?php
class DefaultController extends BaseController
{
	
	
	// 登录界面
	public function actionIndex(){
		$this->display('layout/logon.html');
	}
	
	// 主界面，包括菜单
	public function actionMain(){
		$this->menus = $this->getMenus();
		$this->display('layout/main.html');
	}
	
	// 主界面，刚进去的
	public function actionPanel(){
		$this->display('layout/panel.html');
	}

	// 登出
	public function actionLogout(){
		@session_start();
		unset($_SESSION[$GLOBALS['app_id']]);
		echo '<html><head><script src="http://www.duowan.com/public/assets/sys/js/jquery.js"></script><script type="text/javascript" src="/udb.js"></script></head><body><script type="text/javascript">$(function(){Navbar.logout(\'/index.php\');});</script></body></html>';
		exit(0);
	}
	
	public function actionManager() {
		$user = $this->getUser();
		if(1 != $user['admin'])$this->tips('error', '您没有该权限！');
		$this->ajax = 0;
		$this->display('layout/manager.html');
	}
	
	public function actionManagerOp() {
		$user = $this->getUser();
		if(1 != $user['admin'])$this->tips('error', '您没有该权限！');
		
		$manager = obj('Manager');
		if('add' == $_GET['op']) {
			if(!isset($_GET['udb']))exit('参数错误！');
			$manager->add($_GET['udb']);
		}elseif('del' == $_GET['op']) {
			if(!isset($_GET['udb']))exit('参数错误！');
			$manager->del($_GET['udb']);
		}else{
			$this->udbs = $manager->all();
			$this->ajax = 1;
			$this->display('layout/manager.html');
		}
	}
}