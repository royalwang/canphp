<?php
class Controller{

	public $layout = null;

	private $_v = null;
	private $_data = array();
	public $_auto_display = true;
	
	public function init(){}
	
	public function __construct(){
		$this->init();
	}

	public function __get($name){
		return $this->_data[$name];
	}

	public function __set($name, $value){
		$this->_data[$name] = $value;
	}

	public function err404(){
		header("HTTP/1.0 404 Not Found");
		exit;
	}
	
	public function smarty(){
		if(null == $this->_v){
			$this->_v = obj("Smarty", array(), "protected/framework/smarty/Smarty.class.php");
			$this->_v->template_dir    = BASE_DIR .'/protected/views';
			$this->_v->compile_dir     = BASE_DIR .'/protected/data/tmp';
			$this->_v->cache_dir       = BASE_DIR .'/protected/data/tmp';
			$this->_v->left_delimiter  = '<{';
			$this->_v->right_delimiter = '}>';
			$this->_v->auto_literal    = true;
			$this->_v->default_modifiers = array('escape:"html"');
			$this->_v->registerPlugin('function', 'url', '__template_url');
			$this->_v->registerPlugin('function', 'page', '__template_page');
		}
		return $this->_v;
	}
	
	public function display($tpl_name, $return = false){
		$this->smarty()->assign(get_object_vars($this));
		$this->smarty()->assign($this->_data);
		if( $this->layout ){
			$this->smarty()->assign('__template_file', $tpl_name);
			$tpl_name = $this->layout;
		}
		$this->_auto_display = false;
		if( $return )
			return $this->smarty()->fetch($tpl_name);
		else
			$this->smarty()->display($tpl_name);
	}
	
	protected function arg($name = null, $default = null, $callback_funcname = null) {
		return arg($name, $default, $callback_funcname);
	}
}