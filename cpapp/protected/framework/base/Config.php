<?php
namespace framework\core;
class Config {		
	//应用配置
	static protected $config = array(
				//日志和错误调试配置
				'DEBUG' => true,	//是否开启调试模式，true开启，false关闭
				'LOG_ON' => false,//是否开启出错信息保存到文件，true开启，false不开启
				'LOG_PATH' => BASE_PATH . 'cache/log/', //日志目录
				'ERROR_URL' => '',//出错信息重定向页面，为空采用默认的出错页面，一般不需要修改
				'TIMEZONE' => 'PRC', //时区设置
				
				//网址配置
				'URL_REWRITE_ON' => false,//是否开启重写，true开启重写,false关闭重写	
				'URL_HTTP_HOST' => '', //设置网址域名				
				'URL_REWRITE_RULE' =>array(
				
				),
				
				//数据库配置
				'DB'=>array(
					'default'=>array(								
							'DB_TYPE' => 'mysql',//数据库类型，一般不需要修改
							'DB_HOST' => 'localhost',//数据库主机，一般不需要修改
							'DB_USER' => 'root',//数据库用户名
							'DB_PWD' => '',//数据库密码
							'DB_PORT' => 3306,//数据库端口，mysql默认是3306，一般不需要修改
							'DB_NAME' => 'cp',//数据库名
							'DB_CHARSET' => 'utf8',//数据库编码，一般不需要修改
							'DB_PREFIX' => 'cp_',//数据库前缀
							'DB_CACHE' => 'default',//数据缓存方式
							
							//数据库主从配置，cp2.0添加
							'DB_SLAVE' => array(),//数据库从机配置，cp2.0添加
							/* 数据库主从配置示例，可以配置多台从机，如果用户名、密码等跟主机相同，可不设置
							'DB_SLAVE' => array(
												array(
														'DB_HOST' => '127.0.0.1',
														'DB_USER' => 'root',
														'DB_PWD' => '',
													),
												array(
														'DB_HOST' => '127.0.0.2',
														'DB_USER' => 'root',
														'DB_PWD' => '',
													),
											),
							*/
							
						)
				
				),
				
				//模板配置
				'TPL'=>array(
					'TPL_PATH'=>BASE_PATH.'apps/',//模板目录，一般不需要修改
					'TPL_SUFFIX'=>'.html',//模板后缀，一般不需要修改
					'TPL_CACHE'=>'default',//模板缓存方式						
				),
				
				'CACHE'=>array(
					'default'=>array('CACHE_TYPE'=>'FileCache', 'CACHE_PATH'=>''),
				)
				
			);

		
		static public loadConfig($file, $key){
			$config = require($file);
			self::set($key, $config);
		}
		
		static public function get($key=NULL){
			if( empty($key) ) return self::config;
			$arr = explode('.', $key);
			switch( count($ret) ){
				case 1 : 
					if( isset(self::config[ $arr[0] ])) {
						return self::config[ $arr[0] ];
					}
					break;
				case 2 : 
					if( isset(self::config[ $arr[0] ][ $arr[1] ])) {
						return self::config[ $arr[0] ][ $arr[1] ];
					}
					break;
				case 3 : 
					if( isset(self::config[ $arr[0] ][ $arr[1] ][ $arr[2] ])) {
						return self::config[ $arr[0] ][ $arr[1] ][ $arr[2] ];
					}
					break;
				case 4 : 
					if( isset(self::config[ $arr[0] ][ $arr[1] ][ $arr[2] ][ $arr[3] ])) {
						return self::config[ $arr[0] ][ $arr[1] ][ $arr[2] ][ $arr[3] ];
					}
					break;						
				default: break;
			}
			return NULL;
		}
		
		static public function set($key, $value){
			$arr = explode('.', $key);
			switch( count($ret) ){
				case 1 : 
					self::config[ $arr[0] ] = $value;
					break;
				case 2 : 
					self::config[ $arr[0] ][ $arr[1] ] = $value;
					break;
				case 3 : 
					self::config[ $arr[0] ][ $arr[1] ][ $arr[2] ]; = $value;
					break;
				case 4 : 
					self::config[ $arr[0] ][ $arr[1] ][ $arr[2] ][ $arr[3] ]; = $value;
					break;					
				default: break;
			}		
		}
}