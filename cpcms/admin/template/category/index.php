<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<link href="__PUBLIC__/css/right2.css" type="text/css" rel="stylesheet" />
<meta content="MSHTML 6.00.6000.17063" name="GENERATOR" />
<title>栏目管理</title>
</head>
<body>
<table  cellpadding="0" cellspacing="1" class="table_list" width="100%">
  <caption>
  栏目管理
  </caption>
  <tbody>
    <tr>
      <th colspan="6"><div align="left">&nbsp;&nbsp;<a href="__URL__/add.html" target="_self">添加栏目</a></div></th>
    </tr>
    <tr>
      <th width="10%">ID</th>
      <th width="30%">栏目名称</th>
	  <th width="20%">绑定模块</th>
      <th width="40%">管理操作</th>
    </tr>
    <?php if(!empty($cat)) foreach($cat as $vo){?>
    <tr>
      <td class="align_c">{$vo['id']}</td>
      <td><a 
      href="__APP__/Article/index-{$vo['id']}.html"><span 
      class="">{$vo['cname']}</span></a></td>
	   <td class="align_c">文章</td>
      <td class="align_c">
	  <a  href="__URL__/add-{$vo['id']}.html" target="_self">添加子栏目</a>&nbsp;&nbsp;|&nbsp;&nbsp;
	  <a  href="__URL__/edit-{$vo['id']}.html" target="_self">修改</a>&nbsp;&nbsp;|&nbsp;&nbsp;
	  <a  href="__URL__/del-{$vo['id']}.html" target="_self" onClick="return confirm('确定要删除吗？')">删除</a>
	  </td>
    </tr>
    <?php }?>
  </tbody>
</table>
</body>
</html>
