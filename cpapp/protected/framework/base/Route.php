<?php
namespace framework\base;

class Route {			
	static protected $rewriteRule = array();
	
	static public function parseUrl( $rewriteRule ){
		self::$rewriteRule = $rewriteRule;
		if( !empty(self::$rewriteRule ) ) {
			if( ($pos = strpos( $_SERVER['REQUEST_URI'], '?' )) !== false ){
				parse_str( substr( $_SERVER['REQUEST_URI'], $pos + 1 ), $_GET );
			}
			foreach(self::$rewriteRule as $rule =>

	$mapper){
				$rule = ltrim($rule, "./\\");
				if( false === stripos($rule, 'http://')){
					$rule = $_SERVER['HTTP_HOST'].rtrim(dirname($_SERVER["SCRIPT_NAME"]), '/\\') . '/' . $rule;
				}
				$rule = '/'.str_ireplace(array('\\\\', 'http://', '-', '/', '<', '>',  '.'), array('', '', '\-', '\/', '(?<', ">[a-z0-9_%]+)", '\.'), $rule).'/i';
				if( preg_match($rule, $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'], $matches) ){
					foreach($matches as $matchkey => $matchval){
						if(('app' === $matchkey)){
							$mapper = str_ireplace('<app>', $matchval, $mapper);
						}else if('c' === $matchkey){
							$mapper = str_ireplace('<c>', $matchval, $mapper);
						}else if('a' === $matchkey){
							$mapper = str_ireplace('<a>', $matchval, $mapper);
						} else {
							if( !is_int($matchkey) ) $_GET[$matchkey] = $matchval;
						}
					}
					$_REQUEST['r'] = $mapper;
					break;
				}
			}
		}
		
		return isset($_REQUEST['r']) ? explode("/", $_REQUEST['r']) : array();
	}

	static public function url($route='index/index', $params=array()){
		if( count( explode('/', $route) ) < 3 )  $route = APP_NAME . '/' . $route;
		$paramStr = empty($params) ? '' : '&' . http_build_query($params);
		$url = $_SERVER["SCRIPT_NAME"] . '?r=' . $route . $paramStr;
			
		if( !empty(self::$rewriteRule) ){
			static $urlArray = array();
			if( !isset($urlArray[$url]) ){
				foreach(self::$rewriteRule as $rule => $mapper){
					$mapper = '/'.str_ireplace(array('/', '<app>', '<c>', '<a>'), array('\/', '(?<app>\w+)', '(?<c>\w+)', '(?<a>\w+)'), $mapper).'/i';
					if( preg_match($mapper, $route, $matches) ){
						list($app, $controller, $action) = explode('/', $route);
						$urlArray[$url] = str_ireplace(array('<app>', '<c>', '<a>'), array($app, $controller, $action), $rule);
						if( !empty($params) ){
							$_args = array();
							foreach($params as $argkey => $arg){
								$count = 0;
								$urlArray[$url] = str_ireplace('<'.$argkey.'>', $arg, $urlArray[$url], $count);
								if(!$count) $_args[$argkey] = $arg;
							}
							//处理多出来的参数
							if( !empty($_args) ){
								$urlArray[$url] = preg_replace('/<\w+>/', '', $urlArray[$url]). '?' . http_build_query($_args);
							}	
						}
						//自动加上域名
						if(false === stripos($urlArray[$url], 'http://')){
							$urlArray[$url] = 'http://'.$_SERVER['HTTP_HOST'].rtrim(dirname($_SERVER["SCRIPT_NAME"]), "./\\") .'/'.ltrim($urlArray[$url], "./\\");
						}
						
						//参数个数匹配则返回
						$rule = str_ireplace(array('<app>', '<c>', '<a>'), '', $rule);
						if( count($params) == preg_match_all('/<\w+>/is', $rule, $_match)){
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
}