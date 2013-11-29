<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<link href="__PUBLIC__/css/right2.css" type="text/css" rel="stylesheet" />
<meta content="MSHTML 6.00.6000.17063" name="GENERATOR" />
<title>修改案例</title></head>
<body>
<form action="__URL__/edit" method="post" enctype="multipart/form-data">
  <table  cellpadding="0" cellspacing="1" class="table_form" width="100%">
    <caption>
    修改案例
    </caption>
    <tr>
      <th width="30%" class="align_c"><strong>案例名称</strong></th>
      <td width="70%">		 
			<input name="name" type="text" size="60" value="{$info['name']}">
      </td>
    </tr>
  <tr>
      <th width="30%" class="align_c"><strong>案例简介</strong></th>
      <td width="70%">	    
			<textarea name="intro" cols="58" rows="5">{$info['intro']}</textarea>
	  </td>
    </tr>
    <tr>
      <th width="30%" class="align_c"><strong>案例图片</strong></th>
      <td width="70%"><input name="image" type="file" size="60">(不修改，不用上传)</td>
    </tr>
      <tr>
      <th width="30%" class="align_c"><strong>链接地址 </strong></th>
      <td width="70%"><input name="url" type="text"  size="60" value="{$info['url']}">(可选)</td>
    </tr>
    <tr>
      <th width="30%" class="align_c">&nbsp;</th>
      <td width="70%">
	   <input name="id" type="hidden" value="{$info['id']}">
        <input name="do" type="hidden" value="yes">
        <input type="submit" name="dosubmit" value=" 确定 ">
        &nbsp;&nbsp;&nbsp;
        <input type="reset" name="reset" value=" 重置 "></td>
    </tr>
  </table>
</form>
</body>
</html>
