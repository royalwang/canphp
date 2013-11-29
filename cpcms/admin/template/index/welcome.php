<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<script type="text/javascript" src="__PUBLIC__/js/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/ui.tab.js"></script>
<link href="__PUBLIC__/css/welcome.css" rel="stylesheet" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript">
$(document).ready(function(){
//alert("hello");
	var tab1 = new $.fn.tab({
		tabList:"#tabinfo .ui-tab-container .ui-tab-list li",
		contentList:"#tabinfo .ui-tab-container .ui-tab-content",
		eventType:"click",
		showType:"fade"
	});
});	

</script>
<title>欢迎页面</title>
</head>
<body>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="17" valign="top" background="__PUBLIC__/images/welcome/mail_leftbg.gif"><img src="__PUBLIC__/images/welcome/left-top-right.gif" width="17" height="29" /></td>
    <td valign="top" background="__PUBLIC__/images/welcome/content-bg.gif"><table width="100%" height="31" border="0" cellpadding="0" cellspacing="0" class="left_topbg" id="table2">
        <tr>
          <td height="31"><div class="titlebt">欢迎界面</div></td>
        </tr>
      </table></td>
    <td width="16" valign="top" background="__PUBLIC__/images/welcome/mail_rightbg.gif"><img src="__PUBLIC__/images/welcome/nav-right-bg.gif" width="16" height="29" /></td>
  </tr>
  <tr>
    <td valign="middle" background="__PUBLIC__/images/welcome/mail_leftbg.gif">&nbsp;</td>
    <td valign="top" bgcolor="#F7F8F9"><div id="welcomeContent">
      <div class="leftCol">
        <div class="welcomeinfo">
          <h2>{$user_info['username']}, 欢迎您登录后台管理！</h2>
          <p><img src="__PUBLIC__/images/welcome/ts.gif" width="16" height="16">提示：
            欢迎使用本系统，如果您有任何疑问请与管理员或技术组联系！</p>
          <p>本系统功能强大，操作简单，后台操作易如反掌，模块清晰合理，是你居家度日的必备良药，  此程序是您打家劫舍的首选方案！　</p>
        </div>
        <div id="tabinfo">
          <div class="ui-tab-container">
            <ul class="clearfix ui-tab-list">
              <li class="ui-tab-active">用户信息</li>
              <li>统计信息</li>
              <li>系统参数</li>
              <li>版权说明</li>
            </ul>
            <div class="ui-tab-bd">
              <div class="ui-tab-content">
                <TABLE width="99%" height="133" border=0  cellPadding=0 cellSpacing=0>
                  <TBODY>
                    <TR>
                      <TD height="5" colspan="3"></TD>
                    </TR>
                    <TR>
                      <TD width="4%" height="28" background="__PUBLIC__/images/welcome/news-title-bg.gif"></TD>
                                              <TD height="25" colspan="2" background="__PUBLIC__/images/welcome/news-title-bg.gif" class="text">亲爱的<span class="hightling">{$user_info['username']} : </span><b></b></TD>
                        </TR>
                        <TR>
                          <TD bgcolor="#FAFBFC">&nbsp;</TD>
                          <TD width="42%" height="25" bgcolor="#FAFBFC"><span class="text">您的真实姓名： </span> <span class="hightling">{$user_info['realname']} </span></TD>
                     <TD width="54%" height="25" bgcolor="#FAFBFC"><span class="text">您的注册时间： </span></TD>
                        </TR>
                        <TR>
                          <TD bgcolor="#FAFBFC">&nbsp;</TD>
                          <TD height="25" bgcolor="#FAFBFC"><span class="text">您的登录次数： </span> <span class="hightling">{$user_info['login_total']}</span></TD>
                          <TD height="25" bgcolor="#FAFBFC"><span class="text">你的用户组别： </span> <span class="hightling">管理员 </span></TD>
                        </TR>
                        <TR>
                          <TD bgcolor="#FAFBFC">&nbsp;</TD>

                          <TD height="25" bgcolor="#FAFBFC"><span class="text">本次登录IP： </span> <span class="hightling">{$user_info['login_ip']}</span></TD>                          <TD height="25" bgcolor="#FAFBFC"><span class="text">本次登录时间： </span></TD>
                        </TR>
                        <TR>
                          <TD bgcolor="#FAFBFC">&nbsp;</TD>
                       
                          <TD height="25" bgcolor="#FAFBFC"><span class="text">上次登录IP： </span> <span class="hightling">{$user_info['last_ip']}</span></TD>   <TD height="25" bgcolor="#FAFBFC"><span class="text">上次登录时间： </span></TD>
                    </TR>
                    <TR>
                      <TD height="5" colspan="3"></TD>
                    </TR>
                  </TBODY>
                </TABLE>
              </div>
              <div class="ui-tab-content" style="display:none">
                <TABLE width="99%" height="133" border=0 cellPadding=0 cellSpacing=0>
                  <TBODY>
                    <TR>
                      <TD height="5" colspan="3"></TD>
                    </TR>
                    <TR>
                      <TD width="4%" height="28" background="__PUBLIC__/images/welcome/news-title-bg.gif"></TD>
                      <TD colspan="2" background="__PUBLIC__/images/welcome/news-title-bg.gif" class="text">现有会员：名&nbsp;&nbsp; 其中：                                名&nbsp;&nbsp;&nbsp;&nbsp;名&nbsp;&nbsp;&nbsp;&nbsp;名</TD>
                    </TR>
                    <TR>
                      <TD bgcolor="#FAFBFC">&nbsp;</TD>
                      <TD width="42%" height="25" bgcolor="#FAFBFC"><span class="text">本站现有频道信息： </span><span class="text">条</span></TD>
                      <TD width="54%" bgcolor="#FAFBFC"><span class="text">本站现有系列信息： </span><span class="text">条</span></TD>
                    </TR>
                    <TR>
                      <TD bgcolor="#FAFBFC">&nbsp;</TD>
                      <TD height="25" bgcolor="#FAFBFC"><span class="text">本站现有商家展示： </span><span class="text">个</span></TD>
                      <TD height="25" bgcolor="#FAFBFC"><span class="text">本站现有网上商城： </span><span class="text">个</span></TD>
                    </TR>
                    <TR>
                      <TD bgcolor="#FAFBFC">&nbsp;</TD>
                      <TD height="25" bgcolor="#FAFBFC"><span class="text">本站现有网上名片： </span><span class="text">个</span></TD>
                      <TD height="25" bgcolor="#FAFBFC"><span class="text">本站现有市场联盟： </span><span class="text">个</span></TD>
                    </TR>
                    <TR>
                      <TD bgcolor="#FAFBFC">&nbsp;</TD>
                      <TD height="25" bgcolor="#FAFBFC"><span class="text">本站现有市场资讯： </span><span class="text">条</span></TD>
                      <TD height="25" bgcolor="#FAFBFC"><span class="text">本站现有商家商品： </span><span class="text">个</span></TD>
                    </TR>
                    <TR>
                      <TD height="5" colspan="3"></TD>
                    </TR>
                  </TBODY>
                </TABLE>
              </div>
              <div class="ui-tab-content" style="display:none">
                <TABLE width="99%" border=0 cellPadding=0 cellSpacing=0>
                  <TBODY>
                    <TR>
                      <TD colspan="3"></TD>
                    </TR>
                    <TR>
                      <TD height="5" colspan="3"></TD>
                    </TR>
                    <TR>
                      <TD width="4%" height="25" background="__PUBLIC__/images/welcome/news-title-bg.gif"></TD>
                      <TD height="25" colspan="2" background="__PUBLIC__/images/welcome/news-title-bg.gif" class="text"><span class="TableRow2">服务器IP:</span></TD>
                    </TR>
                    <TR>
                      <TD height="25" bgcolor="#FAFBFC">&nbsp;</TD>
                      <TD width="42%" height="25" bgcolor="#FAFBFC"><span class="text">服务器系统：</span></TD>
                      <TD width="54%" height="25" bgcolor="#FAFBFC"><span class="text">脚本解释引擎：</span></TD>
                    </TR>
                    <TR>
                      <TD height="25" bgcolor="#FAFBFC">&nbsp;</TD>
                      <TD height="25" colspan="2" bgcolor="#FAFBFC"><span class="text">站点物理路径：</span></TD>
                    </TR>
                    <TR>
                      <TD height="25" bgcolor="#FAFBFC">&nbsp;</TD>
                      <TD height="25" bgcolor="#FAFBFC"><span class="text">PHP版本：</span></TD>
                      <TD height="25" bgcolor="#FAFBFC"><span class="text">GD库版本：</span><span class="hightlight"></span></TD>
                    </TR>
                    <TR>
                      <TD height="5" colspan="3"></TD>
                    </TR>
                  </TBODY>
                </TABLE>
              </div>
              <div class="ui-tab-content" style="display:none">
                <TABLE width="99%" border=0 cellPadding=0 cellSpacing=0>
                  <TBODY>
                    <TR>
                      <TD colspan="3"></TD>
                    </TR>
                    <TR>
                      <TD height="5" colspan="3"></TD>
                    </TR>
                    <TR>
                      <TD width="4%" background="__PUBLIC__/images/welcome/news-title-bg.gif"></TD>
                      <TD width="91%" background="__PUBLIC__/images/welcome/news-title-bg.gif" class="hightlight">程序说明：</TD>
                      <TD width="5%" background="__PUBLIC__/images/welcome/news-title-bg.gif" class="text">&nbsp;</TD>
                    </TR>
                    <TR>
                      <TD bgcolor="#FAFBFC">&nbsp;</TD>
                      <TD bgcolor="#FAFBFC" class="text"><span class="hightlight">1、</span>本程序最终解释权归单骑闯天下所有 </TD>
                      <TD bgcolor="#FAFBFC" class="text">&nbsp;</TD>
                    </TR>
                    <TR>
                      <TD bgcolor="#FAFBFC">&nbsp;</TD>
                      <TD bgcolor="#FAFBFC" class="text"><span class="hightlight">2、</span>本程序仅提供使用，任何违反互联网规定的行为，自行负责！</TD>
                      <TD bgcolor="#FAFBFC" class="text">&nbsp;</TD>
                    </TR>
                    <TR>
                      <TD bgcolor="#FAFBFC">&nbsp;</TD>
                      <TD bgcolor="#FAFBFC" class="text"><span class="hightlight">3、</span> 支持作者的劳动，请保留版权。</TD>
                      <TD bgcolor="#FAFBFC" class="text">&nbsp;</TD>
                    </TR>
                    <TR>
                      <TD bgcolor="#FAFBFC">&nbsp;</TD>
                      <TD bgcolor="#FAFBFC" class="text"><span class="hightlight">4、</span>程序使用，技术支持请联系单骑闯天下。</TD>
                      <TD bgcolor="#FAFBFC" class="text">&nbsp;</TD>
                    </TR>
                    <TR>
                      <TD height="5" colspan="3"></TD>
                    </TR>
                  </TBODY>
                </TABLE>
              </div>
              <div class="ui-tab-content" style="display:none">
                <TABLE width="99%" border=0 class="infoTab4"  cellPadding=0 cellSpacing=0>
                  <TBODY>
                    <TR>
                      <TD colspan="3"></TD>
                    </TR>
                    <TR>
                      <TD height="5" colspan="3"></TD>
                    </TR>
                    <TR>
                      <TD width="4%" background="__PUBLIC__/images/welcome/news-title-bg.gif"></TD>
                      <TD width="91%" background="__PUBLIC__/images/welcome/news-title-bg.gif" class="hightlight">程序说明：</TD>
                      <TD width="5%" background="__PUBLIC__/images/welcome/news-title-bg.gif" class="text">&nbsp;</TD>
                    </TR>
                    <TR>
                      <TD bgcolor="#FAFBFC">&nbsp;</TD>
                      <TD bgcolor="#FAFBFC" class="text"><span class="hightlight">1、</span>本程序最终解释权归单骑闯天下所有 </TD>
                      <TD bgcolor="#FAFBFC" class="text">&nbsp;</TD>
                    </TR>
                    <TR>
                      <TD bgcolor="#FAFBFC">&nbsp;</TD>
                      <TD bgcolor="#FAFBFC" class="text"><span class="hightlight">2、</span>本程序仅提供使用，任何违反互联网规定的行为，自行负责！</TD>
                      <TD bgcolor="#FAFBFC" class="text">&nbsp;</TD>
                    </TR>
                    <TR>
                      <TD bgcolor="#FAFBFC">&nbsp;</TD>
                      <TD bgcolor="#FAFBFC" class="text"><span class="hightlight">3、</span> 支持作者的劳动，请保留版权。</TD>
                      <TD bgcolor="#FAFBFC" class="text">&nbsp;</TD>
                    </TR>
                    <TR>
                      <TD bgcolor="#FAFBFC">&nbsp;</TD>
                      <TD bgcolor="#FAFBFC" class="text"><span class="hightlight">4、</span>程序使用，技术支持请联系单骑闯天下。</TD>
                      <TD bgcolor="#FAFBFC" class="text">&nbsp;</TD>
                    </TR>
                    <TR>
                      <TD height="5" colspan="3"></TD>
                    </TR>
                  </TBODY>
                </TABLE>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="rightCol">
        <div class="box">
          <h3>更新信息</h3>
          <p>一、没有更新！<br />
            二、不想更新！<br />
            三、更新什么？ <br />
            四、系统优化。</p>
        </div>
        <div class="box">
          <h3>系统说明</h3>
          <p>一、专业的地区级商家门户建站首选方案！<br />
            二、一个帐号，全站通用！<br />
            三、分类信息、商家展示（百业联盟）、网上商城、网上名片（网上黄页）、广告张贴、市场联盟、市场资讯七大栏目完美整合。 <br />
            四、界面设计精美，后台功能强大。傻瓜式后台操作，无需专业的网站制作知识，只要会打字，就会管理维护网站。</p>
        </div>
      </div>
      <div class="footinfo">
        <ul>
          <li class="mail">联系邮箱： </li>
          <li class="tel"> 服务电话： </li>
        </ul>
      </div></td>
    <td background="__PUBLIC__/images/welcome/mail_rightbg.gif">&nbsp;</td>
  </tr>
  <tr>
    <td valign="bottom" background="__PUBLIC__/images/welcome/mail_leftbg.gif"><img src="__PUBLIC__/images/welcome/buttom_left2.gif" width="17" height="17" /></td>
    <td background="__PUBLIC__/images/welcome/buttom_bgs.gif"><img src="__PUBLIC__/images/welcome/buttom_bgs.gif" width="17" height="17"></td>
    <td valign="bottom" background="__PUBLIC__/images/welcome/mail_rightbg.gif"><img src="__PUBLIC__/images/welcome/buttom_right2.gif" width="16" height="17" /></td>
  </tr>
</table>
</body>
</html>
