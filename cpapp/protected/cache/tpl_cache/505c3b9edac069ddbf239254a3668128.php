<?php if (!defined('CANPHP')) exit;?>﻿<div class="install_right">
  <div class="install_box">
		<h2>系统检查</h2>
			<table width="100%" border="0" cellspacing="1" cellpadding="0" class="jc_table">
                  <tr>
                    <td width="18%"><strong>检查项目</strong></td>
                    <td width="36%"><strong>当前环境</strong></td>
                    <td width="28%"><strong>系统要求</strong></td>
                    <td width="18%"><strong>状态</strong></td>
                  </tr>
                  <tr>
                    <td>web 服务器</td>
                    <td><?php echo $_SERVER['SERVER_SOFTWARE']; ?></td>
                    <td>Apache/nginx/IIS等</td>
                    <td><font color="green">√</font></td>
                  </tr>
                  <tr>
                    <td>php 版本</td>
                    <td>php <?php echo PHP_VERSION;?></td>
                    <td>php 5.0.0 及以上</td>
                    <td><?php if($ifVer) { ?> <?php echo $yes; ?> <?php } else { ?> <?php echo $no; ?><?php } ?></td>
                  </tr>
                  <tr>
                    <td>mysql数据库</td>
                    <td><?php if($ifMysql) { ?>已支持<?php } else { ?>不支持<?php } ?></td>
                    <td>必须支持</td>
                    <td><?php if($ifMysql) { ?> <?php echo $yes; ?> <?php } else { ?> <?php echo $no; ?><?php } ?></td>
                  </tr>
                  <tr>
                    <td>gd 扩展</td>
                    <td><?php if($ifGd) { ?>已开启<?php } else { ?>未开启<?php } ?></td>
                    <td>必须开启</td>
                    <td><?php if($ifGd) { ?> <?php echo $yes; ?> <?php } else { ?> <?php echo $no; ?><?php } ?></td>
                  </tr>
				  <?php $n=1; if(is_array($rwFiles)) foreach($rwFiles AS $file => $status) { ?>
                  <tr>
                    <td><?php echo $file; ?></td>
                    <td><?php if($status) { ?>可写<?php } else { ?>不能写<?php } ?></td>
                    <td>必须可写</td>
                    <td><?php if($status) { ?> <?php echo $yes; ?> <?php } else { ?> <?php echo $no; ?><?php } ?></td>
                  </tr>
				  <?php $n++;}unset($n); ?>
             </table>
  </div>
<div class="install_btn"> <input class="button" value="上一步" type="button" onClick="window.location.href = '<?php echo url('index/index');?>'"> <input class="button" value="下一步" type="button" onClick="window.location.href = '<?php echo url('index/db');?>'"></div>
</div>