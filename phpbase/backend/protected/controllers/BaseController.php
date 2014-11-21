<?php
class BaseController extends Controller
{
	//protected $manager_class = 'ManagerBase';
	protected $manager_class = 'Manager';
	private $_menus = array(
		'ADMIN' => array(
			
		),
		
		'DEFAULT' => array(
			'系统管理' => array(
				'用户管理' => 'default/manager',
			),
		),
	);
	
	private $_public_pages = array(
		'default/index',
		'default/logout',
	);
	private $_manager_object = null;
	
	public function __construct() {
		// 不存在allows列表中的都需要检查权限
		if(!in_array(CONTROLLER_NAME.'/'.ACTION_NAME, $this->_public_pages)){
			$this->_manager_object = obj($this->manager_class);
			if(!$this->_manager_object->check())$this->errorJumpOut();
			$this->udb_name = $this->_manager_object->udb_name;
		}
	}

	private function errorJumpOut() {
		header("HTTP/1.0 403");
		echo "<html><head><meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\"><script>function tips(){alert(\"登录过期，请重新登录！\");top.location.href=\"".url('default/logout')."\"}</script></head><body onload=\"tips()\"></body></html>";
		exit(0);
	}
	
	protected function tips($type, $title, $url = null, $msg = '<br>', $stop=1) {
		$this->title = $title;
		$this->url = (null == $url) ? "javascript:window.history.back();" : $url;
		$this->type = $type;
		$this->msg = $msg;
		$this->display('layout/tips.html');
		if($stop)exit(0);
	}
	

	
	protected function getMenus() {
		$node = $this->_manager_object->getMenuNode();
		return $this->_menus[$node];
	}
	
	protected function getUser() {
		return $this->_manager_object->getUser();
	}
	
	protected function setUser($data) {
		$this->_manager_object->setUser($data);
	}
}