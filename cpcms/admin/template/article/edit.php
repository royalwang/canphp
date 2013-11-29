<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<link href="__PUBLIC__/css/right2.css" type="text/css" rel="stylesheet" />
<meta content="MSHTML 6.00.6000.17063" name="GENERATOR" />
<title>修改文章</title>
<script type="text/javascript" charset="utf-8" src="__PUBLIC__/js/kindeditor-3.4.4/kindeditor.js"></script>
<script type="text/javascript">
    KE.show({
        id : 'content',
		urlType : 'domain'
    });
function check_length()
{
	var lg=255-document.getElementById('description').value.length;
	if(lg>=0)
		document.getElementById('ls_description').innerHTML=lg;
	else
		alert('字数超出限制');
}
function check_form(obj){
	if(obj.title.value=='')
	{
		alert('文章标题不能为空');
		obj.title.focus();
		return false;
	}
	if(obj.cat_id.value=='')
	{
		alert('文章栏目不能为空');
		obj.cat_id.focus();
		return false;
	}
	return true;
}
</script>
</head>
<body>
<form name="add" action="__URL__/edit" method="post"  onSubmit="return check_form(document.add);" >
  <table cellpadding="0" cellspacing="1" class="table_form">
    <caption>
    修改文章
    </caption>
    <tr>
      <th colspan="2"><div align="left"><strong><a href="__URL__/index.html" target="_self">返回文章管理</a></strong></div></th>
    </tr>
    <tr>
      <th width="20%"><font color="red">*</font> <strong>标题</strong> <br /></th>
      <td><input type="text" name="title" id="title" value="{$info['title']}" size="100" class="inputtitle"/>     </td>
    </tr>
    <tr>
      <th width="20%"> <font color="red">*</font> <strong>栏目</strong> <br />      </th>
      <td>
		  <select name="cat_id">
			<option value="">选择分类</option>
			   <?php foreach($cat as $vo){ ?>    
					 <?php if($vo['id']==$info['cat_id']){ ?>
						 <option value="{$vo['id']}" selected="selected">{$vo['cname']}</option> 
					  <?php }else{ ?>
							 <option value="{$vo['id']}">{$vo['cname']}</option> 
					  <?php } ?> 
			   <?php } ?> 
		  </select>
		</td>
    </tr>
    <tr>
      <th width="20%"> <strong>关键词</strong> <br />
        多关键词之间用空格隔开 </th>
      <td><input name="keywords" type="text" class="" id="keywords" value="{$info['keywords']}" size="100" maxlength="255"  /></td>
    </tr>
    <tr>
      <th width="20%"> <strong>摘要</strong> <br />      </th>
      <td><img src="__PUBLIC__/images/right2/icon.gif" width="12" height="12"> 还可以输入 <font id="ls_description" color="#ff0000;">255</font> 个字符！<br />
        <textarea name="description" id="description" rows="4" cols="50" class="" style="width:80%" onKeyUp="check_length();">{$info['description']}</textarea>      </td>
    </tr>
    <tr>
      <th width="20%"> <font color="red">*</font> <strong>内容</strong> <br />        </th>
      <td><textarea name="content" id="content" style=" width:700px;height:450px;visibility:hidden;"><?php echo html_out($info['content']);?></textarea></td>
    </tr>
    <tr>
      <th width="20%"><strong>状态</strong><br /> </th>
      <td>
	  <?php if($info['status']){ ?>
        <input type="radio" name="status" value="1" checked="checked"/>
        发布
        <input type="radio" name="status" value="0">
        草稿
	 <?php }else{?>
	      <input type="radio" name="status" value="1"/>
        发布
        <input type="radio" name="status" value="0" checked="checked">
        草稿
	 <?php }?>
	</td>
    </tr>
    <tr>
      <td></td>
      <td><div align="center">
	    <input type="hidden" name="id" value="{$info['id']}">
        <input type="hidden" name="do" value="yes">
        <input type="submit" name="dosubmit" value="提交">
        &nbsp;</div></td>
    </tr>
  </table>
</form>
</body>
</html>
