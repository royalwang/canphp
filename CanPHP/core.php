<?php
defined('BASE_PATH') or define('BASE_PATH', dirname(__FILE__) . '/');
							
use canphp\core\cpObject;

spl_autoload_register(function($class){
	$class = ltrim($class, '\\');
	
	$namespaces =array('canphp'=>array(CP_PATH.'../'));
	if (false !== ($pos = strrpos($class, '\\')) ){
		$namespace = substr($class, 0, $pos);
		$className = substr($class, $pos + 1);
		
		foreach ($namespaces as $ns => $dirs){
			if (0 !== strpos($namespace, $ns)){
				continue;
			}

			foreach ($dirs as $dir){
				$file = $dir.DIRECTORY_SEPARATOR.
							 str_replace('\\', DIRECTORY_SEPARATOR, $namespace).
							 DIRECTORY_SEPARATOR.
							 $className.'.class.php';
				//echo $file;
				if (file_exists($file)){
					include($file);
				}
			}
		}           
	}
});

include'test.php';