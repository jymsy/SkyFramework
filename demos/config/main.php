<?php
$config=array(
		'basePath'=>__DIR__.DIRECTORY_SEPARATOR.'..',
		'name'=>'demos',
		'preload'=>array('log'),
		'components'=>array(
				'urlManager'=>array(
// 						'urlFormat'=>'path',
						'useParamName'=>true,
						'needCompatibility'=>true,
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
				),
				'session'=>array(
						'class'=>'Sky\web\SSession',
// 						'autoStart' => false,
				),
				'log'=>array(
						'class'=>'Sky\logging\LogRouter',
						'routes'=>array(
// 								array(
// 										'class'=>'Sky\\logging\\BiLogRoute',
// 										'serverName'=>'log.skysrt.com',
// 										'serverPort'=>40022,
// 								),
// 								array(
// 										'class'=>'Sky\logging\ErrorLogRoute',
// 										'serverIP'=>'42.121.104.9',
// 										'serverPort'=>50021,
// 								),
// 								array(
// 										'class'=>'Sky\logging\XhLogRoute',
// 										'serverIP'=>'42.121.104.9',
// 										'serverPort'=>50021,
// 								),
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