<?php if (!defined('CANPHP')) exit;?><DIV class=admin_title>
  <h2>文章栏目</h2>
  <A href="#">文章栏目管理</A>
  <A class=right href="javascript:history.back(-1)">返回上一页</A>
  </DIV>
  <div class="admin_menu">
<a href="/apps/admins.php/article/add">添加文章</a>
<a href="javascript:void(0);" onclick="searchUser();" class="search_action">搜索信息</a>
<a href="javascript:void(0);" onclick="self.location=''" class="right">全部信息</a> 
<a href="javascript:void(0);" onclick="self.location='?status=1'" class="right">已审核</a> 
<a href="javascript:void(0);" onclick="self.location='?status=0'" class="right">未审核</a> 
<a href="javascript:void(0);" onclick="window.art.dialog({id:'add',iframe:'ss', title:'添加站点', width:'600', height:'380', lock:true})" class="right">按地区查找</a>
</div>

  <FORM id="search" method="get" action="#">
    <DIV id=search_div>
	  &nbsp;&nbsp;栏目:
      <SELECT id="s_cid" class="searchauto" name="s_cid">
        <OPTION selected value="">选择分类</OPTION>
        <OPTION value="2">饮酒文化</OPTION>
        <OPTION value="1">企业要闻</OPTION>
      </SELECT>
	  
      &nbsp;&nbsp;推荐类型:
      <SELECT id="s_recommend" class="searchauto" name="s_recommend">
        <OPTION selected value="">不限</OPTION>
        <OPTION value=1>否内容</OPTION>
        <OPTION value=2>热门推荐</OPTION>
        <OPTION value=3>重点推荐</OPTION>
      </SELECT>
	  
      &nbsp;&nbsp;否:
      <SELECT id="s_top" class="searchauto" name="s_top">
        <OPTION selected value="">不限</OPTION>
        <OPTION value=1>否</OPTION>
        <OPTION value=2>不否</OPTION>
      </SELECT>
	  
      &nbsp;&nbsp;状态:
      <SELECT id="s_state" class="searchauto" name="s_state">
        <OPTION selected value="">不限</OPTION>
        <OPTION value=1>正常</OPTION>
        <OPTION value=2>锁定</OPTION>
      </SELECT>
	  
      &nbsp;&nbsp;关键字:
      <INPUT id=s_keyword class="input w150" type=text name=s_keyword>
      <INPUT class="button" value="搜 索" type=submit>
    </DIV>
  </FORM>
  <FORM method="post" action="" target="_self">
  <div class="list_b">
    <TABLE width="100%">
        <TR>
          <TH width=20><INPUT onclick=checkAll(this) type=checkbox></TH>
          <TH width=60>ID</TH>
          <TH>文章标题</TH>
          <TH width=40>置顶</TH>
          <TH width=40>状态</TH>
          <TH width=60>浏览量</TH>
          <TH width=80>发布时间</TH>
          <TH width=80>管理操作</TH>
        </TR>
        <TR>
          <TD><INPUT id=id[] value=1501092 type=checkbox name=id[]></TD>
          <TD>15</TD>
          <TD><A href="#" target="_blank">CPAPP测试列表数据</A></TD>
          <TD><A href="#" target=_self>否</A> </TD>
          <TD><A href="#" target=_self>正常</A> </TD>
          <TD>3</TD>
          <TD>2012-07-11</TD>
          <TD><A href="#" target=_self>修改</A> | <A onClick="return confirm('确定要删除吗？')" href="#" target=_self>删除</A></TD>
        </TR>
        
        
		<TR>
          <TD><INPUT id=id[] value=1501092 type=checkbox name=id[]></TD>
          <TD>15</TD>
          <TD><A href="#" target="_blank">CPAPP测试列表数据</A></TD>
          <TD><A href="#" target=_self><font color="red">是</font></A> </TD>
          <TD><A href="#" target=_self><font color="red">锁定</font></A> </TD>
          <TD>3</TD>
          <TD>2012-07-11</TD>
          <TD><A href="#" target=_self>修改</A> | <A onClick="return confirm('确定要删除吗？')" href="#" target=_self>删除</A></TD>
        </TR>
        
        <TR>
          <TD><INPUT id=id[] value=1501092 type=checkbox name=id[]></TD>
          <TD>15</TD>
          <TD><A href="#" target="_blank">CPAPP测试列表数据</A></TD>
          <TD><A href="#" target=_self>否</A> </TD>
          <TD><A href="#" target=_self>正常</A> </TD>
          <TD>3</TD>
          <TD>2012-07-11</TD>
          <TD><A href="#" target=_self>修改</A> | <A onClick="return confirm('确定要删除吗？')" href="#" target=_self>删除</A></TD>
        </TR>
        
        <TR>
          <TD><INPUT id=id[] value=1501092 type=checkbox name=id[]></TD>
          <TD>15</TD>
          <TD><A href="#" target="_blank">CPAPP测试列表数据</A></TD>
          <TD><A href="#" target=_self>否</A> </TD>
          <TD><A href="#" target=_self>正常</A> </TD>
          <TD>3</TD>
          <TD>2012-07-11</TD>
          <TD><A href="#" target=_self>修改</A> | <A onClick="return confirm('确定要删除吗？')" href="#" target=_self>删除</A></TD>
        </TR>
        
        <TR>
          <TD><INPUT id=id[] value=1501092 type=checkbox name=id[]></TD>
          <TD>15</TD>
          <TD><A href="#" target="_blank">CPAPP测试列表数据</A></TD>
          <TD><A href="#" target=_self>否</A> </TD>
          <TD><A href="#" target=_self>正常</A> </TD>
          <TD>3</TD>
          <TD>2012-07-11</TD>
          <TD><A href="#" target=_self>修改</A> | <A onClick="return confirm('确定要删除吗？')" href="#" target=_self>删除</A></TD>
        </TR>
        
        <TR>
          <TD><INPUT id=id[] value=1501092 type=checkbox name=id[]></TD>
          <TD>15</TD>
          <TD><A href="#" target="_blank">CPAPP测试列表数据</A></TD>
          <TD><A href="#" target=_self>否</A> </TD>
          <TD><A href="#" target=_self>正常</A> </TD>
          <TD>3</TD>
          <TD>2012-07-11</TD>
          <TD><A href="#" target=_self>修改</A> | <A onClick="return confirm('确定要删除吗？')" href="#" target=_self>删除</A></TD>
        </TR>
        
        <TR>
          <TD><INPUT id=id[] value=1501092 type=checkbox name=id[]></TD>
          <TD>15</TD>
          <TD><A href="#" target="_blank">CPAPP测试列表数据</A></TD>
          <TD><A href="#" target=_self>否</A> </TD>
          <TD><A href="#" target=_self>正常</A> </TD>
          <TD>3</TD>
          <TD>2012-07-11</TD>
          <TD><A href="#" target=_self>修改</A> | <A onClick="return confirm('确定要删除吗？')" href="#" target=_self>删除</A></TD>
        </TR>
        
        <TR>
          <TD><INPUT id=id[] value=1501092 type=checkbox name=id[]></TD>
          <TD>15</TD>
          <TD><A href="#" target="_blank">CPAPP测试列表数据</A></TD>
          <TD><A href="#" target=_self>否</A> </TD>
          <TD><A href="#" target=_self>正常</A> </TD>
          <TD>3</TD>
          <TD>2012-07-11</TD>
          <TD><A href="#" target=_self>修改</A> | <A onClick="return confirm('确定要删除吗？')" href="#" target=_self>删除</A></TD>
        </TR>
        
        <TR>
          <TD><INPUT id=id[] value=1501092 type=checkbox name=id[]></TD>
          <TD>15</TD>
          <TD><A href="#" target="_blank">CPAPP测试列表数据</A></TD>
          <TD><A href="#" target=_self>否</A> </TD>
          <TD><A href="#" target=_self>正常</A> </TD>
          <TD>3</TD>
          <TD>2012-07-11</TD>
          <TD><A href="#" target=_self>修改</A> | <A onClick="return confirm('确定要删除吗？')" href="#" target=_self>删除</A></TD>
        </TR>
        
        <TR>
          <TD><INPUT id=id[] value=1501092 type=checkbox name=id[]></TD>
          <TD>15</TD>
          <TD><A href="#" target="_blank">CPAPP测试列表数据</A></TD>
          <TD><A href="#" target=_self>否</A> </TD>
          <TD><A href="#" target=_self>正常</A> </TD>
          <TD>3</TD>
          <TD>2012-07-11</TD>
          <TD><A href="#" target=_self>修改</A> | <A onClick="return confirm('确定要删除吗？')" href="#" target=_self>删除</A></TD>
        </TR>
        
        <TR>
          <TD><INPUT id=id[] value=1501092 type=checkbox name=id[]></TD>
          <TD>15</TD>
          <TD><A href="#" target="_blank">CPAPP测试列表数据</A></TD>
          <TD><A href="#" target=_self>否</A> </TD>
          <TD><A href="#" target=_self>正常</A> </TD>
          <TD>3</TD>
          <TD>2012-07-11</TD>
          <TD><A href="#" target=_self>修改</A> | <A onClick="return confirm('确定要删除吗？')" href="#" target=_self>删除</A></TD>
        </TR>
        
    </TABLE>
	</DIV>
    <DIV class=list_btn>操作：
      <SELECT id=mode onchange=selectchange(this.value) name=mode>
        <OPTION selected value=1>彻底删除</OPTION>
        <OPTION value=2>批量锁定</OPTION>
        <OPTION value=3>批量解锁</OPTION>
        <OPTION value=4>转移栏目</OPTION>
      </SELECT>
      <SPAN id=4>
      <SELECT name=cid>
        <OPTION value=2>转移栏目演示1</OPTION>
        <OPTION selected value=1>转移栏目演示2</OPTION>
      </SELECT>
      </SPAN>
    <INPUT class="button" onClick="return confirm('确定操作吗？')" value="确 定" type=submit>
      </DIV>
  </FORM>