<?php
@session_start();

$config = array(
	'rewrite' => array(
		// '<username>/hello.html' => 'default/index',
		// 'dev/<a>.html' => 'default/<a>',
	),
	'app_id' => 'demo',
);

$setting = array(
	"demo.webdev.duowan.com" => array(
		'debug' => 1,
		'mysql' => array(
			'MYSQL_HOST' => '',
			'MYSQL_PORT' => '',
			'MYSQL_USER' => '',
			'MYSQL_DB'   => '',
			'MYSQL_PASS' => '',
			'MYSQL_CHARSET' => 'utf8',
		),
	),
	"demo.duowan.com" => array(
		'debug' => 0,
		'mysql' => array(
			'MYSQL_HOST' => '',
			'MYSQL_PORT' => '',
			'MYSQL_USER' => '',
			'MYSQL_DB'   => '',
			'MYSQL_PASS' => '',
			'MYSQL_CHARSET' => 'utf8',
		),
	),
);
define('DEBUG', $setting[$_SERVER["HTTP_HOST"]]["debug"]);
return $setting[$_SERVER["HTTP_HOST"]] + $config;