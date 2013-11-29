<div class="right">
<div class="kjdownload">
<div class="title">框架下载</div>
<div style="line-height:22px; padding:15px;"><strong>简介：</strong>CanPHP框架(简称CP)，是一个简单、自由、实用、高效的php框架。
<br>
<strong>最新版本： </strong>canphp1.5
<a href="__APP__/download.html"><img src="__PUBLIC__/images/xz.png"></a>
</div>
</div>

<div class="boxone">
<div class="title">最新文章</div>
<div class="r_txt">
 <UL>
	<?php $list=module('article')->getList('',10,'id desc'); 
		  if(!empty($list)) foreach($list as $vo){
	?>
     	 <li> <a href="__APP__/article/show-{$vo['id']}.html" target="_blank" title="{$vo['title']}">{$vo['title']}</a></li>
 	<?php }?>
   </UL></div>
</div>


<div class="boxone">
<div class="title">热门文章</div>
<div class="r_txt">
  <UL>
	<?php $list=module('article')->getList('',10,'views desc'); 
		  if(!empty($list)) foreach($list as $vo){
	?>
     	 <li> <a href="__APP__/article/show-{$vo['id']}.html" target="_blank" title="{$vo['title']}">{$vo['title']}</a></li>
 	<?php }?>
   </UL></div>
</div>

</div>