<?php	
if( !defined('ROOT_PATH') ) define('ROOT_PATH', realpath('./').DIRECTORY_SEPARATOR);
if( !defined('BASE_PATH') ) define('BASE_PATH', realpath('./protected').DIRECTORY_SEPARATOR);
if( !defined('CONFIG_PATH') ) define('CONFIG_PATH', BASE_PATH.'data/config/');
if( !defined('ROOT_URL') ) define('ROOT_URL',  rtrim(dirname($_SERVER["SCRIPT_NAME"]), '\\/').'/');
if( !defined('PUBLIC_URL') ) define('PUBLIC_URL', ROOT_URL . 'public/');

use framework\base\Config;
use framework\base\Route;
use app\base\controller\ErrorController;
use app\base\model\Route as RouteExt;

//类自动加载
function autoload($class){
	$prefixes =array(
		'framework' => realpath(__DIR__.'/../').DIRECTORY_SEPARATOR,
		'app' => BASE_PATH,
	);

	$class = ltrim($class, '\\');
	if (false !== ($pos = strrpos($class, '\\')) ){
		$namespace = substr($class, 0, $pos);
		$className = substr($class, $pos + 1);
		
		foreach ($prefixes as $prefix => $baseDir){
			if (0 !== strpos($namespace, $prefix)){
				continue;
			}
			$fileBase = $baseDir.str_replace('\\', DIRECTORY_SEPARATOR, $namespace).DIRECTORY_SEPARATOR.$className;
			foreach(array('.php', '.class.php') as $suffix){
				$file  =$fileBase.$suffix;
				if( file_exists($file) ) {
					require $file;
					return true;
				}
			}							
		}           
	}
	return false;
}

//根据路由规则，生成url地址
function url($route='index/index', $params=array()){
	return Route::url($route, $params);
}

function config($key=NULL, $value=NULL){
	if( func_num_args() <= 1 ){
		return Config::get($key);
	}else{
		return Config::set($key);
	}
}

//调用模型
function model($model, $app='', $forceInstance=false){
	static $objArr = array();
	if( empty($app) ) $app = APP_NAME;
	
	$class = "\\app\\{$app}\\model\\{$model}";
	if( isset($objArr[$class]) && false==$forceInstance ){
		return $objArr[$class];
	}
	if( !class_exists($class) ) {
		throw new Exception("Model '{$class}' not found'", 500);
	}		
	return $objArr[$class] = new $class();
}

function run(){
	try{
		//注册类自动加载
		spl_autoload_register('autoload');
		
		//加载配置
		Config::loadConfig( CONFIG_PATH . 'global.php' ); //加载全局配置	
		Config::loadConfig( CONFIG_PATH . Config::get('ENV') . '.php' ); //加载当前环境配置
		
		//设置时区
		date_default_timezone_set( Config::get('TIMEZONE') );
		
		//错误信息显示控制
		if ( Config::get('DEBUG') ) {
			ini_set("display_errors", 1);
			error_reporting( E_ALL ^ E_NOTICE );//除了notice提示，其他类型的错误都报告
		} else {
			ini_set("display_errors", 0);
			error_reporting(0);//把错误报告，全部屏蔽
		}
		
		//路由解析
		//路由扩展
		if( class_exists('RouteExt') ){
			RouteExt::parseUrl( Config::get('REWRITE_RULE') );
		}
		//调用内置路由
		if( !defined('APP_NAME') || !defined('CONTROLLER_NAME') || !defined('ACTION_NAME')){
			Route::parseUrl( Config::get('REWRITE_RULE') );//网址路由解析
		}
		
		//执行指定的控制器操作
		$controller = '\app\\'. APP_NAME .'\controller\\'. CONTROLLER_NAME .'Controller';
		$action = ACTION_NAME;

		if( !class_exists($controller) ) {
			throw new Exception("Controller '{$controller}' not found", 404);
		}
		$obj = new $controller();
		if( !method_exists($obj, $action) ){
			throw new Exception("Action '{$controller}::{$action}()' not found", 404);
		}
		$obj ->$action();
		
	} catch( Exception $e ){
		//调用错误控制器，处理错误
		if( 404 == $e->getCode() ){
			$action = 'error404';
		}else{
			$action = 'error';
		}
		$obj = new ErrorController();
		$obj ->$action($e);
	}
}

run();