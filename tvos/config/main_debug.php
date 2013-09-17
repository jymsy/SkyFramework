<?php
$config=array(
		'basePath'=>__DIR__.DIRECTORY_SEPARATOR.'..',
		'name'=>'tvos',
		'profProbability'=>PROF_PROBABILITY,
		'preload'=>array('log','reqlog'),
		'components'=>array(
				'urlManager'=>array(
// 						'urlFormat'=>'path',
						'useParamName'=>true,
						'needCompatibility'=>true,
				),
				'cache'=>array(
						'class'=>'Sky\caching\MemCache',
						'enabled'=>MEMCACHE_ENABLE,
						'servers'=>MEMCACHE_CONFIG,
						'persistentID'=>'tianci',
				),
				'session'=>array(
						'class'=>'base\components\SkySession',
				),
				'reqlog'=>array(
						'class'=>'base\components\ReqInfo',
						'logDir'=>REQ_LOG_DIR,
				),
				'log'=>array(
						'class'=>'Sky\logging\LogRouter',
						'routes'=>array(
								array(
										'class' => 'Sky\logging\PQPLogRoute',
								),
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