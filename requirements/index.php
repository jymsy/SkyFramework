<?php
/**
 * Sky Framework 环境要求检测脚本
 *
 * 该脚本会检测你的系统是否满足Sky Framework运行的基本条件。
 * @author jiangyumeng
 */

/**
 * @var array List of requirements (name, required or not, result, used by, memo)
 */
$requirements=array(
		array(
				t('PHP version'),
				true,
				version_compare(PHP_VERSION,"5.3.0",">="),
				'Sky Framework',
				t('PHP 5.3.0 or higher is required.')),
		array(
				t('$_SERVER variable'),
				true,
				'' === $message=checkServerVar(),
				'Sky Framework',
				$message),
		array(
				t('Reflection extension'),
				true,
				class_exists('Reflection',false),
				'Sky Framework',
				''),
		array(
				t('PCRE extension'),
				true,
				extension_loaded("pcre"),
				'Sky Framework',
				''),
		array(
				t('SPL extension'),
				true,
				extension_loaded("SPL"),
				'Sky Framework',
				''),
		array(
				t('DOM extension'),
				true,
				class_exists("DOMDocument",false),
				'Sky Framework 自动生成wsdl',
			    ''),
		array(
				t('PDO extension'),
				true,
				extension_loaded('pdo'),
				'所有和数据库相关的类',
				''),
		array(
				t('PDO SQLite extension'),
				false,
				extension_loaded('pdo_sqlite'),
				'所有和数据库相关的类',
				t('This is required if you are using SQLite database.')),
		array(
				t('PDO MySQL extension'),
				true,
				extension_loaded('pdo_mysql'),
				'所有和数据库相关的类',
				t('This is required if you are using MySQL database.')),
		array(
				t('Memcache extension'),
				false,
				'' === $message=checkMemCacheSupport(),
				'MemCache',
				$message),
		array(
				t('Ctype extension'),
				false,
				extension_loaded("ctype"),
				'Sky Framework',
				''),
		array(
				t('xhprof 扩展模块'),
				false,
				extension_loaded("xhprof"),
				'Sky Framework 性能分析日志',
				''),
		array(
				t('igbinary 扩展模块'),
				false,
				extension_loaded("igbinary"),
				'Sky Framework 性能分析日志',
				'生成性能分析日志serialize和unserialize'),
		array(
				t('ftp扩展模块'),
				false,
				extension_loaded("ftp"),
				'ftp 传输模块',
				''),
		array(
				t('phpredis扩展模块'),
				false,
				extension_loaded("redis"),
				'Sky Framework RedisSession',
				''),
);

function checkMemCacheSupport(){
	$missing=array();
	if(!extension_loaded("memcache"))
		$missing[]="memcache";
	if(!extension_loaded("memcached"))
		$missing[]="memcached";
	if(!empty($missing))
		return t("没有加载 {mem} 模块",array("{mem}"=>implode(",", $missing)));
	return "";
}

function checkServerVar(){
	$vars=array('HTTP_HOST','SERVER_NAME','SERVER_PORT','SCRIPT_NAME','SCRIPT_FILENAME','PHP_SELF','HTTP_ACCEPT','HTTP_USER_AGENT');
	$missing=array();
	foreach($vars as $var)
	{
		if(!isset($_SERVER[$var]))
			$missing[]=$var;
	}
	if(!empty($missing))
		return t('$_SERVER does not have {vars}.',array('{vars}'=>implode(', ',$missing)));

	if(realpath($_SERVER["SCRIPT_FILENAME"]) !== realpath(__FILE__))
		return t('$_SERVER["SCRIPT_FILENAME"] must be the same as the entry script file path.');

	if(!isset($_SERVER["REQUEST_URI"]) && isset($_SERVER["QUERY_STRING"]))
		return t('Either $_SERVER["REQUEST_URI"] or $_SERVER["QUERY_STRING"] must exist.');

	if(!isset($_SERVER["PATH_INFO"]) && strpos($_SERVER["PHP_SELF"],$_SERVER["SCRIPT_NAME"]) !== 0)
		return t('Unable to determine URL path info. Please make sure $_SERVER["PATH_INFO"] (or $_SERVER["PHP_SELF"] and $_SERVER["SCRIPT_NAME"]) contains proper value.');

	return '';
}

function t($message,$params=array()){
	static $messages;
	
	if($messages === null){
		$messages=array();
		$file=dirname(__FILE__)."/messages/sky.php";
		if(is_file($file))
			$messages=include($file);
	}
	
	if(empty($message))
		return $message;
	
	if(isset($messages[$message]) && $messages[$message] !== '')
		$message=$messages[$message];
	
	return $params !== array() ? strtr($message,$params) : $message;
}

function getServerInfo(){
	$info[]=isset($_SERVER['SERVER_SOFTWARE']) ? $_SERVER['SERVER_SOFTWARE'] : '';
	$info[]="Sky Framework";
	$info[]=@strftime('%Y-%m-%d %H:%M',time());

	return implode(' ',$info);
}

function renderFile($_file_,$_params_=array()){
	extract($_params_);
	require($_file_);
}

$result=1;  // 1: all pass, 0: fail, -1: pass with warnings

foreach($requirements as $i=>$requirement){
	
	if($requirement[1] && !$requirement[2])
		$result=0;
	else if($result > 0 && !$requirement[1] && !$requirement[2])
		$result=-1;
	if($requirement[4] === '')
		$requirements[$i][4]='&nbsp;';
}

$viewFile=dirname(__FILE__)."/views/index.php";

renderFile($viewFile,array('requirements'=>$requirements,'result'=>$result,'serverInfo'=>getServerInfo()));