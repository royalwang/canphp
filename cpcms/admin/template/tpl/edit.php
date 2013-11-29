<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<link href="__PUBLIC__/css/right2.css" type="text/css" rel="stylesheet" />
<meta content="MSHTML 6.00.6000.17063" name="GENERATOR" />
<title>修改模板</title>
</head>
<body>
<form action="__URL__/edit.html?filename={$info['filename']}" method="post">
  <table  cellpadding="0" cellspacing="1" class="table_form" width="100%">
    <caption>
    修改模板	
    </caption>
		<tr>
		 <th width="20%" class="align_c"><strong>模板文件</strong></th>
        <td width="80%">{$info['filename']}</td>
        </tr>
	  <tr>
      <th width="20%" class="align_c"> <font color="red">*</font> <strong>模板内容</strong> <br />        </th>
      <td width="80%"><textarea name="content" rows="30" cols="100"><?php echo $info['content'];?></textarea></td>
    </tr>
		<tr>
		 <th width="20%" class="align_c">&nbsp;</th>
        <td width="80%">
		<input name="id" type="hidden" value="{$info['id']}">
		<input name="do" type="hidden" value="yes">
		<input type="submit" name="dosubmit" value=" 确定 ">&nbsp;&nbsp;&nbsp;<input type="reset" name="reset" value=" 重置 "></td>
        </tr>
  </table>
</form>
</body>
</html>
