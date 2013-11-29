<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<link href="__PUBLIC__/css/right2.css" type="text/css" rel="stylesheet" />
<meta content="MSHTML 6.00.6000.17063" name="GENERATOR" />
<title>文章管理</title>
</head>
<body>
<table  cellpadding="0" cellspacing="1" class="table_list" width="100%">
  <caption>
 <?php if(empty($cur_cat['name'])){?>
  	所有文章
  <?php }else{?>
 	<span style="color:#FF0000">{$cur_cat['name']}</span>栏目下的文章&nbsp;&nbsp;<a href="__URL__/index.html" target="_self">返回所有文章</a>
 <?php } ?>
  </caption>
  <tbody>
    <tr>
      <th colspan="7"><div align="left">&nbsp;&nbsp;<a href="__URL__/add-{$cur_cat['id']}.html" target="_self">发布文章</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="__URL__/index.html" target="_self">所有文章</a></div></th>
    </tr>
	   <tr>
      <td colspan="7"><div align="center">
	  <form action="__URL__/search" method="get" target="_self">
	 关键字： <input name="keyword" type="text" size="40" />
	  <input name="do" type="hidden" value="yes" />
	  <input type="submit"  value="搜索" />
	  </form>
		</div></td>
    </tr>
    <tr>
      <th width="5%">ID</th>
      <th width="35%">文章标题</th>
      <th width="9%">所属栏目</th>
      <th width="6%">浏览量</th>
      <th width="10%">发布人</th>
      <th width="13%">发布时间</th>
      <th width="22%">管理操作</th>
    </tr>
      <?php if(!empty($list)) foreach($list as $vo){?>
      <tr>
        <td class="align_c">{$vo['id']}</td>
        <td><a 
      href="__ROOT__/../article/show-{$vo['id']}.html" target="_blank"><span 
      class="">{$vo['title']}</span></a></td>
        <td class="align_c"><a href="__URL__/index-{$vo['cat_id']}.html" target="_self">{$vo['cat_name']}</a></td>
        <td class="align_c">{$vo['views']}</td>
        <td class="align_c">{$vo['create_username']}</td>
        <td class="align_c"><?php $vo['create_time']=date('Y-m-d H:i:s',$vo['create_time']);?>{$vo['create_time']}</td>
        <td class="align_c">
		 <?php if($vo['top']){?>
				<a href="__URL__/state-top-{$vo['id']}-0.html" target="_self">取消置顶</a> | 
		 <?php }else{?>
				<a href="__URL__/state-top-{$vo['id']}-1.html" target="_self">置顶</a> | 
		 <?php }?>
		 
	     <?php if($vo['recommend']){?>
			<a href="__URL__/state-recommend-{$vo['id']}-0.html" target="_self">取消推荐</a> | 
		<?php }else{?>
			<a href="__URL__/state-recommend-{$vo['id']}-1.html" target="_self">推荐</a> | 
		<?php }?>
		<a  href="__URL__/edit-{$vo['id']}.html" target="_self">修改</a> 
		<?php if(Auth::checkPower('article','del')){?>| 
	    <a  href="__URL__/del-{$vo['id']}.html" target="_self" onClick="return confirm('确定要删除吗？')">删除</a></td>
		<?php }?>
      </tr>
    <?php }?>
	<tr>
	<td colspan="7"><div align="center">{$page}</div></td>
	</tr>
  </tbody>
</table>
</body>
</html>
