<?php
namespace canphp\core;
//CanPHP框架默认配置
class cpConfig{		
	//应用配置
	static public $APP = array(
				//日志和错误调试配置
				'DEBUG' => true,	//是否开启调试模式，true开启，false关闭
				'LOG_ON' => false,//是否开启出错信息保存到文件，true开启，false不开启
				'LOG_PATH' => './data/log/',//出错信息存放的目录，出错信息以天为单位存放，一般不需要修改
				'ERROR_URL' => '',//出错信息重定向页面，为空采用默认的出错页面，一般不需要修改
				
				//网址配置
				'URL_REWRITE_ON' => false,//是否开启重写，true开启重写,false关闭重写	
				'URL_MODULE_DEPR' => '/',//模块分隔符，一般不需要修改
				'URL_ACTION_DEPR' => '-',//操作分隔符，一般不需要修改
				'URL_PARAM_DEPR' => '-',//参数分隔符，一般不需要修改
				'URL_HTML_SUFFIX' => '.html',//伪静态后缀设置，例如 .html ，一般不需要修改
				'URL_HTTP_HOST' => '', //设置网址域名，cp2.0添加		
				
				//模块配置
				'MODULE_PATH' => './module/',//模块存放目录，一般不需要修改
				'MODULE_SUFFIX' => 'Mod.class.php',//模块后缀，一般不需要修改
				'MODULE_INIT' => 'init.php',//初始程序，一般不需要修改
				'MODULE_DEFAULT' => 'index',//默认模块，一般不需要修改
				'MODULE_EMPTY' => 'empty',//空模块	，一般不需要修改	
						
				//操作配置
				'ACTION_DEFAULT' => 'index',//默认操作，一般不需要修改
				'ACTION_EMPTY' => '_empty',//空操作，一般不需要修改

				//模型配置
				'MODEL_PATH' => './model/',//模型存放目录，一般不需要修改
				'MODEL_SUFFIX' => 'Model.class.php',//模型后缀，一般不需要修改
								
				//静态页面缓存
				'HTML_CACHE_ON' => false,//是否开启静态页面缓存，true开启.false关闭
				'HTML_CACHE_PATH' => './data/html_cache/',//静态页面缓存目录，一般不需要修改
				'HTML_CACHE_RULE' => array(
											'index'=>array('index'=>1000,),//缓存时间,单位：秒
										 ),//静态页面缓存规则 array('模块名'=>array('方法名'=>缓存时间,))
				
				'AUTOLOAD_DIR' => array(),	//自动加载扩展目录，cp2.0添加
				
				'TIMEZONE' => 'PRC', //时区设置，cp2.1添加
			);
		
		//数据库配置
		static public $DB  = array(								
				'DB_TYPE' => 'mysql',//数据库类型，一般不需要修改
				'DB_HOST' => 'localhost',//数据库主机，一般不需要修改
				'DB_USER' => 'root',//数据库用户名
				'DB_PWD' => '',//数据库密码
				'DB_PORT' => 3306,//数据库端口，mysql默认是3306，一般不需要修改
				'DB_NAME' => 'cp',//数据库名
				'DB_CHARSET' => 'utf8',//数据库编码，一般不需要修改
				'DB_PREFIX' => 'cp_',//数据库前缀
				
				'DB_CACHE_TYPE' => '',//缓存类型，为空不缓存，FileCache或Memcache或SaeMemcache，cp2.0添加				
				
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
				
			);
		
		//模板配置			
		static public $TPL = array(
				'TPL_TEMPLATE_PATH'=>'./template/',//模板目录，一般不需要修改
				'TPL_TEMPLATE_SUFFIX'=>'.html',//模板后缀，一般不需要修改
				'TPL_CACHE_TYPE'=>'',//数据缓存类型，为空或Memcache或SaeMemcache，其中为空为普通文件缓存，cp2.0添加								
			);
			
		static public $CACHE = array(
			'FILE_CACHE_PATH'=>'',//文件缓存路径
			'MEM_SERVER'=>array( array('127.0.0.1', 11211),  array('localhost', 11211) ),//memcache
		);

		static public init(){
		
		}
		
		static public loadConfig($file){
			self::config['global'] = require($file);
		}
		
		static public function get($key){
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
				default: break;
			}		
		}
}