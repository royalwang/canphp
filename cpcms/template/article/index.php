<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="author" content="单骑闯天下，李文辉">
<meta name="keywords" content="canphp框架-技术文章">
<meta name="description" content="canphp框架-技术文章">
<meta http-equiv="X-UA-Compatible" content="IE=7">
<title>技术文章-canphp框架-php框架</title>
<LINK rel=stylesheet type=text/css href="__PUBLIC__/css/style.css">
</HEAD>


<BODY>
<!--导航菜单-->
{include file="header"}
<DIV class=box>
<div class="left">
<div class="boxleft">

<div class="h4">技术文章</div>

<div class="conbox">

<div class="downloadtitle">canphp框架升级日志</div>


<?php if(!empty($list)){foreach($list as $vo){
$vo['create_time']=date('Y-m-d',$vo['create_time']);
$vo['content']=msubstr(str_replace(array("\r\n","\r","\n"),'',strip_tags(html_out($vo['content']))),0,200);
?>
<div class="downloadbt"><strong><a href="__APP__/article/show-{$vo['id']}.html" target="_blank" title="{$vo['title']}">{$vo['title']}</a></strong><span>发布时间：{$vo['create_time']}</span></div>
<div class="downloadjj" style="text-indent:2em;">

{$vo['content']}

<a href="__APP__/article/show-{$vo['id']}.html" target="_blank" title="{$vo['title']}">[查看]</a>
</div>
<?php }}?>
<br />
<br />
{$page}
<div class="clearfix"></div>
</div></div>
</div>
{include file="right"}

<div class="clearfix"></div>
</DIV>

<!--底部信息-->
{include file="footer"}
</BODY></HTML>
