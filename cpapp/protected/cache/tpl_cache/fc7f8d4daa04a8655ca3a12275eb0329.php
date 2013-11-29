<?php if (!defined('CANPHP')) exit;?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>后台管理</title>
<meta content="IE=8" http-equiv="X-UA-Compatible" />
<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
<link rel="stylesheet" type="text/css" href="<?php echo __PUBLIC__; ?>/admin/images/admin_box.css" />
<script type="text/javascript" src="<?php echo __PUBLIC__; ?>/js/jquery.js"></script>
</head>
<body>
<script type="text/javascript">
$(function(){
	$(".searchauto").change(function(){$("#search").submit();});
});
</script>
<div id="contain">
  <div class="admin_title">
    <div class="on">文章栏目</div>
    <a href="#">文章栏目管理</a> <a class="history" href="javascript:history.back(-1)">返回上一页</a> </div>
  <form id="search" method="get" action="#">
    <div id="search_div"> &nbsp;&nbsp;栏目:
      <select id="s_cid" class="searchauto" name="s_cid">
        <option selected="selected" value="">选择分类</option>
        <option value="2">饮酒文化</option>
        <option value="1">企业要闻</option>
      </select>
      &nbsp;&nbsp;推荐类型:
      <select id="s_recommend" class="searchauto" name="s_recommend">
        <option selected="selected" value="">不限</option>
        <option value="1">否内容</option>
        <option value="2">热门推荐</option>
        <option value="3">重点推荐</option>
      </select>
      &nbsp;&nbsp;否:
      <select id="s_top" class="searchauto" name="s_top">
        <option selected="selected" value="">不限</option>
        <option value="1">否</option>
        <option value="2">不否</option>
      </select>
      &nbsp;&nbsp;状态:
      <select id="s_state" class="searchauto" name="s_state">
        <option selected="selected" value="">不限</option>
        <option value="1">正常</option>
        <option value="2">锁定</option>
      </select>
      &nbsp;&nbsp;关键字:
      <input id="s_keyword" class="input w150" type="text" name="s_keyword" />
      <input class="button" value="搜 索" type="submit" />
    </div>
  </form>
  <form method="post" action="" target="_self">
    <div class="admin_list">
      <table width="100%">
        <tr>
          <th width="20"><input onclick="checkAll(this)" type="checkbox" /></th>
          <th width="60">ID</th>
          <th>文章标题</th>
          <th width="40">置顶</th>
          <th width="40">状态</th>
          <th width="60">浏览量</th>
          <th width="80">发布时间</th>
          <th width="80">管理操作</th>
        </tr>
        <tr>
          <td><input id="id[]" value="1501092" type="checkbox" name="id[]" /></td>
          <td>15</td>
          <td><a href="#" target="_blank">CPAPP测试列表数据</a></td>
          <td><a href="#" target="_self">否</a> </td>
          <td><a href="#" target="_self">正常</a> </td>
          <td>3</td>
          <td>2012-07-11</td>
          <td><a href="#" target="_self">修改</a> | <a onclick="return confirm('确定要删除吗？')" href="#" target="_self">删除</a></td>
        </tr>
        <tr>
          <td><input id="id[]" value="1501092" type="checkbox" name="id[]" /></td>
          <td>15</td>
          <td><a href="#" target="_blank">CPAPP测试列表数据</a></td>
          <td><a href="#" target="_self"><font color="red">是</font></a> </td>
          <td><a href="#" target="_self"><font color="red">锁定</font></a> </td>
          <td>3</td>
          <td>2012-07-11</td>
          <td><a href="#" target="_self">修改</a> | <a onclick="return confirm('确定要删除吗？')" href="#" target="_self">删除</a></td>
        </tr>
        <tr>
          <td><input id="id[]" value="1501092" type="checkbox" name="id[]" /></td>
          <td>15</td>
          <td><a href="#" target="_blank">CPAPP测试列表数据</a></td>
          <td><a href="#" target="_self">否</a> </td>
          <td><a href="#" target="_self">正常</a> </td>
          <td>3</td>
          <td>2012-07-11</td>
          <td><a href="#" target="_self">修改</a> | <a onclick="return confirm('确定要删除吗？')" href="#" target="_self">删除</a></td>
        </tr>
        <tr>
          <td><input id="id[]" value="1501092" type="checkbox" name="id[]" /></td>
          <td>15</td>
          <td><a href="#" target="_blank">CPAPP测试列表数据</a></td>
          <td><a href="#" target="_self">否</a> </td>
          <td><a href="#" target="_self">正常</a> </td>
          <td>3</td>
          <td>2012-07-11</td>
          <td><a href="#" target="_self">修改</a> | <a onclick="return confirm('确定要删除吗？')" href="#" target="_self">删除</a></td>
        </tr>
        <tr>
          <td><input id="id[]" value="1501092" type="checkbox" name="id[]" /></td>
          <td>15</td>
          <td><a href="#" target="_blank">CPAPP测试列表数据</a></td>
          <td><a href="#" target="_self">否</a> </td>
          <td><a href="#" target="_self">正常</a> </td>
          <td>3</td>
          <td>2012-07-11</td>
          <td><a href="#" target="_self">修改</a> | <a onclick="return confirm('确定要删除吗？')" href="#" target="_self">删除</a></td>
        </tr>
        <tr>
          <td><input id="id[]" value="1501092" type="checkbox" name="id[]" /></td>
          <td>15</td>
          <td><a href="#" target="_blank">CPAPP测试列表数据</a></td>
          <td><a href="#" target="_self">否</a> </td>
          <td><a href="#" target="_self">正常</a> </td>
          <td>3</td>
          <td>2012-07-11</td>
          <td><a href="#" target="_self">修改</a> | <a onclick="return confirm('确定要删除吗？')" href="#" target="_self">删除</a></td>
        </tr>
        <tr>
          <td><input id="id[]" value="1501092" type="checkbox" name="id[]" /></td>
          <td>15</td>
          <td><a href="#" target="_blank">CPAPP测试列表数据</a></td>
          <td><a href="#" target="_self">否</a> </td>
          <td><a href="#" target="_self">正常</a> </td>
          <td>3</td>
          <td>2012-07-11</td>
          <td><a href="#" target="_self">修改</a> | <a onclick="return confirm('确定要删除吗？')" href="#" target="_self">删除</a></td>
        </tr>
        <tr>
          <td><input id="id[]" value="1501092" type="checkbox" name="id[]" /></td>
          <td>15</td>
          <td><a href="#" target="_blank">CPAPP测试列表数据</a></td>
          <td><a href="#" target="_self">否</a> </td>
          <td><a href="#" target="_self">正常</a> </td>
          <td>3</td>
          <td>2012-07-11</td>
          <td><a href="#" target="_self">修改</a> | <a onclick="return confirm('确定要删除吗？')" href="#" target="_self">删除</a></td>
        </tr>
        <tr>
          <td><input id="id[]" value="1501092" type="checkbox" name="id[]" /></td>
          <td>15</td>
          <td><a href="#" target="_blank">CPAPP测试列表数据</a></td>
          <td><a href="#" target="_self">否</a> </td>
          <td><a href="#" target="_self">正常</a> </td>
          <td>3</td>
          <td>2012-07-11</td>
          <td><a href="#" target="_self">修改</a> | <a onclick="return confirm('确定要删除吗？')" href="#" target="_self">删除</a></td>
        </tr>
        <tr>
          <td><input id="id[]" value="1501092" type="checkbox" name="id[]" /></td>
          <td>15</td>
          <td><a href="#" target="_blank">CPAPP测试列表数据</a></td>
          <td><a href="#" target="_self">否</a> </td>
          <td><a href="#" target="_self">正常</a> </td>
          <td>3</td>
          <td>2012-07-11</td>
          <td><a href="#" target="_self">修改</a> | <a onclick="return confirm('确定要删除吗？')" href="#" target="_self">删除</a></td>
        </tr>
        <tr>
          <td><input id="id[]" value="1501092" type="checkbox" name="id[]" /></td>
          <td>15</td>
          <td><a href="#" target="_blank">CPAPP测试列表数据</a></td>
          <td><a href="#" target="_self">否</a> </td>
          <td><a href="#" target="_self">正常</a> </td>
          <td>3</td>
          <td>2012-07-11</td>
          <td><a href="#" target="_self">修改</a> | <a onclick="return confirm('确定要删除吗？')" href="#" target="_self">删除</a></td>
        </tr>
      </table>
    </div>
    <div class="pages"><span class="current">1</span> </div>
    <div style="TEXT-ALIGN: left" class="btn">操作：
      <select id="mode" onchange="selectchange(this.value)" name="mode">
        <option selected="selected" value="1">彻底删除</option>
        <option value="2">批量锁定</option>
        <option value="3">批量解锁</option>
        <option value="4">转移栏目</option>
      </select>
      <span id="schange"><span style="DISPLAY: none" id="4">
      <select name="cid">
        <option value="2">饮酒文化</option>
        <option selected="selected" value="1">企业要闻</option>
      </select>
      </span></span><span class="btn2">
      <input class="listbox button" onclick="return confirm('确定操作吗？')" value="确 定" type="submit" name="button" />
      </span></div>
  </form>
</div>
</body>
</html>
