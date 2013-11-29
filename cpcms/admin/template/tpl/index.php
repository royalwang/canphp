<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<link href="__PUBLIC__/css/right2.css" type="text/css" rel="stylesheet" />
<meta content="MSHTML 6.00.6000.17063" name="GENERATOR" />
<title>模板管理</title>
</head>
<body>
  <table  cellpadding="0" cellspacing="1" class="table_list" width="100%">
    <caption>
    模板管理
    </caption>
    <tbody>
      <tr>
        <th width="40%">模板文件</th>
		<th width="40%">上次修改时间</th>
        <th width="20%">修改</th>
      </tr>
	  
	  <?php if(!empty($list)) foreach($list as $vo){?>
      <tr>
        <td><a href="__URL__/edit.html?filename={$vo['filename']}" target="_self">{$vo['filename']}</a></td>
		<td class="align_c"><?php echo date("Y-m-d H:i:s",$vo['filemtime']);?></td>
        <td class="align_c">
		<a href="__URL__/edit.html?filename={$vo['filename']}" target="_self">修改</a>
		<a href="__URL__/restore.html?filename={$vo['filename']}" target="_self">恢复到上一个版本</a>
		</td>
      </tr>
	  <?php }?>
    </tbody>
  </table>
</body>
</html>
