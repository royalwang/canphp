<?php
class controller{
	protected $model = NULL; //数据库模型
	protected $layout = NULL; //布局视图
	private $_data = array();
	
	protected function init(){}
	
	public function __construct(){
		if( 1 != config('APP_STATE') ){
			$this->alert('该应用尚未开启!');
		}
		$this->model = model('base')->model;
		$this->init();
	}

	public function __get($name){
		return isset( $this->_data[$name] ) ? $this->_data[$name] : NULL;
	}

	public function __set($name, $value){
		$this->_data[$name] = $value;
	}
	
	//获取模板对象
	protected function view(){
		static $view = NULL;
		if( empty($view) ){
			$view = new cpTemplate( config('TPL') );
		}
		return $view;
	}
	
	//模板赋值
	protected function assign($name, $value){
		return $this->view()->assign($name, $value);
	}
	
	//模板显示
	protected function display($tpl = '', $return = false, $is_tpl = true ){
		if( $is_tpl ){
			$tpl = empty($tpl) ? CONTROLLER_NAME . '_'. ACTION_NAME : $tpl;
			if( $is_tpl && $this->layout ){
				$this->__template_file = $tpl;
				$tpl = $this->layout;
			}
		}
		$this->view()->config['TPL_TEMPLATE_PATH'] = BASE_PATH . 'apps/' . config('_APP_NAME') . '/view/';
		$this->view()->assign( $this->_data );
		return $this->view()->display($tpl, $return, $is_tpl);
	}
	
	//判断是否是数据提交	
	protected function isPost(){
		return $_SERVER['REQUEST_METHOD'] == 'POST';
	}
	
	//直接跳转
	protected function redirect( $url, $code=302) {
		header('location:' . $url, true, $code);
		exit;
	}
	
	//弹出信息
	protected function alert($msg, $url = NULL){
		header("Content-type: text/html; charset=utf-8"); 
		$alert_msg="alert('$msg');";
		if( empty($url) ) {
			$gourl = 'history.go(-1);';
		}else{
			$gourl = "window.location.href = '{$url}'";
		}
		echo "<script>$alert_msg $gourl</script>";
		exit;
	}
	
	//获取分页查询limit
	protected function pageLimit($url, $num = 10){
		$url = str_replace(urlencode('{page}'), '{page}', $url);
		$page = is_object($this->pager['obj']) ? $this->pager['obj'] : new Page();	
		$cur_page = $page->getCurPage($url);
		$limit_start = ($cur_page-1) * $num;
		$limit = $limit_start.','.$num;
		$this->pager = array('obj'=>$page, 'url'=>$url, 'num'=>$num, 'cur_page'=>$cur_page, 'limit'=>$limit);
		return $limit;
	}
	
	//分页结果显示
	protected function pageShow($count){
		return $this->pager['obj'] ->show($this->pager['url'], $count, $this->pager['num']);
	}
}