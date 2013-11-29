<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="author" content="单骑闯天下，李文辉">
<meta name="keywords" content="CanPHP框架,CP框架，PHP框架,PHP开发框架,PHP教程,PHP源码,PHP下载,PHP论坛">
<meta name="description" content="CanPHP框架- 简单、自由、实用、高效、安全，所有人都能学得会的php框架，具有完整的开发手册，丰富的入门教程，是中小型项目开发首选">
<meta http-equiv="X-UA-Compatible" content="IE=7">
<title>CanPHP框架-CP框架-php框架-php开发框架-canphp官方网站</title>
<LINK rel=stylesheet type=text/css href="__PUBLIC__/css/style.css">
</HEAD>
<BODY>
<!--导航菜单-->
{include file="header"}
<DIV class=box>
  <DIV class=indexdld>
    <div class="thisver">
      <p> <strong>简介：</strong> CanPHP框架(简称CP)，<br>
        是一个简单、自由、实用、高效<br>
        的php框架。<a href="__APP__/manual/" style="color:#FF6600">更多功能……</a><br />
        <strong>最新版本：</strong> canphp 1.5正式版</p>

		<a href="__APP__/download.html" title="立即下载" class="lijixiazai"> </a>
    </div>
  </DIV>
  <DIV class=indexfun>
    <H3>使用简单</H3>
    <P class=thisp1>只要您会使用php输出'hello world'，就可以轻松学习CP框架，提升php水平！</P>
    <H3>功能实用</H3>
    <P class=thisp2>不管您是在做大项目还是小项目或对开源系统二次开发，CP框架都可以帮助您！</P>
    <H3>高效安全</H3>
    <P class=thisp3>数据库缓存、静态页面缓存，让效率加倍提升！数据过滤、权限认证，让系统更安全！</P>
  </DIV>
</DIV>
<DIV class=serviceWrapper>
  <DIV class="xxzn">
    <H4>学习指南</H4>
    <div class="xxzntitle"><a href="__APP__/manual/changjianwentijieda.html">CP框架之答疑篇</a></div>
    <div class="xxzninfo">在这里一一解答您对CP框架的疑问与顾虑。</div>
    <div class="xxzntitle"><a href="http://www.canphp.com/bbs/thread-550-1-1.html">CP框架之体验篇</a></div>
    <div class="xxzninfo">带你去体验如何用CP输出"hello world"、数据库操作、模板操作，享受php编程乐趣！ </div>
    <div class="xxzntitle"><a href="http://www.canphp.com/bbs/thread-554-1-1.html">CP框架之实战篇</a></div>
    <div class="xxzninfo">以CPCMS为基础，教你如何去开发一个网站 </div>
    <div class="xxzntitle"><a href="http://www.canphp.com/bbs/forum-21-1.html">CP框架之提高篇</a></div>
    <div class="xxzninfo">静态页面生成、jquery+ajax、网络采集… </div>
  </DIV>
  <DIV class="indexxz">
    <H4>资源下载</H4>
    <div class="downloadtitle">编辑器</div>
    <div class="downloadbt"><a href="http://www.canphp.com/bbs/viewthread.php?tid=26&extra=page%3D1">DW</a> <a href="http://www.canphp.com/bbs/viewthread.php?tid=25&extra=page%3D1">Notepad++ </a> </div>
    <div class="downloadtitle">中文手册</div>
    <div class="downloadbt"><a href="http://www.canphp.com/bbs/viewthread.php?tid=21&extra=page%3D1">PHP中文手册</a> <a href="http://www.canphp.com/bbs/viewthread.php?tid=22&extra=page%3D1">CSS手册</a> <a href="__APP__/manual/">CanPHP手册</a></div>
    <div class="downloadtitle">集成环境</div>
    <div class="downloadbt"><a href="http://www.canphp.com/bbs/viewthread.php?tid=27&extra=page%3D1">WampServer</a> </div>
    <div class="downloadtitle">辅助工具</div>
    <div class="downloadbt"><a href="http://www.canphp.com/bbs/viewthread.php?tid=24&extra=page%3D1">IETester </a></div>
  </DIV>
  <DIV class="indexinfo">
    <H4>最新动态</H4>
    <UL>
	<?php $list=module('article')->getList('',9); 
		  if(!empty($list)) foreach($list as $vo){
	?>
     	 <li> <a href="__APP__/article/show-{$vo['id']}.html" target="_blank" title="{$vo['title']}">{$vo['title']}</a></li>
 	<?php }?>
    </UL>
  </DIV>
</DIV>
<DIV class="line">
  <DIV class=box_title>
    <H3>合作伙伴/友情链接</H3>
  </DIV>
  <DIV class=box_line>
    <UL>

      <LI class=bv><FONT color=red>合作伙伴</FONT> ： 
	  <a href="http://www.5idev.com/" target=_blank>web开发教程</a>
	  <a href="http://www.54cxz.com/" target="_blank">我是初学者</a>
	  <a href="http://www.lqcms.com/" target="_blank">狼群cms</a>
  	 </LI>

	  <LI>友情链接 ： 
			<a href="http://www.54cxy.com/" target="_blank" title="54程序员">54程序员</a>
			<a href="http://speedphp.com/" target="_blank">SpeedPHP框架</a>
			<a href="http://vipinit.com/" target="_blank">Vipin-IT</a>
			<a href="http://www.canphp.com" target="_blank">php框架</a> 
			<a href="http://www.canphp.com/bbs" target="_blank">canphp论坛</a> 
			<a href="http://bbs.doyouhaobaby.net/" target="_blank">DoYouHaoBaby</a>
			<a href="http://www.unrice.com" target="_blank">精品域名</a>
			<a href="http://www.ttphp.com/" target="_blank">PHP教程</a>
      </LI>
    </UL>
  </DIV>
</DIV>
<!--底部信息-->
{include file="footer"}
</BODY>
</HTML>
