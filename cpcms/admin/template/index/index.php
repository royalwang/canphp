<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="__PUBLIC__/css/index.css" type="text/css" />
<script src="__PUBLIC__/js/menu.js" type="text/javascript"></script>
<title>网站管理后台</title>
<dl id="header">
<dt>&nbsp;</dt>
	<dd>
		<li>当前用户：admin&nbsp;&nbsp;</li>
		<li>所属用户组：管理员&nbsp;&nbsp;&nbsp;&nbsp;</li>
		<li><a href="__ROOT__/../" target="_blank">[网站首页]</a>&nbsp;&nbsp;</li>
		<li><a href="__APP__/index/logout" target="_self">[退出系统]</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</li>
	</dd>
</dl>
<div id="contents">
	<div class="left">
		<ul class="bigbtu">
			<li id="now01"><a href="__APP__/article/add.html" onClick="go_cmdurl('发布文章',this);border_left('TabPage2','left_tab2');" title="发布文章" target="content3">发布文章</a></li>
			<li id="now02"><a href="__APP__/case/add.html" onClick="go_cmdurl('发布图库',this);border_left('TabPage2','left_tab3');" title="发布图库" target="content3">发布图库</a></li>
		</ul>
		<div class="menu_top"></div>
		<div class="menu" id="TabPage3">
			<ul id="TabPage2">
				<li id="left_tab1" class="Selected" onClick="javascript:border_left('TabPage2','left_tab1');" title="常用">常用</li>
				<li id="left_tab2" onClick="javascript:border_left('TabPage2','left_tab2');" title="文章系统">文章</li>		
				<li id="left_tab3" onClick="javascript:border_left('TabPage2','left_tab3');" title="案例">案例</li>
				<li id="left_tab5" onClick="javascript:border_left('TabPage2','left_tab5');" title="模板">模板</li>
				<li id="left_tab4" onClick="javascript:border_left('TabPage2','left_tab4');" title="会员管理">会员</li>
				<li id="left_tab7" onClick="javascript:border_left('TabPage2','left_tab7');" title="系统管理">系统</li>
			</ul>
			<div id="left_menu_cnt">
				<ul id="dleft_tab1">
				<li id="now21"><a href="__APP__/article/add.html" onClick="go_cmdurl('发布文章',this)" target="content3" title="发布文章">发布文章</a></li>
				<li id="now23"><a href="__APP__/article/index.html" onClick="go_cmdurl('文章管理',this)" target="content3" title="文章管理">文章管理</a></li>
				<li id="now24"><a href="__APP__/category/index.html" onClick="go_cmdurl('文章栏目',this)" target="content3" title="文章栏目">文章栏目</a></li>
				<li id="now24"><a href="__APP__/tpl/index.html" onClick="go_cmdurl('模板管理',this)" target="content3" title="模板管理">模板管理</a></li>
				<li id="now24"><a href="__APP__/case/index.html" onClick="go_cmdurl('案例管理',this)" target="content3" title="案例管理">案例管理</a></li>
				<!--
					<li id="now11" class="Selected"><a href="http://www.865171.cn/admin-templates/" onClick="go_cmdurl('日志管理',this);" target="content3" title="日志管理">日志管理</a></li>
					<li id="now12"><a href="http://www.865171.cn/admin-templates/" onClick="go_cmdurl('浏览相册',this);" target="content3" title="浏览相册">浏览相册</a></li>
					<li id="now13"><a href="http://www.865171.cn/admin-templates/" onClick="go_cmdurl('日志评论',this);" target="content3" title="日志评论">日志评论</a></li>
					<li id="now14"><a href="http://www.865171.cn/admin-templates/" onClick="go_cmdurl('访客留言',this)" target="content3" title="访客留言">访客留言</a></li>
					<li id="now1a"><a href="http://www.865171.cn/admin-templates/" onClick="go_cmdurl('添加订阅',this);" target="content3" title="添加订阅">添加订阅</a></li>
					<li id="now1b"><a href="http://www.865171.cn/admin-templates/" onClick="go_cmdurl('邀请码',this)" target="content3" title="可用邀请码">可用邀请码</a></li>
					<li id="now1c"><a href="http://www.865171.cn/admin-templates/" onClick="go_cmdurl('我的好友',this)" target="content3" title="我的好友">我的好友</a></li>
					<li id="now1d"><a href="http://www.865171.cn/admin-templates/" onClick="go_cmdurl('更新数据',this)" target="content3" title="更新数据">更新数据</a></li>
					<li id="now1e"><a href="http://www.865171.cn/admin-templates/" onClick="go_cmdurl('博客设置',this)" target="content3" title="博客设置">博客设置</a></li>
					
					<li id="now19"><a href="http://www.865171.cn/admin-templates/"  target="_blank" title="进入内容管理操作界面"><font color=red>内容管理员</font></a></li>							
					-->						
				</ul>
				<ul id="dleft_tab2" style="display:none;">
					<li id="now21"><a href="__APP__/article/add.html" onClick="go_cmdurl('发布文章',this)" target="content3" title="发布文章">发布文章</a></li>
					<li id="now23"><a href="__APP__/article/index.html" onClick="go_cmdurl('文章管理',this)" target="content3" title="文章管理">文章管理</a></li>
					<!--
					<li id="now22"><a href="__APP__/article/index/status/1" onClick="go_cmdurl('草稿箱',this)" target="content3" title="草稿箱">草稿箱<span id="sdraft_num"></span></a></li>
					<li id="now28"><a href="__APP__/article/index/status/2" onClick="go_cmdurl('回收站',this)" target="content3" title="回收站">回收站<span id="del_num"></span></a></li>
					-->
					<li id="now24"><a href="__APP__/category/index.html" onClick="go_cmdurl('文章栏目',this)" target="content3" title="文章栏目">文章栏目</a></li>
					<!--<li id="now26"><a href="__APP__/Comment/index.html" onClick="go_cmdurl('评论管理',this)" target="content3" title="评论管理">评论管理</a></li>-->
					<!--
					<li id="now25"><a href="http://www.865171.cn/admin-templates/" onClick="go_cmdurl('备份日志',this)" target="content3" title="备份日志">备份日志</a></li>
					<li id="now26"><a href="http://www.865171.cn/admin-templates/" onClick="go_cmdurl('评论管理',this)" target="content3" title="评论管理">评论管理</a></li>
					<li id="now27"><a href="http://www.865171.cn/admin-templates/" onClick="go_cmdurl('留言管理',this)" target="content3" title="留言管理">留言管理</a></li>
					<li id="now29"><a href="http://www.865171.cn/admin-templates/" onClick="go_cmdurl('引用通告',this)" target="content3" title="引用通告">引用通告</a></li>           -->
				
				</ul>
				<ul id="dleft_tab3" style="display:none;">
				
					<li id="now31"><a href="__APP__/case/add.html" onClick="go_cmdurl('发布案例',this)" target="content3" title="发布案例">发布案例</a></li>
					<li id="now32"><a href="__APP__/case/index.html" onClick="go_cmdurl('案例管理',this)" target="content3" title="案例管理">案例管理</a></li>
					<!--<li id="now33"><a href="http://www.865171.cn/admin-templates/" onClick="go_cmdurl('相册分类',this)" target="content3" title="相册分类">相册分类</a></li>
					<li id="now34"><a href="http://www.865171.cn/admin-templates/" onClick="go_cmdurl('大头贴',this)" target="content3" title="大头贴">大头贴</a></li>
					-->

				</ul>
				
				<ul id="dleft_tab4" style="display:none;">
				<!--
					<li id="now41"><a href="http://www.865171.cn/admin-templates/" onClick="go_cmdurl('所有文件',this)" target="content3" title="所有文件">所有文件</a></li>
					<li id="now42"><a href="http://www.865171.cn/admin-templates/" onClick="go_cmdurl('图片文件',this)" target="content3" title="图片文件">图片文件</a></li>
					<li id="now43"><a href="http://www.865171.cn/admin-templates/" onClick="go_cmdurl('压缩文件',this)" target="content3" title="压缩文件">压缩文件</a></li>
					<li id="now44"><a href="http://www.865171.cn/admin-templates/" onClick="go_cmdurl('文档文件',this)" target="content3" title="文档文件">文档文件</a></li>
					-->
					<li>开发中...</li>	
				</ul>
				<ul id="dleft_tab5" style="display:none;">
				
					<li id="now51"><a href="__APP__/tpl/index.html" onClick="go_cmdurl('模板管理',this)" target="content3" title="模板管理">模板管理</a></li>
					<!--<li id="now52"><a href="http://www.865171.cn/admin-templates/" onClick="go_cmdurl('改主模板',this)" target="content3" title="改主模板">改主模板</a></li>
					<li id="now53"><a href="http://www.865171.cn/admin-templates/" onClick="go_cmdurl('改副模板',this)" target="content3" title="改副模板">改副模板</a></li>
					<li id="now54"><a href="http://www.865171.cn/admin-templates/" onClick="go_cmdurl('备份模板',this)" target="content3" title="备份模板">备份模板</a></li>          -->
			
				</ul>
				<ul id="dleft_tab7" style="display:none;">
				<li  id="now53"><a href="__APP__/system/clearCache.html" onClick="go_cmdurl('清空缓存',this)" target="content3" title="清空缓存">清空缓存</a></li>	
				</ul>
			</div>
			<div class="clear"></div>
		</div>
		<div class="menu_end"></div>
	</div>
	<div class="right">
	   <ul id="TabPage1">
			<li id="Tab1" class="Selected" onClick="javascript:switchTab('TabPage1','Tab1');" title="后台首页">后台首页</li>
			<!--<li id="Tab2" onClick="javascript:switchTab('TabPage1','Tab2');">标签</li>-->
			<li id="Tab3" onClick="javascript:switchTab('TabPage1','Tab3');"><span id="dnow99" style="display:block">发布文章</span></li>
	   </ul>
		<div id="cnt">
		  <div id="dTab1" class="Box">
		  <iframe src="__URL__/welcome" name="content1" frameborder="0" scrolling="auto"></iframe>
		  </div>
		  <!--
			<div id="dTab2" class="HackBox Box">
			<iframe src="第二调用地址" name="content2" frameborder="0" scrolling="auto"></iframe>
			</div>
			-->
			<div id="dTab3" class="HackBox Box">
			<iframe src="__APP__/article/add.html" name="content3" id="content3" frameborder="0" scrolling="auto"></iframe>
			</div>
		</div>
	</div>
	<div class="clear"></div>
</div>
</body>
</html>