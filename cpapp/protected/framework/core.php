<?php	
defined('ROOT_PATH') or define('ROOT_PATH', realpath('./').DIRECTORY_SEPARATOR);
defined('BASE_PATH') or define('BASE_PATH', realpath('./protected').DIRECTORY_SEPARATOR);
defined('CONFIG_PATH') or define('CONFIG_PATH', BASE_PATH.'data/config/');
defined('ROOT_URL') or define('ROOT_URL',  rtrim(dirname($_SERVER["SCRIPT_NAME"]), '\\/'));
defined('PUBLIC_URL') or define('PUBLIC_URL', ROOT_URL . '/' . 'public');
defined('ENV') or define('ENV', 'development');

use framework\base\Config;
use framework\base\Route;

//类自动加载
function autoload($class){
	$prefixes =array(
		'framework' => BASE_PATH,
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
		Config::loadConfig( CONFIG_PATH . ENV .'.php' ); //加载当前环境配置
		
		//错误信息显示控制
		if ( Config::get('DEBUG') ) {
			ini_set("display_errors", 1);
			error_reporting( E_ALL ^ E_NOTICE );//除了notice提示，其他类型的错误都报告
		} else {
			ini_set("display_errors", 0);
			error_reporting(0);//把错误报告，全部屏蔽
		}
		
		//路由解析
		Route::parseUrl( Config::get('REWRITE_RULE') );//网址路由解析
		echo APP_NAME;
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
		if( in_array($e->getCode(), array(403, 404, 500) )){
			$action = 'error'.$e->getCode();
		}else{
			$action = 'error';
		}
		$obj = new \app\base\controller\ErrorController();
		$obj ->$action($e);
	}
}

run();