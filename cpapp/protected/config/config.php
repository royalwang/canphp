<?php 
return array (
  'REWRITE' => 
  array (
  ),
  'APP' => 
  array (
    'DEBUG' => true,
    'LOG_ON' => false,
    'LOG_PATH' => BASE_PATH . 'cache/log/',
    'URL_HTTP_HOST' => '',
  ),
  'DB' => 
  array (
    'DB_TYPE' => 'mysql',
    'DB_HOST' => 'localhost',
    'DB_USER' => 'root',
    'DB_PWD' => '123456',
    'DB_PORT' => '3306',
    'DB_NAME' => '123',
    'DB_CHARSET' => 'utf8',
    'DB_PREFIX' => 'cp_',
  ),
  'TPL' => 
  array (
    'TPL_TEMPLATE_SUFFIX' => '.php',
    'TPL_CACHE_ON' => true,
    'TPL_CACHE_PATH' => BASE_PATH . 'cache/tpl_cache/',
  ),
);