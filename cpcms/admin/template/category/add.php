<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<link href="__PUBLIC__/css/right2.css" type="text/css" rel="stylesheet" />
<meta content="MSHTML 6.00.6000.17063" name="GENERATOR" />
<title>添加栏目</title>
</head>
<body>
<form action="__URL__/add" method="post">
  <table  cellpadding="0" cellspacing="1" class="table_form" width="100%">
    <caption>
    添加栏目
    </caption>
    <tr>
      <th width="30%" class="align_c"><strong>上级分类</strong></th>
      <td width="70%">		 
	     <select name="pid" >
		   <?php if($pid==0){ ?>
			<option value="0" selected="selected">无（作为一级栏目）</option>
		   <?php }?>
		   <?php if(is_array($cat)) foreach($cat as $vo){ ?>
				 <?php if($vo['id']==$pid){ ?>
					<option value="{$vo['id']}" selected="selected">{$vo['cname']}</option>
				 <?php } else{?>    
					 <option value="{$vo['id']}">{$vo['cname']}</option> 
				 <?php } ?> 
			<?php } ?>  
		  </select>	
      </td>
    </tr>
  <tr>
      <th width="30%" class="align_c"><strong>绑定模块</strong></th>
      <td width="70%">	    
	   <select name="module" >
		   <?php if(is_array($modules)) foreach($modules as $k=>$vo){ ?>
				 <?php if($vo['id']==$pid){ ?>
					<option value="{$k}" selected="selected">{$vo}</option>
				 <?php } else{?>    
					 <option value="{$k}">{$vo}</option> 
				 <?php } ?> 
			<?php } ?>  
		  </select>
		</td>
    </tr>
    <tr>
      <th width="30%" class="align_c"><strong>栏目名称</strong></th>
      <td width="70%"><input name="name" type="text" size="40"></td>
    </tr>
    <tr>
      <th width="30%" class="align_c">&nbsp;</th>
      <td width="70%">
        <input name="do" type="hidden" value="yes">
        <input type="submit" name="dosubmit" value=" 确定 ">
        &nbsp;&nbsp;&nbsp;
        <input type="reset" name="reset" value=" 重置 "></td>
    </tr>
  </table>
</form>
</body>
</html>
