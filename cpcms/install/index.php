<?php 
//如果已经安装，跳转到首页
if(file_exists('../data/install.lock'))
{
	header("location:../");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>欢迎使用CanPHP安装！</title>
<link href="images/style.css" rel="stylesheet" type="text/css" />
</head><body>
<div id='content'>
<div id='pageheader'>
	<div id="logo" style="margin-top:10px; margin-left:15px;"><img src="images/logo.jpg" border="0" alt="canphp" /></div>
	<div id="version" class="rightheader">Canphp正式版</div>
</div>
<div id='innercontent'>
	<h1>CanPHP安装界面简述</h1>
    <div class="botBorder">
        <p style="font-size:16px">
		canphp框架是一个简洁，自由，高效的轻量级php开源框架。以“简单，自由，包容”为理念，简化和加快小型web应用项目(如企业网站，小型信息管理系统)开发和开源系统二次开发。以面向应用为基础集成了web开发常用功能：如单一入口控制，模板引擎，数据库缓存，静态页面生成，多语言支持，文件上传，验证码，图片缩略图，无限分类，邮件发送以及常用函数库和数据安全过滤函数库。
		</p>
    </div>
	<form  action="xieyi.html">
		<p class="center"><input type="submit" class="submit" value=" 马上安装体验CanPHP" /></p>
	</form>
</div>
</div>
<div class='copyright'>CanPHP版权</div>
</div>
<div style="display:none;">
</div>
</body>
</html>