<?php
return array(

	'rewrite' => array(
		'<username>/hello.html' => 'default/model',
		
		//'dev/<a>.html' => 'default/<a>',
	),
	
	'app_id' => 'k100',

	'mongo' => array(
		'MONGO_HOST' => 'localhost',
		'MONGO_PORT' => '27017',
		'MONGO_DB'   => 'test',
	),
);
