<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="keywords" content="{$info['title']},{$info['keywords']},{$info['keywords']},{$info['cat_name']}">
<meta name="description" content="{$info['title']},{$info['keywords']},{$info['description']}">
<meta http-equiv="X-UA-Compatible" content="IE=7">
<title>{$info['title']}|{$info['keywords']}|{$info['cat_name']}|PHP框架|canphp框架官网</title>
<LINK rel=stylesheet type=text/css href="__PUBLIC__/css/style.css">
</HEAD>
<BODY>
<!--导航菜单-->
{include file="header"}
<DIV class=box>
<div class="left">
<div class="boxleft">

<h1>{$info['title']}</h1>
<div class="info"><small>时间:{$info['create_time']}</small> <small>发布者:</small>{$info['create_username']}  <small>点击:</small> {$info['views']} 次</div>
<div class="intro">摘要：{$info['description']}</div>
<div class="conboxnr">

{$info['content']}


</div>
<div class="prev_next">
上一篇：<a href="__APP__/article/show-{$prev['id']}.html" target="_self" title="{$prev['title']}">{$prev['title']}</a><br />
下一篇：<a href="__APP__/article/show-{$next['id']}.html" target="_self" title="{$next['title']}">{$next['title']}</a><br />
</div>
</div>
</div>

{include file="right"}




<div class="clearfix"></div>
</DIV>

<!--底部信息-->
{include file="footer"}
</BODY></HTML>
