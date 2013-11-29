<div class="right">

<?php 		
$manual_name=array(  'rumenzhiyin'=>'入门指引',
						'hexinjichu'=>'核心基础',
						'kuozhanku'=>'扩展库',
						'canshupeizhi'=>'参数配置',
						'fulu'=>'附录');

foreach($manual_menu as $key=>$list){?>
<div class="boxone">
<div class="title"  onclick="show('{$key}')">{$manual_name[$key]}</div>
<div class="r_txt" id="{$key}">
  <UL>
	<?php
		  if(!empty($list)) foreach($list as $url=>$title){
	?>
     	 <li> <a href="__URL__/{$url}" target="_self" title="{$title}"><?php echo str_replace(array('CanPHP开发手册--','CanPHP开发手册——'),'',$title);?></a></li>
 	<?php }?>
   </UL></div>
</div>
<?php }?>
</div>
<script type="text/javascript">
var manual=Array('rumenzhiyin','hexinjichu','kuozhanku','canshupeizhi','fulu');
for(var i in manual)
{
	var id=manual[i];
	if($.cookie(id)==1)
	{
		$("#"+id).hide();
	}
}
function show(id)
{
	if($.cookie(id)==1)
	{
		$("#"+id).show();
		$.cookie(id,0);
	}
	else
	{
		$("#"+id).hide();
		$.cookie(id,1);
	}
}
</script>