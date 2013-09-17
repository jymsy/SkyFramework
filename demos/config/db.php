<?php
return array(
		'db'=>array(
				'product'=>'mysql',
				'token'=>'TVOS',
// 				'server_master'=>array('121.199.4.55:3306'),
// 				'server_slave'=>array(	'121.199.4.56:3306'),
				'server_master'=>TVOS_MASTER,
				'server_slave'=>TVOS_SLAVE,
// 				'user'=>'skyadmin',
// 				'password'=>'skytvos!123',
				'user'=>TVOS_DB_NAME,
				'password'=>TVOS_DB_PASSWORD,
				'option'=>array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'"),
		),
// 		'db_cloud'=>array(
// 				'class'=>'Sky\db\ConnectionPool',
// 				'product'=>'mysql',
// 				'token'=>'Cloudtv',
// 				'server_master'=>array('42.121.104.85:3306'),
// 				'server_slave'=>array('42.121.104.85:3306'),
// 				'user'=>'root',
// 				'password'=>'dota.123',
// 				'option'=>array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'")
// 		),
);