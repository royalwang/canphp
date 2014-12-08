<?php
namespace framework\base;
//父类控制器
class Controller{
	public $layout = NULL; //布局视图
	public $autoDisplay = true; //自动调用模板
	public $_data = array();
	

	//模板赋值
	public function assign($name, $value){
		$this->_data[$name] = $value;
	}
	
	//模板显示
	public function display($tpl = '', $return = false, $isTpl = true ){
		if( !Config::get('TPL.TPL_PATH') ) Config::set('TPL.TPL_PATH', BASE_PATH);
		$template = new Template( Config::get('TPL') );
		if( $isTpl ){
			if( empty($tpl) ){
				$tpl = 'app/'.APP_NAME . '/view/' . strtolower(CONTROLLER_NAME) . '_'. strtolower(ACTION_NAME);
			}
			if( $this->layout ){
				$this->__template_file = $tpl;
				$tpl = $this->layout;
			}
		}

		$template->assign(get_object_vars($this));
		$template->assign( $this->_data );
		return $template->display($tpl, $return, $isTpl);
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