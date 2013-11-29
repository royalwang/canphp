<?php if (!defined('CANPHP')) exit;?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="keywords" content="<?php echo $info['title']; ?>,<?php echo $info['keywords']; ?>,<?php echo $info['keywords']; ?>,<?php echo $info['cat_name']; ?>">
<meta name="description" content="<?php echo $info['title']; ?>,<?php echo $info['keywords']; ?>,<?php echo $info['description']; ?>">
<meta http-equiv="X-UA-Compatible" content="IE=7">
<title><?php echo $info['title']; ?>|<?php echo $info['keywords']; ?>|<?php echo $info['cat_name']; ?>|PHP框架|canphp框架官网</title>
<LINK rel=stylesheet type=text/css href="<?php if (defined('http://127.0.0.1/cp/cpcms//public')) echo http://127.0.0.1/cp/cpcms//public; else echo 'http://127.0.0.1/cp/cpcms//public'; ?>/css/style.css">
</HEAD>
<BODY>
<!--导航菜单-->
<div class="top">
  <div class="head">
    <h1><a href="<?php if (defined('__APP__')) echo __APP__; else echo '__APP__'; ?>/" style="margin-top:10px;width:205px; height:75px; display:block;">&nbsp;</a></h1>
    <div class="menu">
      <ul class="nav">
        <li><a href="<?php if (defined('__APP__')) echo __APP__; else echo '__APP__'; ?>/" class="hover">首  页</a></li>
		<li><a href="<?php if (defined('__APP__')) echo __APP__; else echo '__APP__'; ?>/update.html">升级日志</a></li>
        <li><a href="<?php if (defined('__APP__')) echo __APP__; else echo '__APP__'; ?>/download.html">框架下载</a></li>
        <li><a href="<?php if (defined('__APP__')) echo __APP__; else echo '__APP__'; ?>/case/">案例演示</a></li>
        <li><a href="<?php if (defined('__APP__')) echo __APP__; else echo '__APP__'; ?>/manual/">在线手册</a></li>
		<li><a href="<?php if (defined('__APP__')) echo __APP__; else echo '__APP__'; ?>/article/index.html">技术文章</a></li>
        <li><a href="http://www.canphp.com/bbs/index.php">交流论坛</a></li>
      </ul>
    </div>
  </div>
</div>
<DIV class=box>
<div class="left">
<div class="boxleft">

<h1><?php echo $info['title']; ?></h1>
<div class="info"><small>时间:<?php echo $info['create_time']; ?></small> <small>发布者:</small><?php echo $info['create_username']; ?>  <small>点击:</small> <?php echo $info['views']; ?> 次</div>
<div class="intro">摘要：<?php echo $info['description']; ?></div>
<div class="conboxnr">

<?php echo $info['content']; ?>


</div>
<div class="prev_next">
上一篇：<a href="<?php if (defined('__APP__')) echo __APP__; else echo '__APP__'; ?>/article/show-<?php echo $prev['id']; ?>.html" target="_self" title="<?php echo $prev['title']; ?>"><?php echo $prev['title']; ?></a><br />
下一篇：<a href="<?php if (defined('__APP__')) echo __APP__; else echo '__APP__'; ?>/article/show-<?php echo $next['id']; ?>.html" target="_self" title="<?php echo $next['title']; ?>"><?php echo $next['title']; ?></a><br />
</div>
</div>
</div>

<div class="right">
<div class="kjdownload">
<div class="title">框架下载</div>
<div style="line-height:22px; padding:15px;"><strong>简介：</strong>CanPHP框架(简称CP)，是一个简单、自由、实用、高效的php框架。
<br>
<strong>最新版本： </strong>canphp1.5
<a href="<?php if (defined('__APP__')) echo __APP__; else echo '__APP__'; ?>/download.html"><img src="<?php if (defined('http://127.0.0.1/cp/cpcms//public')) echo http://127.0.0.1/cp/cpcms//public; else echo 'http://127.0.0.1/cp/cpcms//public'; ?>/images/xz.png"></a>
</div>
</div>

<div class="boxone">
<div class="title">最新文章</div>
<div class="r_txt">
 <UL>
	<?php $list=module('article')->getList('',10,'id desc'); 
		  if(!empty($list)) foreach($list as $vo){
	?>
     	 <li> <a href="<?php if (defined('__APP__')) echo __APP__; else echo '__APP__'; ?>/article/show-<?php echo $vo['id']; ?>.html" target="_blank" title="<?php echo $vo['title']; ?>"><?php echo $vo['title']; ?></a></li>
 	<?php }?>
   </UL></div>
</div>


<div class="boxone">
<div class="title">热门文章</div>
<div class="r_txt">
  <UL>
	<?php $list=module('article')->getList('',10,'views desc'); 
		  if(!empty($list)) foreach($list as $vo){
	?>
     	 <li> <a href="<?php if (defined('__APP__')) echo __APP__; else echo '__APP__'; ?>/article/show-<?php echo $vo['id']; ?>.html" target="_blank" title="<?php echo $vo['title']; ?>"><?php echo $vo['title']; ?></a></li>
 	<?php }?>
   </UL></div>
</div>

</div>




<div class="clearfix"></div>
</DIV>

<!--底部信息-->
<DIV class=footer>
  <div class="inner">
    <P class="about"><A href="http://www.canphp.com/" target="_blank">关于CanPHP</A> | <A 
href="http://www.canphp.com/" target="_blank">服务条款</A> | <A 
href="http://www.canphp.com/" target="_blank">广告服务</A> | <A 
href="http://www.canphp.com/" target="_blank">商务洽谈</A> | <A 
href="http://www.canphp.com/" target="_blank">客服中心</A> | <A 
href="http://www.canphp.com/" target="_blank">网站导航</A> | <A 
href="http://www.canphp.com/" _blank?>版权所有</A></P>
    <P id=copyRightEn>Copyright © 2011 CanPHP</P>
    <P id=copyRightCn>CanPHP 版权所有 ( <A href="http://www.miibeian.gov.cn/" 
target="_blank">皖ICP备10014930号-3</A>)<script src="http://s4.cnzz.com/stat.php?id=2851790&web_id=2851790&show=pic" language="JavaScript"></script></P>
  </DIV>
</DIV>

</BODY></HTML>
