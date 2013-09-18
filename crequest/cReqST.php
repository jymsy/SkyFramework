<?php
//Client Request Statistics
class CReqST{
	private static $toLog = array();
	private static $endRunTime;
	private static $config;
	public static $tmp_allconfig;
	public static $tmp_allEvents;
	const CONFIG_FILE = "config/cReqST.cfg.php";
	const TIMEZONE = "Asia/Shanghai";
	/**
	 * 
	 * @var int For count execute time. Unit: ms
	 */
	const TIME_PRECISION = 2;
	public static function run($client='default'){
		$reqTime = microtime(true);
		date_default_timezone_set(self::TIMEZONE);
		$allconfig = require(__DIR__.DIRECTORY_SEPARATOR.self::CONFIG_FILE);
		self::$config = isset($allconfig[$client])
			?array_merge($allconfig['default'],$allconfig[$client])
			:$allconfig['default'];
		
		$reqAct = self::reqActionId();
		$events = self::getEvents($reqAct);
		array_push(self::$toLog, $reqAct,self::getClientIP(),$reqTime);
		
		//[Begin Trigger Action Event-->
		try{
			if($events['sleep']) usleep($events['sleep']*1000);
			if($events['exception']) throw new Exception();
		}catch (Exception $e){
			register_shutdown_function(__CLASS__.'::onShutDown');
			array_push(self::$toLog, round(((self::$endRunTime=microtime(true))-$reqTime)*1000,self::TIME_PRECISION));
			throw new Exception("This is expected exception throwed in cReqST.php on purpose.");
		}
		register_shutdown_function(__CLASS__.'::onShutDown');
		array_push(self::$toLog, round(((self::$endRunTime=microtime(true))-$reqTime)*1000,self::TIME_PRECISION));
		//<--End Trigger]
	}
	
	private static function reqActionId(){
		$reqAct = '';
		if (isset($_SERVER['HTTP_SOAPACTION'])) {
			$reqAct = $_SERVER['HTTP_SOAPACTION'];
		}else $reqAct = $_SERVER['REQUEST_URI'];
		return preg_replace('/\s/', '', $reqAct);
	}
	
	private static function getClientIP(){
		$ip = "";
		if ( isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] && strcasecmp($_SERVER['HTTP_X_FORWARDED_FOR'], 'unknown') ) {
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} elseif ( isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown') ) {
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		if (($pos=strpos($ip, ',')) !== false) $ip = substr($ip, 0, $pos);
		return preg_replace('/\s/', "", $ip);
	}
	
	private static function getEvents($reqAct){
		$events = array('sleep'=>0,'exception'=>false);
		$eventFile = __DIR__.DIRECTORY_SEPARATOR.self::$config['events'];
		if(file_exists($eventFile)){
			$allEvents = require($eventFile);
			//Default Events. Sleep Units: ms
			$events = array_key_exists("*", $allEvents)?$allEvents["*"]:$events;
			return array_key_exists($reqAct, $allEvents)?array_merge($events,$allEvents[$reqAct]):$events;
		}else return $events;
	}
	
	/**
	 * Do not call this function manually.
	 */
	public static function onShutDown(){
		array_push(self::$toLog, round((microtime(true)-self::$endRunTime)*1000,self::TIME_PRECISION));
		array_push(self::$toLog, is_null(error_get_last())?'OK':'ERROR');
		//var_dump(error_get_last());
		//echo "ExecuteShutDownFunc->",__CLASS__,"::",__FUNCTION__,PHP_EOL;
		$logStr = implode("\t", self::$toLog);
		//Set Log File
		$logFile=__DIR__.DIRECTORY_SEPARATOR;
		if (DIRECTORY_SEPARATOR=="/") {
			$logFile.=str_replace(array('(',')'), array('\(','\)'), self::$config['log']);
		}else $logFile.=self::$config['log'];
		//Log to file
		echo $logFile;
		exec("echo ".addslashes($logStr)." >> $logFile");
	}
}
if (strncmp($_SERVER["HTTP_HOST"], "ctest", 5)==0) CReqST::run($_SERVER["HTTP_HOST"]);
//if ($_SERVER["HTTP_HOST"]=="ctest.skyworth-cloud.com") CReqST::run($_SERVER["HTTP_HOST"]);