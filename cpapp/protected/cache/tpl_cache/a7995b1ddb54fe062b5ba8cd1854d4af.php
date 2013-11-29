<?php if (!defined('CANPHP')) exit;?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>后台管理</title>
<meta content="IE=8" http-equiv="X-UA-Compatible" />
<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
<link rel="stylesheet" type="text/css" href="<?php echo __PUBLIC__; ?>/css/base.css" />
<link rel="stylesheet" type="text/css" href="<?php echo __PUBLIC__; ?>/admin/images/admin_box.css" />
<script type="text/javascript" src="<?php echo __PUBLIC__; ?>/js/jquery.js"></script>
<script type="text/javascript" src="<?php echo __PUBLIC__; ?>/js/kindeditor/kindeditor-min.js"></script>
<script type="text/javascript">
function setTab(id, num, n){
	for(var i=1; i<=n; i++){
		$("#"+id+i).removeClass('hover');
	}
	$("#"+id+num).addClass('hover');
}
KindEditor.ready(function(K) {
	K.create('#editor', {
		width: '99%',
		height: '460px',
		resizeType: 1,
		themeType: 'simple',
		urlType: 'relative',
		allowFileManager : true
	});
});
</script>
</head>
<body>
<div id="contain">
<?php $cpTemplate->display("$__template_file"); ?>
</div>
</body>
</html>
