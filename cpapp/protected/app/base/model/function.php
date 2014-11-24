<?php
require(CP_PATH . 'lib/common.function.php');
require(CP_PATH . 'ext/template_ext.php');

//调试运行时间和占用内存
function debug($flag='system', $end = false){
	static $arr =array();
	if( !$end ){
		$arr[$flag] = microtime(true); 
	} else if( $end && isset($arr[$flag]) ) {
		echo  '<p>' . $flag . ': runtime:' . round( (microtime(true) - $arr[$flag]), 6)
			 . '	memory_usage:' . memory_get_usage()/1000 . 'KB</p>'; 
	}
}

//保存配置
function save_config($app, $new_config = array()){
	if( !is_file($app) ){
		$file = BASE_PATH . 'apps/' . $app. '/config.php';
	}else{
		$file = $app;
	}
	
	if( is_file($file) ) {
		$config = require($file);
		$config = array_merge($config, $new_config);
	}else{
		$config = $new_config;
	}
	$content = var_export($config, true);
	$content = str_replace("_PATH' => '" . addslashes(BASE_PATH), "_PATH' => BASE_PATH . '", $content);

	if( file_put_contents($file, "<?php \r\nreturn " . $content . ';' ) ) {
		return true;
	}
	return false;
}

function copy_dir($src, $dst, $del = false) {
	if ($del && file_exists($dst)){
		return del_dir($dst);
	}
	if (is_dir($src)) {
		@mkdir($dst, 0777, true);
		$files = scandir($src);
		foreach ($files as $file){
			if ($file != "." && $file != "..") copy_dir("$src/$file", "$dst/$file");
		}
	}
	else if (file_exists($src)) copy($src, $dst);
}