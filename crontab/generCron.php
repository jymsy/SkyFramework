#!/usr/bin/env php
<?php
array_shift($_SERVER["argv"]);
if (count($_SERVER['argv'])!=3) {
	throw new InvalidArgumentException('argument error');
}
set_error_handler('handleError',error_reporting());
call_user_func_array("generateCron", $_SERVER["argv"]);

function generateCron($xmlpath,$cronpath,$serverName){
	
	$crontab=loadXmlCron($xmlpath,$serverName);
	
	if(($fp=fopen($cronpath, 'w'))===FALSE){
		echo 'error open cron file';
		exit(1);
	}
	fwrite($fp, "###### $serverName cron file for cron.php ######\n");
	foreach ($crontab as $cron){
		echo $cron['value']."\n";
		fwrite($fp, $cron['value']."#".$cron['mail']."\n");
	}
	
	fclose($fp);
	
	echo "generate cron file at '$cronpath'\n";
}

function loadXmlCron($path,$serverName){
	if (!is_file($path)) {
		echo "$path is not a exist file.\n";
		exit(1);
	}

	$crons=array();
	$dom=new DOMDocument();
	$dom->load($path);

	$serverDom=$dom->getElementsByTagName('server');

	foreach ($serverDom as $server){
		if ($serverName===$server->getAttribute('name')) {
			$cronDom=$server->getElementsByTagName('content');
			foreach ($cronDom as $cron){
				$crons[]=array(
					'value'=>$cron->getAttribute('value'),
					'mail'=>$cron->getAttribute('mail')
				);
			}
		}
	}

	return $crons;
}

function handleError($code,$message,$file,$line){
	echo $message."\n";
	exit();
}
