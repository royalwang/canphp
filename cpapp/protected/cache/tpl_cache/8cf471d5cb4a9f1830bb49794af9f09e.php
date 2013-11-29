<?php if (!defined('CANPHP')) exit;?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo $title; ?></title>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
<link rel="stylesheet" type="text/css" href="<?php echo __PUBLICAPP__; ?>/images/install.css" />
</head>
<body>
<div class="install_main">
  <div class="install_title"><?php echo $title; ?></div>
  <div class="install_left">
    <ul>
	  <?php $n=1; if(is_array($menu)) foreach($menu AS $action => $title) { ?>
		<?php if($action == ACTION_NAME) { ?>
			<li class="on"><?php echo $title; ?></li>
		<?php } else { ?>
			<li><?php echo $title; ?></li>	
		<?php } ?>
	  <?php $n++;}unset($n); ?>
    </ul>
  </div>
  <?php $cpTemplate->display("$__template_file"); ?>
</div>
</body>
</html>