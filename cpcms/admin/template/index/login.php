<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<link href="__PUBLIC__/css/login.css"  rel="stylesheet" type="text/css" media="screen" charset="utf-8" />
<title>网站后台管理中心</title>
<script language="javascript">
if(self!=top)
{
	parent.location.href='__APP__';
}
//重载验证码
function fleshVerify()
{
var timenow = new Date().getTime();
document.getElementById('verifyImg').src= '__URL__/verify.html?'+timenow;
}
//清除数据
function form_reset()
{
document.getElementById('username').value="";
document.getElementById('password').value="";
document.getElementById('checkcode').value="";
document.getElementById('username').focus();
}
//提交数据前，对数据进行检查
function check_form()
{
	if(document.getElementById('username').value=="")
	{
		alert('请输入用户名');
		document.getElementById('username').focus();
		return false;
	}
	if(document.getElementById('password').value=="")
	{
		alert('请输入密码');
		document.getElementById('password').focus();
		return false;
	}
	if(document.getElementById('checkcode').value=="")
	{
		alert('请输入验证码');
		document.getElementById('checkcode').focus();
		return false;
	}
	return true;
}
</script>
</head>
<body>
<div id="login-bg">
	<form onsubmit="return check_form()"action="__URL__/login" name="login-form" target="_self" method="post" style="float:left;">
		<div id="login-box">
		<div class="form-input">
			<span>用户名：</span><input name="username" type="text" id="username" class="text-input" onFocus="this.style.borderColor='#239fe3'" onBlur="this.style.borderColor='#dcdcdc'"/>
		</div>
		<div class="form-input">
			<span>密&nbsp;&nbsp;码：</span><input name="password" type="password" id="password"  class="text-input" onFocus="this.style.borderColor='#239fe3'" onBlur="this.style.borderColor='#dcdcdc'"/>
		</div>
		<div class="form-input">
			<span>验证码：</span><input name="checkcode" type="text" id="checkcode"  class="text-input" style=" width:60px;" onFocus="this.style.borderColor='#239fe3'" onBlur="this.style.borderColor='#dcdcdc'"/>
			<img src="__URL__/verify" border="0" align="absmiddle" height="20" width="50" style="margin-left:3px;" alt="如果您无法识别验证码，请点图片更换" onclick="fleshVerify()" id="verifyImg"/>
		</div>
		<div class="form-input-image" align="center">
		<input name="do" type="hidden" value="yes" />
		<input type="image" src="__PUBLIC__/images/login/login-btn.gif" />
		<img src="__PUBLIC__/images/login/clean-btn.gif"  style="CURSOR: hand"  onclick="form_reset()"/>
		</div>
	</div>
	</form>
</div>
</body>
</html>
