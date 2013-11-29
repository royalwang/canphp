<?php if (!defined('CANPHP')) exit;?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<HTML xmlns="http://www.w3.org/1999/xhtml"><HEAD>
<TITLE><?php echo $title; ?></TITLE>
<META content="text/html; charset=utf-8" http-equiv=Content-Type>
<LINK rel=stylesheet type=text/css href="<?php echo __PUBLIC__; ?>/admin/images/admin_style.css">
<SCRIPT type=text/javascript src="<?php echo __PUBLIC__; ?>/js/jquery.js"></SCRIPT>
<SCRIPT type=text/javascript src="<?php echo __PUBLIC__; ?>/admin/js/jquery.layout.js"></SCRIPT>
<SCRIPT type="text/javascript">
var myLayout;
$(document).ready(function(){
	myLayout=$("body").layout({west__minSize:40,spacing_open:7,spacing_closed:7,east__initClosed:true,north__spacing_open:0,south__spacing_open:0,togglerLength_open:30,togglerLength_closed:60});
});
</SCRIPT>
</HEAD>
<BODY style="MARGIN: 0px" scroll=no>
<DIV class=ui-layout-north>
<DIV class=header>
            <DIV class=logo><?php echo $title; ?></DIV>
            <DIV class=right_menu>
                <SPAN><A class=aa href="<?php echo url('index/password');?>" target="main">修改密码</A></SPAN> 
                <SPAN><A class=bb href="<?php echo url('default/index/index');?>" target="_blank">网站主页</A></SPAN>
                <SPAN><A class=cc href="<?php echo url('index/clearCache');?>" target="main">更新缓存</A></SPAN>
                <SPAN><A class=dd href="<?php echo url('index/logout');?>" target="_parent">注销登录</A></SPAN> 
            </DIV>
          </DIV>
</DIV>

<DIV class=ui-layout-west>

<DIV id=menu>
	  <?php $n=1;if(is_array($leftMenu)) foreach($leftMenu AS $menu) { ?>
		<?php if(!empty($menu['list'])) { ?>
          <DIV class="menubg_1 cursor"><?php echo $menu['title']; ?></DIV>
          <UL class=none>
		  	<?php $n=1; if(is_array($menu['list'])) foreach($menu['list'] AS $name => $url) { ?>
		  		<LI><A href="<?php echo $url; ?>"  target="main"><?php echo $name; ?></A> </LI>
			<?php $n++;}unset($n); ?>
          </UL>
		<?php } ?>
	<?php $n++;}unset($n); ?>
		  </DIV> </DIV> 
          
<DIV class=ui-layout-center>
<IFRAME style="OVERFLOW: visible" id="main" height="100%" src="<?php echo url('index/welcome');?>" frameBorder=0 width="100%" name="main" scrolling=yes> </IFRAME>
</DIV>

<SCRIPT language=javascript> 
$(function(){
	$("#menu").find('DIV').first().attr('class','menubg_2');
	$("#menu").find('UL').first().show();
	$("#menu DIV").click(function(){
		$("#menu DIV").attr('class','menubg_1');
		$("#menu UL").hide();
		$(this).attr('class','menubg_2');
		$(this).next().show();
	});
});
</SCRIPT>
</BODY>
</HTML>
