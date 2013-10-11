<?php
$config=array(
		'basePath'=>__DIR__.DIRECTORY_SEPARATOR.'..',
		'name'=>'skyosadmin',
		'preload'=>array('log'),
		'components'=>array(
				'urlManager'=>array(
						'urlFormat'=>'get',
						'useParamName'=>true,
				),
				'session'=>array(
						'class'=>'Sky\web\DbSession',
						'sessionTable'=>'tb_session',
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
						'enabled'=>MAIL_ENABLE
				),
				'ftp'=>array(
						'class'=>'Sky\utils\Ftp',
						'host'=>FTP_HOST,
						'port'=>FTP_PORT,
						'username'=>FTP_USERNAME,
						'password'=>FTP_PASSWORD,
						'ssl'=>false,
						'timeout'=>90,
						'autoConnect'=>true,
				),
				
				'log'=>array(
						'class'=>'Sky\logging\LogRouter',
						'routes'=>array(
								array(
										'class' => 'Sky\logging\PQPLogRoute',
								),
						),
				),
				'curl' => array(
						'class' => 'Sky\utils\Curl'
				),
				'session'=>array(
					'class'=>'Sky\web\DbSession',
					'sessionTable'=>'`skyg_base`.`base_admin_session`',
				)
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