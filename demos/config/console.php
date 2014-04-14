<?php
$config= array(
		'basePath'=>__DIR__.DIRECTORY_SEPARATOR.'..',
		'name'=>'demos',
		'preload'=>array('log'),
		'components'=>array(
				'gearman'=>array(
						'class'=>'Sky\utils\Gearman',
						'servers'=>'42.121.14.43:6380',
				),
				'rabbitmq'=>array(
						'class'=>'Sky\utils\rabbitmq\Rabbit',
						'host' => '127.0.0.1',
						'port' => '5672',
						'login' => 'guest',
						'password' => 'guest',
						'vhost'=>'/'
				),
				'redis' => array(
						'class' => 'Sky\utils\RedisClient',
						'masterhost' => '127.0.0.1:6379',
						'slavehost' => '127.0.0.1:6379',
						'persistent' => true,
						'enabled' => true,
						'timeout' => 10
				),
				'activemq'=>array(
						'class'=>'Sky\utils\ActiveMQ',
						'brokerUri'=>'tcp://localhost:61612',
				),
				'curl' => array(
						'class' => 'Sky\utils\Curl'
				),
				'log'=>array(
						'class'=>'Sky\logging\LogRouter',
						'routes'=>array(
								array(
										'class' => 'Sky\utils\rabbitmq\ProfLogRoute',
										'logFile'=>'/tmp/skyc.log',
								),
						),
				),
		),
// 		'modules'=>require(__DIR__.DIRECTORY_SEPARATOR.'modules.php'),
);

$database =  require(__DIR__.DIRECTORY_SEPARATOR.'db.php');
if (!empty($database)) {
	$config['components']=array_merge($config['components'],$database);
}
return $config;