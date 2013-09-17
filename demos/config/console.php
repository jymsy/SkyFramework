<?php
$config= array(
		'basePath'=>__DIR__.DIRECTORY_SEPARATOR.'..',
		'name'=>'demos',
		'components'=>array(
				'gearman'=>array(
						'class'=>'Sky\utils\Gearman',
						'servers'=>'42.121.14.43:6380',
				),
		),
// 		'modules'=>require(__DIR__.DIRECTORY_SEPARATOR.'modules.php'),
);

$database =  require(__DIR__.DIRECTORY_SEPARATOR.'db.php');
if (!empty($database)) {
	$config['components']=array_merge($config['components'],$database);
}
return $config;