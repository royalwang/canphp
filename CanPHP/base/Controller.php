<?php
namespace framework\base;
use framework\base\Config;
use framework\base\Template;
use framework\base\Cache;

class Controller{
	public $layout = NULL; //布局视图
	public $autoDisplay = true; //自动调用模板
	public $_data = array();
	
	public function __get($name){
		return isset($this->_data[$name]) ? $this->_data[$name] : NULL;
	}
	
	public function __set($name, $value){
		$this->_data[$name] = $value;
	}
	
	//模板赋值
	public function assign($name, $value){
		$this->_data[$name] = $value;
	}
	
	//模板显示
	public function display($tpl = '', $return = false, $isTpl = true ){
		$template = new Template( Config::get('TPL') );
		if( $isTpl ){
			$tpl = empty($tpl) ? APP_NAME . '/view/' . CONTROLLER_NAME . '_'. ACTION_NAME : $tpl;
			if( $this->layout ){
				$this->__template_file = $tpl;
				$tpl = $this->layout;
			}
		}

		$template->assign(get_object_vars($this));
		$template->assign( $this->_data );
		
		return $template->display($tpl, $return, $is_tpl);
	}
	
	//判断是否是数据提交	
	public function isPost(){
		return $_SERVER['REQUEST_METHOD'] == 'POST';
	}
	
	//直接跳转
	public function redirect( $url, $code=302) {
		header('location:' . $url, true, $code);
		exit;
	}
	
	//弹出信息
	public function alert($msg, $url = NULL, $charset='utf-8'){
		header("Content-type: text/html; charset={$charset}"); 
		$alert_msg="alert('$msg');";
		if( empty($url) ) {
			$go_url = 'history.go(-1);';
		}else{
			$go_url = "window.location.href = '{$url}'";
		}
		echo "<script>$alert_msg $go_url</script>";
		exit;
	}
	
	public function arg($name=null, $default = null, $callback = null) {
		static $args;
		if( !$args ){
			$args = array_merge((array)$_GET, (array)$_POST);
		}
		if( null==$name ) return $args;
		if( !isset($args[$name]) ) return $default;
		$arg = $args[$name];
		if($callback) $arg = $callback($arg);
		return $arg;
	}
}