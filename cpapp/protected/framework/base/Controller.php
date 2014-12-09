<?php
namespace framework\base;
//父类控制器
class Controller{
	public $layout = NULL; //布局视图
	
	//模板赋值
	public function assign($name, $value=NULL){
		return $this->_getView()->assign( $name, $value);
	}
	
	//模板显示
	public function display($tpl = '', $return = false, $isTpl = true ){
		if( $isTpl ){
			if( empty($tpl) ){
				$tpl = 'app/'.APP_NAME . '/view/' . strtolower(CONTROLLER_NAME) . '_'. strtolower(ACTION_NAME);
			}
			if( $this->layout ){
				$this->__template_file = $tpl;
				$tpl = $this->layout;
			}
		}	
		$this->_getView()->assign( get_object_vars($this));
		return $this->_getView()->display($tpl, $return, $isTpl);
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
	
	public function arg($name=null, $default = null) {
		static $args;
		if( !$args ){
			$args = array_merge((array)$_GET, (array)$_POST);
		}
		if( null==$name ) return $args;
		if( !isset($args[$name]) ) return $default;
		$arg = $args[$name];
		if( is_array($arg) ){
			array_walk($arg, function(&$v, $k){$v = trim(htmlspecialchars($v, ENT_QUOTES, 'UTF-8'));} );
		}else{
			$arg = trim(htmlspecialchars($arg, ENT_QUOTES, 'UTF-8'));
		}
		return $arg;
	}
	
	//获取模板引擎实例
	protected function _getView(){
		static $view;		
		if( !isset($view) ){
			if( !Config::get('TPL.TPL_PATH') ) Config::set('TPL.TPL_PATH', BASE_PATH);
			$view = new Template( Config::get('TPL') );
		}		
		return $view;
	}
}