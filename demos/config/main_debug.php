<?php
$config=array(
		'basePath'=>__DIR__.DIRECTORY_SEPARATOR.'..',
		'name'=>'demos',
		'profProbability'=>1,
		'preload'=>array('log','reqlog'),
		'homeUrl'=>'/demos/debug.php?_r=skyapp/skyapp/Catpcha',
// 		'homeUrl'=>'/http://www.baidu.com',
		'components'=>array(
				'urlManager'=>array(
// 						'urlFormat'=>'path',
						'useParamName'=>true,
// 						'needCompatibility'=>true,
				),
				'mail'=>array(
						'class'=>'Sky\utils\mail\PHPMailer',
						'exceptions'=>true,
						'CharSet'=>'UTF-8',
						'Host'=>'smtp.163.com',
						'SMTPAuth'=>true,
						'Port'=>25,
						'Username'=>'skysrt@163.com',
						'Password'=>'skysrt123',
						
				),
				'cache'=>array(
						'class'=>'Sky\caching\MemCache',
						'keyPrefix'=>'',
						'enabled'=>MEMCACHE_ENABLE,
						'servers'=>MEMCACHE_CONFIG,
						'persistentID'=>'tianci',
						'options'=>array(
// 								\Memcached::OPT_HASH=>\Memcached::HASH_DEFAULT,
// 								\Memcached::OPT_COMPRESSION=>true,
						),
// 						'servers'=>array(
// 								array('host'=>MEMCACHE_IP, 'port'=>MEMCACHE_PORT),
// 								// 							array('host'=>'server2', 'port'=>11211, 'weight'=>40),
// 						)
				),
				'user' => array(
						'identityClass' => 'skyapp\models\User',
				),
				'redis' => array(
						'class' => 'RedisConnection',
						'hostname' => '127.0.0.1',
// 						'port' => 6379,
// 						'database' => 0,
// 						'prefix' => 'Sky.redis.'
				),
				'gearman'=>array(
					'class'=>'Sky\utils\Gearman',
					'servers'=>'42.121.14.43:6380',
				),
				'session'=>array(
// 						'class'=>'demos\components\SkySession',
// 						'class'=>'Sky\web\CacheSession',
					'class'=>'Sky\web\DbSession',
					'sessionTable'=>'base.tb_session'
				),
				'ftp'=>array(
						'class'=>'Sky\utils\Ftp',
						'host'=>'42.121.14.43',//'42.121.14.43',
						'username'=>'jiangym',//'jiangym',
						'password'=>'jiangym123',//'jiangym123',
						'ssl'=>false,
						'timeout'=>90,
						'autoConnect'=>true,
				),
				'curl'=>array(
						'class'=>'Sky\utils\Curl',
						'enabled'=>true,
				),
				'reqlog'=>array(
					'class'=>'demos\components\ReqInfo',
					'logDir'=>REQ_LOG_DIR,
				),
				'log'=>array(
						'class'=>'Sky\logging\LogRouter',
						'routes'=>array(
// 								array(
// 										'class'=>'Sky\logging\WebLogRoute',
// // 										'levels'=>'trace, info,warning,error',
// // 										'categories'=>'system.*',
// 								),
								array(
										'class' => 'Sky\logging\PQPLogRoute',
// 										'categories' => 'application.*, exception.*,system.*',
								),
// 								array('class'=>'demos\components\ReqInfoLogRoute'),
						),
				),
		),

		// 可以使用 \Sky\Sky::$app->params['paramName'] 访问的应用级别的参数
		'params'=>require(__DIR__.DIRECTORY_SEPARATOR.'params.php'),
		'modules'=>require(__DIR__.DIRECTORY_SEPARATOR.'modules.php'),
);

$database =  require(__DIR__.DIRECTORY_SEPARATOR.'db.php');
if (!empty($database)) {
	$config['components']=array_merge($config['components'],$database);
}
return $config;