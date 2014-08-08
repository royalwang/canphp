<?php
namespace canphp\core;
use canphp\core\cpObject;
use canphp\core\cpConfig;
class cpController extends cpObject{
	protected $layout = NULL; //布局视图
	
	protected function init(){}
	
	public function __construct(){
		$this->init();
	}
	
	//模板赋值
	protected function assign($name, $value){
		$this->__data[$name] = $value;
	}
	
	//模板显示
	protected function display($tpl = '', $return = false, $is_tpl = true ){
		$template = cpTemplate::getInstance( config('TPL') );
		if( $is_tpl ){
			$tpl = empty($tpl) ? CONTROLLER_NAME . '_'. ACTION_NAME : $tpl;
			if( $this->layout ){
				$this->__template_file = $tpl;
				$tpl = $this->layout;
			}
		}
		if( defined('APP_NAME') && defined('BASE_PATH') ){
			$template->config['TPL_TEMPLATE_PATH'] = BASE_PATH . 'apps/' . APP_NAME . '/view/';
		}
		$template->assign(get_object_vars($this));
		$template->assign( $this->_data );
		return $template->display($tpl, $return, $is_tpl);
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
			$go_url = 'history.go(-1);';
		}else{
			$go_url = "window.location.href = '{$url}'";
		}
		echo "<script>$alert_msg $go_url</script>";
		exit;
	}
	
	protected function arg($name, $default = null, $callback = null) {
		if( !isset($_REQUEST[$name]) ) return $default;
		$arg = $_REQUEST[$name];
		if($callback) $arg = $callback($arg);
		return $arg;
	}
}