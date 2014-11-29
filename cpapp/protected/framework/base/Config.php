<?php
namespace framework\base;
class Config {		

	static protected $config = array(
				'DEBUG' => true,	
				'LOG_ON' => false,
				'LOG_PATH' => 'data/log/', 
				'TIMEZONE' => 'PRC', 
							
				'REWRITE_RULE' =>array(
					//'<app>/<c>/<a>'=>'<app>/<c>/<a>',
				),
				
				'DEFAULT_APP' => 'main',
				'DEFAULT_CONTROLLER' => 'Default',
				'DEFAULT_ACTION' => 'index',
				
				'DB'=>array(
					'default'=>array(								
							'DB_TYPE' => 'mysql',
							'DB_HOST' => 'localhost',
							'DB_USER' => 'root',
							'DB_PWD' => '',
							'DB_PORT' => 3306,
							'DB_NAME' => 'cp',
							'DB_CHARSET' => 'utf8',
							'DB_PREFIX' => 'cp_',
							'DB_CACHE' => 'default',						
							'DB_SLAVE' => array(),
							/* 
							'DB_SLAVE' => array(
												array(
														'DB_HOST' => '127.0.0.1',
													),
												array(
														'DB_HOST' => '127.0.0.2',
													),
											),
							*/							
						),				
				),
				
				'TPL'=>array(
					'TPL_PATH'=> '',
					'TPL_SUFFIX'=>'.html',
					'TPL_CACHE'=>'default',						
				),
				
				'CACHE'=>array(
					'default'=>array('CACHE_TYPE'=>'FileCache', 'CACHE_PATH'=>'data/cache/'),
				),				
			);

		static public function loadConfig($file){
			if( !file_exists($file) ){
				throw new \Exception("Config file '{$file}' not found", 500); 
			}
			$config = require($file);
			self::$config = array_merge(self::$config, (array)$config);
		}
		
		static public function get($key=NULL){
			if( empty($key) ) return self::$config;
			$arr = explode('.', $key);
			switch( count($arr) ){
				case 1 : 
					if( isset(self::$config[ $arr[0] ])) {
						return self::$config[ $arr[0] ];
					}
					break;
				case 2 : 
					if( isset(self::$config[ $arr[0] ][ $arr[1] ])) {
						return self::$config[ $arr[0] ][ $arr[1] ];
					}
					break;
				case 3 : 
					if( isset(self::$config[ $arr[0] ][ $arr[1] ][ $arr[2] ])) {
						return self::$config[ $arr[0] ][ $arr[1] ][ $arr[2] ];
					}
					break;						
				default: break;
			}
			return NULL;
		}
		
		static public function set($key, $value){
			$arr = explode('.', $key);
			switch( count($arr) ){
				case 1 : 
					self::$config[ $arr[0] ] = $value;
					break;
				case 2 : 
					self::$config[ $arr[0] ][ $arr[1] ] = $value;
					break;
				case 3 : 
					self::$config[ $arr[0] ][ $arr[1] ][ $arr[2] ] = $value;
					break;					
				default: break;
			}		
		}
}