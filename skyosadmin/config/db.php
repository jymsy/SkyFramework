<?php
return array(
		'db'=>array(
				'product'=>'mysql',
				'token'=>'TVOS',
				'server_master'=>TVOS_MASTER,
				'server_slave'=>TVOS_SLAVE,
				'user'=>TVOS_DB_NAME,
				'password'=>TVOS_DB_PASSWORD,
				'option'=>array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'"),
		),
);