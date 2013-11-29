<?php
//网站全局配置
//在这里配置你所需的参数(与CanPHP无关)
$config['ver']='1.2.2011.1020';//版本号,2011.0314表示发布日期

//网站全局配置结束

//日志和错误调试配置
$config['DEBUG']=true;	//是否开启调试模式，true开启，false关闭
$config['LOG_ON']=true;//是否开启出错信息保存到文件，true开启，false不开启
$config['LOG_PATH']='./data/log/';//出错信息存放的目录，出错信息以天为单位存放，一般不需要修改
$config['ERROR_URL']='';//出错信息重定向页面，为空采用默认的出错页面，一般不需要修改
$config['ERROR_HANDLE']=false;//是否启动CP内置的错误处理，如果开启了xdebug，建议设置为false
//日志和错误调试配置结束

//应用配置
		//网址配置
$config['URL_REWRITE_ON']=false;//是否开启重写，true开启重写,false关闭重写
$config['URL_MODULE_DEPR']='/';//模块分隔符，一般不需要修改
$config['URL_ACTION_DEPR']='-';//操作分隔符，一般不需要修改
$config['URL_PARAM_DEPR']='-';//参数分隔符，一般不需要修改
$config['URL_HTML_SUFFIX']='.html';//伪静态后缀设置，，例如 .html ，一般不需要修改
		
		//模块配置
$config['MODULE_PATH']='./module/';//模块存放目录，一般不需要修改
$config['MODULE_SUFFIX']='Mod.class.php';//模块后缀，一般不需要修改
$config['MODULE_INIT']='init.php';//初始程序，一般不需要修改
$config['MODULE_DEFAULT']='index';//默认模块，一般不需要修改
$config['MODULE_EMPTY']='empty';//空模块	，一般不需要修改	
		
		//操作配置
$config['ACTION_DEFAULT']='index';//默认操作，一般不需要修改
$config['ACTION_EMPTY']='_empty';//空操作，一般不需要修改

		//静态页面缓存
$config['HTML_CACHE_ON']=false;//是否开启静态页面缓存，true开启.false关闭
$config['HTML_CACHE_PATH']='./data/html_cache/';//静态页面缓存目录，一般不需要修改
$config['HTML_CACHE_SUFFIX']='.html';//静态页面缓存后缀，一般不需要修改
$config['HTML_CACHE_RULE']['index']['index']=1000;//缓存时间,单位：秒
/*
缓存规则如下，可创建多条规则
$config['HTML_CACHE_RULE']['模块名']['操作名']=缓存时间;//单位：秒,可创建多条数据
$config['HTML_CACHE_RULE']['模块名1']['操作名1']=缓存时间;
$config['HTML_CACHE_RULE']['模块名1']['操作名2']=缓存时间;
$config['HTML_CACHE_RULE']['模块名2']['操作名1']=缓存时间;
$config['HTML_CACHE_RULE']['模块名2']['操作名2']=缓存时间;
*/
//应用配置结束

//数据库配置
$config['DB_TYPE']='mysql';//数据库类型，一般不需要修改
$config['DB_HOST']='localhost';//数据库主机，一般不需要修改
$config['DB_USER']='root';//数据库用户名
$config['DB_PWD']='123456';//数据库密码
$config['DB_PORT']=3306;//数据库端口，mysql默认是3306，一般不需要修改
$config['DB_NAME']='cp2';//数据库名
$config['DB_CHARSET']='utf8';//数据库编码，一般不需要修改
$config['DB_PREFIX']='cp_';//数据库前缀
$config['DB_PCONNECT']=false;//true表示使用永久连接，false表示不适用永久连接，一般不使用永久连接

$config['DB_CACHE_ON']=false;//是否开启数据库缓存，true开启，false不开启
$config['DB_CACHE_PATH']='./data/db_cache/';//数据库查询内容缓存目录，地址相对于入口文件，一般不需要修改
$config['DB_CACHE_TIME']=600;//缓存时间,0不缓存，-1永久缓存
$config['DB_CACHE_CHECK']=false;//是否对缓存进行校验，一般不需要修改
$config['DB_CACHE_FILE']='cachedata';//缓存的数据文件名
$config['DB_CACHE_SIZE']='15M';//预设的缓存大小，最小为10M，最大为1G
$config['DB_CACHE_FLOCK']=true;//是否存在文件锁，设置为false，将模拟文件锁，一般不需要修改
//数据库配置结束

//模板配置
$config['TPL_TEMPLATE_PATH']='./template/';//模板目录，一般不需要修改
$config['TPL_TEMPLATE_SUFFIX']='.php';//模板后缀，一般不需要修改，一般不需要修改
$config['TPL_CACHE_ON']=false;//是否开启模板缓存，true开启,false不开启
$config['TPL_CACHE_PATH']='./data/tpl_cache/';//模板缓存目录，一般不需要修改
$config['TPL_CACHE_SUFFIX']='.php';//模板缓存后缀,一般不需要修改，一般不需要修改
//模板配置结束
?>