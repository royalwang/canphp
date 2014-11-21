<?php
date_default_timezone_set('PRC');
defined('BASE_DIR') or define('BASE_DIR','./');
$GLOBALS = require(BASE_DIR.'/protected/config.php');

defined('DEBUG') or define('DEBUG',false);
defined('REWRITE') or define('REWRITE',false);
define('BASE_URL', $_SERVER["SCRIPT_NAME"]);
defined('DEVELOPMENT') or define('DEVELOPMENT', "\\" == DIRECTORY_SEPARATOR );

if( isset($GLOBALS['xhprof']) && $GLOBALS['xhprof'] >0 ) {
	if ( function_exists('xhprof_enable') && mt_rand(1, $GLOBALS['xhprof']) == 1 ){
		xhprof_enable();
		xhprof_enable(XHPROF_FLAGS_CPU + XHPROF_FLAGS_MEMORY);
		define('_XHPROF_OPEN', true);
	} 
}

error_reporting(E_ALL & ~(E_STRICT|E_NOTICE));
if(DEBUG){
	ini_set("display_errors", "On");
}else{
	ini_set("display_errors", "Off");
	ini_set("log_errors", "On");
}

require(dirname(__FILE__).'/Controller.php');
if(isset($GLOBALS['mysql']) && !empty($GLOBALS['mysql']) ) require(dirname(__FILE__).'/Model.php');

$controller='default';
$action='index';

