<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="author" content="单骑闯天下，李文辉">
<meta name="keywords" content="canphp框架-升级日志">
<meta name="description" content="canphp框架-升级日志">
<meta http-equiv="X-UA-Compatible" content="IE=7">
<title>canphp框架-升级日志</title>
<LINK rel=stylesheet type=text/css href="__PUBLIC__/css/style.css">
</HEAD>
<BODY>
<!--导航菜单-->
{include file="header"}
<DIV class=box>
<div class="left">
<div class="boxleft">

<div class="h4">升级日志</div>

<div class="conbox">

<div class="downloadtitle">canphp框架升级日志</div>

<div class="downloadbt">canphp1.5正式版 <span>发布时间：2011-11-03 </span>  &nbsp; &nbsp; &nbsp; &nbsp;<a href="__APP__/download.html">下载中心</a></div>
<div class="downloadjj">
<pre>
1、增加xml类，用于xml数据转换成php数组
2、增强分页类，优化生成的分页网址，增加长文章内容分页功能
3、增强图片类，增加图片水印功能
4、增强系统错误处理类，可以显示出错的文件及出错的行和trace信息
5、增加模板扩展标签，如if，for，foreach标签等
6、增加自定义网址解析接口url_parse_ext()
7、增加模型缓存接口db_cache_get_ext($key)和db_cache_set_ext($key,$data,$expire)
8、修正权限认证类。错误类和cpModel模型类缓存bug

从1.5以下版本升级到1.5正式版本
将1.5正式版的CanPHP目录直接覆盖掉原来的CanPHP目录，
注意：cp1.5分页类有较大改进，如出现分页有问题，需要参考cp1.5开发手册稍微修改一下。
</pre>
</div>

<div class="downloadbt">canphp1.4 <span>发布时间：2011-07-20 </span></div>
<div class="downloadjj">
<pre>
1、新增强大的表单验证类
2、新增数据库备份与恢复类，支持分卷。
3、新增zip压缩压缩与解压缩类
4、新增汉字转拼音类
5、新增ip地理位置信息类
6、新增模型调用函数model()
7、Http.class.php类http协议由1.1改成1.0。
8、修正1.3版本中生成的缓存没有后缀的bug
9、修正其他一些细节bug
</pre>
</div>

<div class="downloadbt">canphp1.3 <span>发布时间：2011-06-25 </span></div>
<div class="downloadjj">
<pre>
1 修改默认配置加载方式，默认配置写成静态类cpConfig.class.php
2 lib目录增加插件管理控制类Plugin.class.php
3 增加模板标签自定义解析接口tpl_parse_ext($template)，可任意diy模板标签
4 ext目录增加extend.php扩展函数文件，默认会加载，留给用户自定义函数
5 过滤函数文件filter.function.php合并到了common.function.php
6 优化cpModel模型类的find()和修正insert()方法返回bug
7 修正删除静态缓存bug
8 常用函数库common.function.php增加del_dir()函数
</pre>
</div>

<div class="downloadbt">canphp1.2 <span>发布时间：2011-05-15 </span></div>
<div class="downloadjj">
<pre>
1 常用函数增加了加解密函数cp_encode()和cp_decode()
2 升级了http类，支持发送http头，如cookie信息等
3 升级了多语言类，增加了获取语言包数组getPack()方法
4 改善数据库缓存类，没有调用数据库，不加载缓存类和数据缓存文件
5 修改了一些细节bug
</pre>
</div>

<div class="downloadbt">canphp1.1<span>发布时间：2011-04-15 </span></div>
<div class="downloadjj">
<pre>
1 升级了数据库缓存系统，支持数百万级数据
2 扩展类库中增加了权限认证类
3 错误日志类增加记录出错页面网址
4 修正分页类一些小细节bug
</pre>
</div>

<div class="downloadbt">canphp1.0正式版<span>发布时间：2011-03-12 </span></div>
<div class="downloadjj">
<pre>
1 增加调试和发布模式
2 增加自定义系统错误页面重定向
3 增加静态页面生成功能
4 增加多语言支持
5 增加自动包含所需的类
6 cpApp单一入口中，增加默认加载common.function.php和filter.function.php
7 常用函数库common.function.php增加module(),json_encode(),is_email()三个函数
8 第三方扩展库增加邮件发送类
</pre>
</div>

<div class="clearfix"></div>
</div></div>
</div>
{include file="right"}

<div class="clearfix"></div>
</DIV>

<!--底部信息-->
{include file="footer"}
</BODY></HTML>
