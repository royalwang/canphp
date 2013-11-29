<?php if (!defined('CANPHP')) exit;?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $title; ?></title>
<style>
*{PADDING-BOTTOM:0px;MARGIN:0px;PADDING-LEFT:0px;PADDING-RIGHT:0px;PADDING-TOP:0px}
BODY{FONT-FAMILY:Verdana,Arial,Helvetica,sans-serif;BACKGROUND:#fff;COLOR:#333;FONT-SIZE:12px}
IMG{BORDER-BOTTOM:medium none;BORDER-LEFT:medium none;DISPLAY:block;BORDER-TOP:medium none;BORDER-RIGHT:medium none}
LI{LIST-STYLE-TYPE:none;LIST-STYLE-IMAGE:none}.clear{CLEAR:both}
.df_main{ width:950px; margin:0px auto}
.df_title {MARGIN: 0px auto;font:30px Georgia, Arial, sans-serif; font-weight:700; padding:20px; border-bottom:1px solid #eee; color:#3071bf ; width:910px; margin:0px auto; margin-top:20px; margin-bottom:10px;}
.df_title span{ font-size:14px; padding-left:20px;}
.df_intro{border:1px dashed #ececec; padding:20px;margin-bottom:10px;}
.df_intro h3{ padding:0px; margin:0px; font-size:16px; padding-bottom:10px; font-weight:700;}
.df_intro p{padding:0px; margin:0px; line-height:26px; font-size:14px; text-indent:2em;}
.button A{BORDER: #427ac7 1px solid;LINE-HEIGHT: 32px; MARGIN-TOP: 12px; font-size:12px; BACKGROUND:#70a5ee; FLOAT: left; HEIGHT: 32px; PADDING-TOP: 0px; width:120px; text-align:center; font-weight:700;color:#f7f7fa; text-decoration:none; margin-right:15px;}
.button A:hover{BACKGROUND:#3071bf; border:1px solid #3071bf; text-decoration:none;}
.button span{ line-height:60px;}
.df_box{border:1px dashed #ececec; padding:20px;margin-bottom:10px;}
</style>
</head>
<body>
<div class="df_main">
  <div class="df_title">CPAPP<span><?php echo $hello; ?></span></div>
  <div class="df_intro">
    <h3>CPAPP简介：</h3>
    <p>CPAPP是基于CP2.0框架，使用APP开发模式，采用MVCA四层（MVC+api层，各APP之间通过api层通信）架构，内置了通用后台和APP管理器，并整合了一套自主研发、功能强大、浏览器兼容性好的CSS框架，且遵循apache2.0协议的开源基础应用系统。</p>
    <br />
  
    <h3>特色：</h3>
    <p>1、内置通用后台，省去后台登录，登录验证，后台界面开发。</p>
    <p>2、基于APP开发，伸缩自由，不仅适用于复杂项目，也适用于简单项目。</p>
    <p>3、PHP框架和CSS框架的整合，不但简单快速的实现功能，还解决了前端页面CSS兼容性问题；</p>
    <p>4、官方推出APP应用中心，无需CODING，只需一键在线安装APP，就可以实现功能。</p>
    <br />
	
    <p> <strong>内置的通用后台</strong>：实现了登录页面，登录验证，后台首页，并自动加载各app的后台操作到后台首页左侧的菜单栏。</p>
    <p> <strong>内置的app管理器</strong>：不仅可以安装、卸载、启用、停用，导入，导出和创建app，还可以通过应用中心在线安装app。 </p>
    <br />
	
	<div class="clear"></div>
    <div class="button"><a href="http://www.canphp.com">进入CP官网</a> <a href="<?php echo url('admin/index/index');?>">点击进入后台</a> <span>(后台默认用户名admin 密码123456)</span></div>
    <div class="clear"></div>
  </div>
  <div class="df_box">版权归canphp.com所有</div>
</div>
</body>
</html>
