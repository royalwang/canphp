<?php
namespace canphp\core;
use canphp\core\cpConfig;
class cpTemplate {
	public $config =array(); //配置
	protected $vars = array();//存放变量信息
	protected $_replace = array();
	
	public function __construct($config = array()) {
		$this->config = array_merge(cpConfig::TPL, (array)$config);//参数配置	
		$this->assign('cpTemplate', $this);
		$this->_replace = array(
				'str' => array( 'search' => array(),
								'replace' => array()
							),
				'reg' => array( 'search' => array("/__[A-Z]+__/",	//替换常量
												"/{(\\$[a-zA-Z_]\w*(?:\[[\w\.\"\'\[\]\$]+\])*)}/i",	//替换变量
												"/{include\s*file=\"(.*)\"}/i",	//递归解析模板包含
												),
								'replace' => array("<?php echo $0; ?>",
												 "<?php echo $1; ?>",
												 "<?php \$cpTemplate->compile(\"$1\"); ?>",
												)					   
							)
		);
	}
	
	//模板赋值
	public function assign($name, $value = '') {
		if( is_array($name) ){
			foreach($name as $k => $v){
				$this->vars[$k] = $v;
			}
		} else {
			$this->vars[$name] = $value;
		}
	}

	//执行模板解析输出
	public function display($tpl = '', $return = false, $is_tpl = true ) {
		//如果没有设置模板，则调用当前模块的当前操作模板
		if ( $is_tpl &&  ($tpl == "") && (!empty($_GET['_module'])) && (!empty($_GET['_action'])) ) {
			$tpl = $_GET['_module'] . "/" . $_GET['_action'];
		}
		if( $return ){
			if ( ob_get_level() ){
				ob_end_flush();
				flush(); 
			} 
			ob_start();
		}
		extract($this->vars, EXTR_OVERWRITE);
		eval('?>' . $this->compile( $tpl, $is_tpl));//直接执行编译后的模板
		
		if( $return ){
			$content = ob_get_contents();
			ob_end_clean();
			return $content;
		}
	}	
	
	//自定义添加标签
	public function addTags($tags = array(), $reg = false) {
		$flag = $reg ? 'reg' : 'str';
		foreach($tags as $k => $v) {
			$this->_replace[$flag]['search'][] = $k;
			$this->_replace[$flag]['replace'][] = $v;
		}
	}
	
	//模板编译核心
	protected function compile( $tpl, $is_tpl = true ) {
		if( $is_tpl ){
			$tplFile = $this->config['TPL_TEMPLATE_PATH'] . $tpl . $this->config['TPL_TEMPLATE_SUFFIX'];
			if ( !file_exists($tplFile) ) {
				throw new Exception($tplFile . "模板文件不存在");
			}
			$tpl_key = md5( realpath($tplFile) );
			$cache = new cpCache($this->config, $this->config['TPL_CACHE_TYPE']);
			$data = $cache->get( $tpl_key );
			if ( !empty($data['content']) && filemtime($tplFile) < $data['create_time'] ) {
				return $data['content'];
			}
			$template = file_get_contents( $tplFile );
		} else {
			$template = $tpl;
		}
		
		//如果自定义模板标签解析函数tpl_parse_ext($template)存在，则执行
		if ( function_exists('tpl_parse_ext') ) {
			$template = tpl_parse_ext($template);
		}
		$template = str_replace($this->_replace['str']['search'], $this->_replace['str']['replace'], $template);
		$template = preg_replace($this->_replace['reg']['search'], $this->_replace['reg']['replace'], $template);
		
		if( isset($tpl_key) ){
			$data = array('content'=>$template, 'create_time'=>time());
			$cache->set($tpl_key, $data, 86400*365);
		}
		return $template;
	}
}