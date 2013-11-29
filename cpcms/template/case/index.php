<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="author" content="单骑闯天下，李文辉">
<meta name="keywords" content="案例中心">
<meta name="description" content="案例中心">
<meta http-equiv="X-UA-Compatible" content="IE=7">
<title>
案例中心
</title>
<LINK rel=stylesheet type=text/css href="__PUBLIC__/css/style.css">
</HEAD>
<BODY>
<!--导航菜单-->
{include file="header"}
<DIV class=box>
<div class="left">
<div class="boxleft">

<div class="h4">案例中心</div>

<div class="conbox">
<DIV class=caseDisplay>

<?php if(is_array($list)){ foreach($list as $vo){ ?>
	<?php if(empty($vo['url'])){?>
		<DIV class=caseHolder>
		<P class=thumb><IMG
		alt="{$vo['name']}" src="__ROOT__/upload/case/thumb_{$vo['image']}"></p>
		<P class=txt>{$vo['name']}</P>
		<P class=specs>{$vo['intro']}</P>
		</DIV>
	<?php }else{ ?> 
		<DIV class=caseHolder>
		<P class=thumb><A title="{$vo['name']}" href="{$vo['url']}" target="_blank"><IMG
		alt="{$vo['name']}" src="__ROOT__/upload/case/thumb_{$vo['image']}"></A></p>
		<P class=txt><A title="{$vo['name']}" href="{$vo['url']}"  target="_blank">{$vo['name']}</A></P>
		<P class=specs>{$vo['intro']}</P>
		</DIV>
	<?php } ?> 
<?php }}?>




<div class="clearfix"></div>
</div>
<div class="pagesbox">{$page}</div>
</div></div>
</div>

{include file="right"}

<div class="clearfix"></div>
</DIV>


<!--底部信息-->
{include file="footer"}
</BODY></HTML>
