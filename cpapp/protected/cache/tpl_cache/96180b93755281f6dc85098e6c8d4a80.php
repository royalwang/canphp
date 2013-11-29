<?php if (!defined('CANPHP')) exit;?> <div class="admin_title">
 <h2>应用管理</h2>
 <a href="<?php echo url('index/import');?>">导入应用</a>
 <a href="<?php echo url('index/create');?>">创建应用</a>
 <a class="right" href="javascript:history.back(-1)">返回上一页</a>
 </div>
  <div class="list_b">
    <table>
        <tr>
          <th width="100">应用ID</th>
          <th>应用名称</th>
		  <th width="100">版本</th>
		  <th width="100">开发者</th>
          <th width="100">管理操作</th>
        </tr>
      <?php $n=1; if(is_array($apps)) foreach($apps AS $app => $config) { ?>
        <tr>         
          <td><?php echo $app; ?></td>
		  <td><a href="<?php echo url("$app/index/index");?>" target="_blank"><?php echo $config['APP_NAME']; ?></a></td>
		  <td><?php echo $config['APP_VER']; ?></td>
		  <td><?php echo $config['APP_AUTHOR']; ?></td>
          <td>
            <?php if($config['APP_STATE'] == 1) { ?>
              <a href="<?php echo url('index/export', array('app'=>$app));?>" target="_self">导出</a>
              | <a href="<?php echo url('index/uninstall', array('app'=>$app));?>" onclick="return confirm('卸载将会删除所有数据表和文件,确定要卸载吗？')" target="_self"><font color="red">卸载</font></a>
              | <a href="<?php echo url('index/state', array('app'=>$app,'state'=>2));?>" target="_self"><font color="red">停用</font></a> 
            <?php } elseif ($config['APP_STATE'] == 2) { ?>
              <a href="<?php echo url('index/export', array('app'=>$app));?>" target="_self">导出</a>
              | <a href="<?php echo url('index/uninstall', array('app'=>$app));?>" onclick="return confirm('卸载将会删除所有数据表和文件,确定要卸载吗？')" target="_self"><font color="red">卸载</font></a>
              | <a href="<?php echo url('index/state', array('app'=>$app,'state'=>1));?>" target="_self"><font color="green">启用</font></a>            <?php } else { ?>
              <a href="<?php echo url('index/install', array('app'=>$app));?>" target="_self"><font color="green">安装</font></a>  <?php } ?>
        </td>
       </tr> 
       <?php $n++;}unset($n); ?> 
    </table>
	</div>