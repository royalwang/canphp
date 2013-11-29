<?php
ob_start();
//定义CanPHP框架目录
define('CP_PATH',dirname(__FILE__).'/../../CanPHP/');//注意目录后面加“/”

require(dirname(__FILE__).'/../config.php');//加载配置
require(CP_PATH.'core/cpApp.class.php');//加载应用控制类
$config['LOG_PATH']='./../data/log/';
$app=new cpApp($config);//以类库模式执行


//判断是否已经安装过
if (file_exists('./../data/install.lock'))
{
	cpError::show('已经安装,如果还继续安装请先删除data/install.lock，再继续');
}

$config=$_POST;//接收表单数据
if(empty($config))
{
	cpError::show('请填写数据库参数');
}

$model=new cpModel($config);//实例化模型类

$sql="CREATE DATABASE IF NOT EXISTS `".$config['DB_NAME']."` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;";
$model->query($sql);//如果指定数据库不存在，则尝试创建
$model->db->select_db($config['DB_NAME']);//选择数据库

$ins=new Install();//实例化数据库安装类
$sql_array=$ins->mysql('../data/db.sql','cp_',$config['DB_PREFIX']); 

//执行数据库操作
foreach($sql_array as $sql)
{
	$model->db->query($sql);//安装数据
}

//修改配置文件
$config_array=array();
foreach($config as $key=>$value)
{
	$config_array["config['".$key."']"]=$value;	
}
if(!set_config($config_array))
{
	cpError::show('配置文件写入失败！');
}


//安装成功，创建锁定文件
$data_dir=dirname(__FILE__).'/../data/';
if(!is_dir($data_dir))
	@mkdir($data_dir);
@fopen($data_dir.'install.lock','w');
//安装成功，跳转到首页
@header("location:./../index.php");

//修改配置的函数 
function set_config($array,$config_file='./../config.php')
{
	 if(empty($array)||!is_array($array))
	 {
		 return false;
	 }

	 $config=file_get_contents($config_file);//读取配置
 	 foreach($array as $name=>$value)
     { 
		$name=str_replace(array("'",'"','['),array("\\'",'\"','\['),$name);//转义特殊字符，再传给正则替换
		if(is_string($value)&&!in_array($value,array('true','false','3306')))
		{
			$value="'".$value."'";//如果是字符串，加上单引号
		}
		$config=preg_replace("/(\\$".$name.")\s*=\s*(.*?);/i", "$1={$value};", $config);//查找替换
	 }
	//写入配置
	if(file_put_contents($config_file,$config))
	return true;
	else 
	return false; 
}
?>