if(REWRITE){
	if( ($pos = strpos( $_SERVER['REQUEST_URI'], '?' )) !== false )
		parse_str( substr( $_SERVER['REQUEST_URI'], $pos + 1 ), $_GET );
	//$GLOBALS['rewrite']['<c>/<a>'] = '<c>/<a>';
	foreach($GLOBALS['rewrite'] as $rule => $mapper){
		if(0!==stripos($rule, 'http://'))
			$rule = 'http://'.$_SERVER['HTTP_HOST'].rtrim(dirname(BASE_URL), '/\\') .'/'.$rule;
		$rule = '/'.str_ireplace(array(
			'\\\\', 'http://', '/', '<', '>',  '.',
		), array(
			'', '', '\/', '(?<', '>\w+)', '\.',
		), $rule).'/i';
		if(preg_match($rule, 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'], $matchs)){
			foreach($matchs as $matchkey => $matchval){
				if('c' === $matchkey)
					$mapper = str_ireplace('<c>', $matchval, $mapper);
				elseif('a' === $matchkey)
					$mapper = str_ireplace('<a>', $matchval, $mapper);
				else
					if(!is_int($matchkey))$_GET[$matchkey] = $matchval;
			}
			$_REQUEST['r'] = $mapper;
			break;
		}
	}
}

$GLOBALS['request_args'] = array_merge($_GET, $_POST);

if(isset($_REQUEST['r'])){
	$route_arr=explode('/', $_REQUEST['r']);
	if(isset($route_arr[0]) && !empty($route_arr[0])) $controller=strtolower($route_arr[0]);
	if(isset($route_arr[1]) && !empty($route_arr[1])) $action=strtolower($route_arr[1]);
}

try {
	define('CONTROLLER_NAME', $controller);
	define('ACTION_NAME', $action);

	include BASE_DIR.'/protected/controllers/BaseController.php';

	$object=obj(ucfirst($controller).'Controller');
	$methodName='action'.ucfirst($action);

	if(!method_exists($object, $methodName)) throw new Exception($methodName.'方法不存在');

	$object->$methodName();
    if($object->_auto_display){
        $_tpl_name = BASE_DIR."protected/views/".CONTROLLER_NAME."_".ACTION_NAME.".html";
        if($object->smarty()->templateExists($_tpl_name))$object->display($_tpl_name);
    }
	
	if( defined('_XHPROF_OPEN') && _XHPROF_OPEN ){
		$xhprof_data = xhprof_disable();  
		$xhprof_data = serialize($xhprof_data);
		$source = empty($GLOBALS['app_id'])?$_SERVER['HTTP_HOST']:$GLOBALS['app_id'];
		$file_name = ini_get("xhprof.output_dir").'/'.uniqid().$source.'xhprof';
		file_put_contents($file_name, $xhprof_data);
	}

} catch (Exception $e) {
	if(DEBUG){
		dump($e);
	}else{
		error_log('Throws error: '.$e->getMessage().' in '.$e->getFile().' on line '.$e->getLine());
		obj('DefaultController')->err404();
	}
}


function obj($className, $args=array(), $filePath="", $forceInstance=false){
	static $objArray=array();
	static $fileArray=array();

	if (!preg_match("#^[a-z0-9_]+$#i",$className))
		throw new Exception('类名只能使用大小写字母、数字、下划线');

	if(isset($objArray[$className])&&($forceInstance == false)) return $objArray[$className];

	$file_exists=false;
	if(empty($filePath)){
		foreach(array('models','extensions','controllers') as $type){
			$file=BASE_DIR.'protected/'.$type.'/'.$className.'.php';
			if(file_exists($file)){
				$file_exists=true;
				break;
			}
		}

		$file2=dirname(__FILE__).'/lib/'.$className.'.php';
		if(($file_exists==false)&&file_exists($file2)){
			$file=$file2;
			$file_exists=true;
		}

	}else{
		$file=BASE_DIR.$filePath;
		if(file_exists($file)){
			$file_exists=true;
		}
	}

	if($file_exists==false) throw new Exception($className.'类文件不存在');

	if(!isset($fileArray[$file])){
		require_once($file);
		$fileArray[$file]=1;
	}

	if(!class_exists($className)) throw new Exception($className.'类不存在');

	if(empty($args)){
		$objArray[$className]=new $className();
	}else{
		$objArray[$className]=call_user_func_array(array(new ReflectionClass($className), 'newInstance'), $args);
	}

	return $objArray[$className];
}

function dump($var, $exit = false){
	$output = var_export($var, true);
	if(!DEBUG)return error_log(str_replace("\n", '', $output));
	$content = "<div align=left><pre>\n" .htmlspecialchars($output). "\n</pre></div>\n";
	echo "<html><head><meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">";
	echo "</head><body>{$content}</body></html>";
	if($exit) exit();
}

function url($route='', $param=array()){
	$params=empty($param)?'':'&'.http_build_query($param);
	$url = BASE_URL.(empty($route)?'':'?r='.$route).$params;

	if(REWRITE){
		static $urlArray=array();
		if(!isset($urlArray[$url])){
			foreach($GLOBALS['rewrite'] as $rule => $mapper){
				$mapper = '/'.str_ireplace(array('/', '<a>', '<c>'), array('\/', '(?<a>\w+)', '(?<c>\w+)'), $mapper).'/i';
				if(preg_match($mapper, $route, $matchs)){
					list($controller, $action) = explode('/', $route);
					$urlArray[$url] = str_ireplace(array('<a>', '<c>'), array($action, $controller), $rule);
					if(!empty($param)){
						$_args = array();
						foreach($param as $argkey => $arg){
							$count = 0;
							$urlArray[$url] = str_ireplace('<'.$argkey.'>', $arg, $urlArray[$url], $count);
							if(!$count)$_args[$argkey] = $arg;
						}
						$urlArray[$url] = preg_replace('/<\w+>/', '', $urlArray[$url]).
							(!empty($_args) ? '?'.http_build_query($_args) : '');
					}
					if(0!==stripos($urlArray[$url], 'http://'))
						$urlArray[$url] = 'http://'.$_SERVER['HTTP_HOST'].rtrim(dirname(BASE_URL), '/\\') .'/'.$urlArray[$url];

					//参数个数匹配则返回
					$rule = str_ireplace(array('<c>', '<a>'), '', $rule);
					if( count($param) == preg_match_all('/<\w+>/is', $rule, $_match)){
						return $urlArray[$url];
					}

				}
			}
			return isset($urlArray[$url]) ? $urlArray[$url] : $url;
		}
		return $urlArray[$url];
	}
	return $url;
}

function arg($name = null, $default = null, $callback_funcname = null) {
	if($name){
		if(!isset($GLOBALS['request_args'][$name]))return $default;
		$arg = $GLOBALS['request_args'][$name];
	}else{
		$arg = $GLOBALS['request_args'];
	}
	if($callback_funcname)array_walk_recursive($arg, $callback_funcname);
	return $arg;
}

function __template_url($params){
	$route = $params['r'];
	unset($params['r']);
	return url($route, $params);
}

function __template_page($params) {
	if( empty($params['page']) ) return '';

	$page = $params['page'];
	$p = empty($params['_p']) ? 'p' : $params['_p'];
	$route = $params['r'];
	unset($params['page'], $params['_p'], $params['r']);

	$page_str='';
	if($page['current_page']>1) {
		$params[$p] = $page['prev_page'];
		$page_str .= '<a href="' . url($route, $params) . '" class="prev" title="上一页">上一页</a>';
		unset($params[$p]);
	}
	
	foreach($page['all_pages'] as $value) {
		if($value>1) $params[$p]=$value;
		if( $value == $page['current_page'] ) {
			$current = 'title="已经是当前页" class="current" ';
		} else {
			$current = 'title="第'. $value .'页" ';
		}
		$page_str .= '<a href="' . url($route, $params) . '" ' . $current . '>' . $value . '</a>';
	}
	if($page['current_page']<$page['total_page']){
		$params[$p] = $page['next_page'];
		$page_str .= '<a href="' . url($route, $params) . '" class="next" title="下一页">下一页</a>';	
	}

	return $page_str;
}