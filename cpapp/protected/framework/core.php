<?php	
defined('ROOT_PATH') or define('ROOT_PATH', realpath('./').DIRECTORY_SEPARATOR);
defined('BASE_PATH') or define('BASE_PATH', realpath('./protected/').DIRECTORY_SEPARATOR);
defined('CP_PATH') or define('CP_PATH', dirname(__FILE__).DIRECTORY_SEPARATOR);

require(BASE_PATH . 'config/global.php');//加载全局配置	

defined('DEFAULT_APP') or define('DEFAULT_APP', 'main');
defined('DEFAULT_CONTROLLER') or define('DEFAULT_CONTROLLER', 'index');
defined('DEFAULT_ACTION') or define('DEFAULT_ACTION', 'index');



function config(){

}

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
		'canphp'=>array(realpath(CP_PATH.'../')),
		'apps'=>array(BASE_PATH),
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

function run(){

	defined('DEBUG') or define('DEBUG', config('DEBUG'));


	
	if ( DEBUG ) {
		ini_set("display_errors", 1);
		error_reporting( E_ALL ^ E_NOTICE );//除了notice提示，其他类型的错误都报告
	} else {
		ini_set("display_errors", 0);
		error_reporting(0);//把错误报告，全部屏蔽
	}
	
	urlRoute();//网址路由解析
	
	//加载app配置
	if( is_file(BASE_PATH . 'apps/' . APP_NAME. '/config.php') ){
		config( require(BASE_PATH . 'apps/' . APP_NAME. '/config.php') );
	}
	config('_APP_NAME', APP_NAME);
	require(CP_PATH . 'core/cpConfig.class.php');
	cpConfig::set('APP', config('APP'));
	
	try{
		defined('__ROOT__') or define('__ROOT__', config('URL_HTTP_HOST') . rtrim(dirname($_SERVER["SCRIPT_NAME"]), '\\/'));
		defined('__PUBLIC__') or define('__PUBLIC__', __ROOT__ . '/' . 'public');
		defined('__PUBLICAPP__') or define('__PUBLICAPP__', __ROOT__ . '/' . 'public/' . APP_NAME);
		
		spl_autoload_register( 'autoload' );
		
		$controller = "\\apps\\".APP_NAME."\\controller\\{$controller}Controller";
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
		cpError::show( $e->getMessage() );
	}
}

$obj = new \apps\main\controller\indexController;
$obj->actionIndex();