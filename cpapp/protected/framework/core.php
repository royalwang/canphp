<?php	
defined('ROOT_PATH') or define('ROOT_PATH', realpath('./').DIRECTORY_SEPARATOR);
defined('BASE_PATH') or define('BASE_PATH', realpath('./protected').DIRECTORY_SEPARATOR);
defined('FRAMEWORK_PATH') or define('FRAMEWORK_PATH', dirname(__FILE__).DIRECTORY_SEPARATOR);
defined('ENV') or define('ENV', 'development');

Config.loadConfig( BASE_PATH . 'data/config/global.php' ); //加载全局配置	
Config.loadConfig( BASE_PATH . 'data/config/'. ENV .'.php' ); //加载当前环境配置

defined('DEFAULT_APP') or define('DEFAULT_APP', 'main');
defined('DEFAULT_CONTROLLER') or define('DEFAULT_CONTROLLER', 'index');
defined('DEFAULT_ACTION') or define('DEFAULT_ACTION', 'index');


function model($model, $app='', $forceInstance=false){
	static $model_obj = array();
	if( empty($app) ) $app = APP_NAME;
	
	$class = "\\apps\\{$app}\\model\\{$model}";
	if( isset($model_obj[$class]) && false==$forceInstance ){
		return $model_obj[$class];
	}
	if( !class_exists($class) ) {
		throw new Exception("Class '{$class}' not found'", 500);
	}	
	
	return $model_obj[$class] = new $class();
}
							
spl_autoload_register(function($class){
	$prefixes =array(
		'framework' => realpath(CP_PATH.'../')
		'app' => BASE_PATH,
	);

	$class = ltrim($class, '\\');
	if (false !== ($pos = strrpos($class, '\\')) ){
		$namespace = substr($class, 0, $pos);
		$className = substr($class, $pos + 1);
		
		foreach ($prefixes as $prefix => $dirs){
			if (0 !== strpos($namespace, $prefix)){
				continue;
			}

			foreach ($dirs as $baseDir){
				$fileBase = $baseDir.str_replace('\\', DIRECTORY_SEPARATOR, $namespace).
							DIRECTORY_SEPARATOR.$className;
				foreach(array('.php','.class.php') as $suffix){
					$file  =$fileBase.$suffix;
					if( file_exists($file) ) {
						require $file;
						return true;
					}
				}						
			}
		}           
	}
	return false;
});

use framework\base\Config;
use framework\base\Route;
function run(){
	try{
		spl_autoload_register( 'autoload' );
		Config.loadConfig(BASE_PATH.'data/config/global.php');
		
		if ( Config.get('DEBUG') ) {
			ini_set("display_errors", 1);
			error_reporting( E_ALL ^ E_NOTICE );//除了notice提示，其他类型的错误都报告
		} else {
			ini_set("display_errors", 0);
			error_reporting(0);//把错误报告，全部屏蔽
		}
		
		Route::parseUrl();//网址路由解析
			
		defined('__ROOT__') or define('__ROOT__', config('URL_HTTP_HOST') . rtrim(dirname($_SERVER["SCRIPT_NAME"]), '\\/'));
		defined('__PUBLIC__') or define('__PUBLIC__', __ROOT__ . '/' . 'public');
			
		$controller = "\\app\\".APP_NAME."\\controller\\{$controller}Controller";
		$action = ACTION_NAME;

		if( !class_exists($controller) ) {
			throw new Exception("Class '{$class}' not found", 404);
		}
		$obj = new $controller();
		
		if( !method_exists($obj, $action) ){
			throw new Exception("Action '{$controller}::{$action}()' not found", 404);
		}
		$obj ->$action();

	} catch( Exception $e){
		Error::show( $e->getMessage() );
	}
}

$obj = new \apps\main\controller\indexController;
$obj->actionIndex();