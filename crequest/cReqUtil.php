<?php
class CReqUtil{
	const DB_FILE_PREFIX = "config/";
	const EVENTS_FILE_PREFIX = "config/";
	const LOG_FILE_PREFIX = "log/";
	const CONFIG_FILE = "config/cReqST.cfg.php";
	private static $allCfg;
	public static function config(){
		if (is_null(self::$allCfg)) {
			if (file_exists(__DIR__.DIRECTORY_SEPARATOR.self::CONFIG_FILE)) {
				self::$allCfg = @include(__DIR__.DIRECTORY_SEPARATOR.self::CONFIG_FILE);
			}else{
				self::$allCfg = array();
			}
		}
		return self::$allCfg;
	}
	
	public static function getDBPath($name){
		return self::DB_FILE_PREFIX.$name.".db";
	}
	
	public static function getEventsFilePath($name){
		return self::EVENTS_FILE_PREFIX.$name.".php";
	}
	
	public static function getLogFilePath($name,$absolute=false){
		return ($absolute?__DIR__.DIRECTORY_SEPARATOR:'').self::LOG_FILE_PREFIX.$name.".log";
	}
	
	public static function getLogName($logFile){
		if (strncmp(__DIR__, $logFile, strlen(__DIR__))==0) {
			$logFile = substr($logFile, strlen(__DIR__.DIRECTORY_SEPARATOR));
		}
		return substr($logFile, strlen(self::LOG_FILE_PREFIX),-4);
	}
	
	public static function listLogFiles($prefix=""){
		$list =  self::scanFiles(self::LOG_FILE_PREFIX.$prefix, ".log");
		if ($prefix) {
			$return = array();
			foreach ($list AS $key =>$value){
				$return[$prefix.$key] = $value;
			}
			return $return;
		}else return $list;
	}
	
	public static function listDBFiles(){
		return self::scanFiles(self::DB_FILE_PREFIX,".db");
	}
	
	public static function listAllClient(){
		return array_keys(self::$allCfg);
	}
	
	public static function setConfig($client,array $config){
		$codeCfgArr = array('default' => sprintf(
				"array('events'=>'%s','log'=>'%sdefault_'.date('md').'.log')"
				,self::getEventsFilePath('default')
				,self::LOG_FILE_PREFIX
		));
		$allCfg = self::config();
		$allCfg[$client] = isset($allCfg[$client])?array_merge($allCfg[$client],$config):$config;
		self::writeCfgToFile(self::CONFIG_FILE, $allCfg, $codeCfgArr);
		self::$allCfg = require(__DIR__.DIRECTORY_SEPARATOR.self::CONFIG_FILE);
	}
	
	public static function removeConfig($client, array $configNames){
		$codeCfgArr = array('default' => sprintf(
				"array('events'=>'%s','log'=>'%sdefault_'.date('md').'.log')"
				,self::getEventsFilePath('default')
				,self::LOG_FILE_PREFIX
		));
		$allCfg = self::config();
		if (isset($allCfg[$client])) {
			foreach ($configNames AS $cfgName){
				if (array_key_exists($cfgName, $allCfg[$client])) {
					unset($allCfg[$client][$cfgName]);
				}
			}
			if ($allCfg[$client]==array()) {
				unset($allCfg[$client]);
			}
		}
		self::writeCfgToFile(self::CONFIG_FILE, $allCfg, $codeCfgArr);
		self::$allCfg = require(__DIR__.DIRECTORY_SEPARATOR.self::CONFIG_FILE);
	}
	
	public static function setLogFile($client, $logShortName){
		self::setConfig($client, array(
				'log' => self::getLogFilePath($logShortName),
		));
	}
	
	public static function createEventsFile($name,$defaultMinSleep,$defaultMaxSleep,$defaultExceptionProb,$actionEvents=array()){
		$file = self::getEventsFilePath($name);
		$defaultMinSleep = intval($defaultMinSleep);
		$defaultMaxSleep = intval($defaultMaxSleep);
		$defaultExceptionProb = intval($defaultExceptionProb);
		$codeCfgArr = array(
				'*' => sprintf("array('sleep'=>%s, 'exception'=>%s)"
						,($defaultMinSleep==$defaultMaxSleep)?$defaultMinSleep:"rand($defaultMinSleep,$defaultMaxSleep)"
						,$defaultExceptionProb<1?"false":($defaultExceptionProb>99?"true":"($defaultExceptionProb>rand(0,100))")
						)
		);
		self::writeCfgToFile($file, $actionEvents, $codeCfgArr);
		return $file;
	}
	
	private static function writeCfgToFile($file,$constCfgArr,$codeCfgArr=array()){	
		$toWriteStr = "<?php\r\nreturn array (\r\n";
		foreach ($codeCfgArr AS $key => $value){
			$toWriteStr .= sprintf("  '%s' => %s",str_replace(array('\\','\''), array('\\\\','\\\''), $key),"$value,\r\n");
			unset($constCfgArr[$key]);
		}
		$constStr = var_export($constCfgArr,true);
		$toWriteStr .= str_replace("\n","\r\n",substr($constStr, strpos($constStr, "\n")+1));
		$toWriteStr .= ";";
		$handle = fopen(__DIR__.DIRECTORY_SEPARATOR.$file, 'w');
		fwrite($handle, $toWriteStr);
		fclose($handle);
	}
	
	public static function scanFiles($prefix,$suffix=""){
		$fileArr = array();
		if ($pos=strrpos($prefix, "/")){
			$dir = __DIR__.DIRECTORY_SEPARATOR.substr($prefix, 0, $pos);
			if (!is_dir($dir)) return $fileArr;
			$prefix = substr($prefix,$pos+1);
		}else{
			$dir = __DIR__;
		}
		if ($allFileArr = scandir($dir, 0)) {
			$prefixLen = strlen($prefix);
			$suffixLen = strlen($suffix);
			foreach ($allFileArr AS $file){
				if ((!$prefixLen || strncmp($file, $prefix, $prefixLen)==0) && (!$suffixLen || substr($file, -$suffixLen)==$suffix) && substr($file, 0, 1) != ".") {
					$fileArr[substr($file, $prefixLen,strlen($file)-$prefixLen-$suffixLen)] = $dir.DIRECTORY_SEPARATOR.$file;
				}
			}
		}
		return $fileArr;
	}
	
	public static function incFilePath($filePath, $absolute=true){
		$dirPrefix = $absolute?'':(__DIR__.DIRECTORY_SEPARATOR);
		if (!file_exists($dirPrefix.$filePath)) {
			return $filePath;
		}
		$MaxIncCount = 65536;
		$info = pathinfo($filePath);
		$fileName = $info['filename'];
		$incCount = 0;
		$dir = $info['dirname'].DIRECTORY_SEPARATOR;
		$fileType = $info['extension'];
		if(preg_match('/(?P<baseName>.+)\((?P<incCount>\d+)\)$/', $fileName, $matches)){
			$fileName = $matches['baseName'];
			$incCount = $matches['incCount'];
		}
		for ($i = $incCount+1;$i<$MaxIncCount;++$i){
			$filePath = "$dir$fileName($i).$fileType";
			if(file_exists($dirPrefix.$filePath)){
				continue;
			}else {
				return $filePath;
			}
		}
		throw new Exception("IncCount of File($filePath) is up to $MaxIncCount, Please Clean File.");
	}
}