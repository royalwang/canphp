<DIV class=admin_title>
  <h2>添加文章</h2>
  <A class=right href="javascript:history.back(-1)">返回上一页</A>
 </DIV>
  <FORM encType="multipart/form-data" onSubmit="return check_form(document.add);" method="post" action="#">
      <UL class=tab>
        <LI id=one1 class="hover" onClick="setTab('one',1,2)">基本信息</LI>
        <LI id=one2 onClick="setTab('one',2,2)">附加设置</LI>
      </UL>
      <DIV id=con_one_1 class="form_box">
        <TABLE>
            <TR>
              <TH>栏目：</TH>
              <TD>
			  <SELECT name=cid reg="^.+$">
                  <OPTION selected value="">选择分类</OPTION>
                  <OPTION value=1>企业要闻</OPTION>
                  <OPTION value=2>饮酒文化</OPTION>
                </SELECT>
               </TD>
            </TR>
            <TR>
              <TH>属性：</TH>
              <TD>
			   <SELECT id=recommend name=recommend>
                  <OPTION selected value=1>普通内容</OPTION>
                  <OPTION style="COLOR: #090" value=2>热门推荐</OPTION>
                  <OPTION style="COLOR: #f63" value=3>重点推荐</OPTION>
                </SELECT>
				
                <SELECT id=top name=top>
                  <OPTION selected value=2>不置顶</OPTION>
                  <OPTION style="COLOR: #f63" value=1>置顶</OPTION>
                </SELECT>
				
                <SELECT id=state name=state>
                  <OPTION selected value=1>正常显示</OPTION>
                  <OPTION style="COLOR: #f63" value=2>关闭显示</OPTION>
                </SELECT>
                常规属性,如果不是特殊内容可以不用设置</TD>
            </TR>
            <TR>
              <TH>标题：</TH>
              <TD>
                  <INPUT class="input ruler" type=text name=title>必须填写</TD>
            </TR>
            <TR>
              <TH>缩图：</TH>
              <TD>
                  <INPUT id=thumb class="input w200" type=text name=thumb>
                  <A class=button onClick="#" href="javascript:void(0);">上传缩图</A>
				  </TD>
            </TR>
            <TR>
              <TH>内容</TH>
              <TD>
			  	<TEXTAREA id=editor name="content"></TEXTAREA>
              </TD>
            </TR>
        </TABLE>
      </DIV>
      <DIV id=con_one_2 class="form_box" style="display:none">
        <TABLE width="100%">
            <TR>
              <TH>关联内容：</TH>
              <TD>
                  <INPUT class="input w400" type=text name=reid>
                  <A class=button onClick="" href="javascript:void(0);">选择相关</A></TD>
            </TR>
            <TR>
              <TH>SEO关键字：</TH>
              <TD>
			  <INPUT id=keywords class="input w400" type=text name=keywords>
                请使用全角逗号分隔。</TD>
            </TR>
            <TR>
              <TH>SEO描述： </TH>
              <TD>
			  	<TEXTAREA class="textarea w400 h80" onkeyup=value=value.substr(0,110); name=intro></TEXTAREA>
                长度不能超过110个字符，留空将自动提取文章前110个字符 </TD>
            </TR>
            <TR>
              <TH>发布时间：</TH>
              <TD colSpan=3><INPUT class="input date" name=create_time></TD>
            </TR>
            <TR>
              <TH>点击数：</TH>
              <TD colSpan=3>
			  	<INPUT id=clickcount class="input w150" value=0 type=text name=clickcount>
              </TD>
            </TR>
            <TR>
              <TH>内容页模版：</TH>
              <TD><INPUT class="input w150" type=text name=temp>
                不带文件后缀，留空为默认模板 
                格式为show_</TD>
            </TR>
        </TABLE>
      </DIV>
      <DIV class=btn>
        <INPUT type=hidden name=id>
        <INPUT value=yes type=hidden name=do>
        <INPUT class="button" value="确定" type=submit name=dosubmit>
        <INPUT class="button" value="重置" type=reset name=reset>
      </DIV>
  </FORM>
