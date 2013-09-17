<?php
$config= array(
		'basePath'=>__DIR__.DIRECTORY_SEPARATOR.'..',
		'name'=>'tvos',
		'components'=>array(),
);

$database =  require(__DIR__.DIRECTORY_SEPARATOR.'db.php');
if (!empty($database)) {
	$config['components']=array_merge($config['components'],$database);
}
return $config;