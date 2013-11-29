<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>{$title}</title>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
<link rel="stylesheet" type="text/css" href="__PUBLIC__/admin/images/admin_style.css" />
<script type="text/javascript" src="__PUBLIC__/js/jquery.js"></script>
</head>
<body style="MARGIN: 0px" scroll="no">
<div class="login_title">{$title}</div>
<div class="login_main">
     <div class="login_box">
	  <div class="login_do" id="tips" style="display:none"> </div>
      <div style="padding:15px 20px;">
		  <table border="0" cellspacing="0" cellpadding="0" width="100%">
			<tbody>
			  <tr>
				<th>用户名：</th>
				<td><input id="username" class="login_input" type="text" /></td>
			  </tr>
			  <tr>
				<th>密&nbsp;&nbsp;&nbsp;码：</th>
				<td><input id="password" class="login_input" type="password" /></td>
			  </tr>
			  <tr>
				<th>验证码：</th>
				<td><div>
					<table width="100%" border="0" cellpadding="0" cellspacing="0">
					  <tr>
						<td width="120"><input id='checkcode' class="login_yz" type="text" /></td>
						<td><img src="{url('index/verify')}" width="100" height="32" title="如果您无法识别验证码，请点图片更换"  id="verifyImg" style="cursor:pointer" /></td>
					  </tr>
					</table>
				  </div></td>
			  </tr>
			  <tr>
				<th>&nbsp;</th>
				<td><a class="button" href="javascript:;" id="btn_submit">登录</a></td>
			  </tr>
			</tbody>
		  </table>
		</div>
    </div>
</div>
<div class="login_footer">
  <p>{$footer}</p>
</div>
<script type="text/javascript">
$(function(){
	if(self != top){
		parent.location.href = "{url('index/login')}";
	}
	
	//刷新验证码
	$("#verifyImg").click(function(){
		var url = $(this).attr('src');
		url = url + ((/\?/.test(url)) ? '&' : '?' ) + new Date().getTime();
		$(this).attr('src', url)
	});
	
	//登录处理
	function submitLogin(){
		var username = $.trim( $("#username").val() );
		var password = $.trim( $("#password").val() );
		var checkcode = $.trim( $("#checkcode").val() );
		$('#tips').show();
		if( "" == username){
			$('#tips').html('请输入用户名');		
			return false;
		}	
		if( "" == password){
			$('#tips').html('请输入密码');
			return false;
		}
		if( "" == checkcode){
			$('#tips').html('请输入验证码');
			return false;
		}		
		$('#tips').html('正在登录中……');
		
		$.post("{url('index/login')}",
				{'username':username, 'password':password, 'checkcode':checkcode},
				function(json){
					if(json.status ==1){
						window.location.href = "{url('index/index')}";
					}else{
						$('#tips').html(json.msg);
						$('#tips').show();
					}
				},
				'json'
		);
	}
	
	//点击登录
	$("#btn_submit").click(function(){
		return submitLogin();
	});
	
	//输完验证码或焦点在登录按钮上，按回车登录
	$("#checkcode, #btn_submit").keydown(function(e){
		if(e.keyCode==13){
			return submitLogin();
		}
	});
	
	//输入框聚焦，则隐藏错误tips
	$("#username, #password, #checkcode").focus(function(){
		$('#tips').hide("slow");
	});

});
</script>
</body>
</html>
