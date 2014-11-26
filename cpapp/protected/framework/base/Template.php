<?php
namespace framework\base;

class Template {
	protected $config =array();
	protected $vars = array();
	protected $_replace = array();
	
	public function __construct( $config = array() ) {
		$this->config = array_merge(Config::get('TPL', (array)$config);
		$this->assign('__Template', $this);
		$this->_replace = array(
				'str' => array( 'search' => array(),
								'replace' => array()
							),
				'reg' => array( 'search' => array("/__[A-Z]+__/",
												"/{(\\$[a-zA-Z_]\w*(?:\[[\w\.\"\'\[\]\$]+\])*)}/i",	
												"/{include\s*file=\"(.*)\"}/i",
												),
								'replace' => array("<?php echo $0; ?>",
												 "<?php echo $1; ?>",
												 "<?php \$__Template->compile(\"$1\"); ?>",
												)					   
							)
		);
		$this->cache = new Cache($this->config['TPL_CACHE']);
	}
	
	public function assign($name, $value = '') {
		if( is_array($name) ){
			foreach($name as $k => $v){
				$this->vars[$k] = $v;
			}
		} else {
			$this->vars[$name] = $value;
		}
	}

	public function display($tpl = '', $return = false, $isTpl = true ) {
		if( $return ){
			if ( ob_get_level() ){
				ob_end_flush();
				flush(); 
			} 
			ob_start();
		}
		
		extract($this->vars, EXTR_OVERWRITE);
		eval('?>' . $this->compile( $tpl, $isTpl));
		
		if( $return ){
			$content = ob_get_contents();
			ob_end_clean();
			return $content;
		}
	}	
	
	public function addTags($tags = array(), $reg = false) {
		$flag = $reg ? 'reg' : 'str';
		foreach($tags as $k => $v) {
			$this->_replace[$flag]['search'][] = $k;
			$this->_replace[$flag]['replace'][] = $v;
		}
	}
	
	public function compile( $tpl, $isTpl = true ) {
		if( $isTpl ){
			$tplFile = $this->config['TPL_PATH'] . $tpl . $this->config['TPL_SUFFIX'];
			if ( !file_exists($tplFile) ) {
				throw new Exception("Template file '{$tplFile}' not found", 404);
			}

			$tplKey = md5(realpath($tplFile));				
		} else {
			$tplKey = md5($tpl);
		}
		
		$ret = $this->cache->get( $tplKey );
		if ( empty($ret['content']) || ($isTpl&&filemtime($tplFile)>($ret['compile_time'])) ) {
			$template = $isTpl ? file_get_contents( $tplFile ) : $tpl;
			$template = str_replace($this->_replace['str']['search'], $this->_replace['str']['replace'], $template);
			$template = preg_replace($this->_replace['reg']['search'], $this->_replace['reg']['replace'], $template);
			$this->cache->set( $tplKey, $template, 86400*365);
		}		
	
		return $template;
	}
}