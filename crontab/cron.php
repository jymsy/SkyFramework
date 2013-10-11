#!/usr/bin/env php
<?php
define('SECDEL', 3600*24*10);
date_default_timezone_set('Asia/Shanghai');
array_shift($_SERVER["argv"]);
if (count($_SERVER['argv']) > 3) {
	throw new InvalidArgumentException('missing argument cronPath');
}

call_user_func_array("cronRun", $_SERVER["argv"]);

function cronRun($cronPath, $eth='eth1', $errFilePath='/tmp/cron.err'){
	$crontab=file($cronPath);
	$now=time();
	delOldLog($now);
	$mailArr=array();
	$newErrFile=$errFilePath.'.new';
	foreach ( $crontab as $cron ) {
		$cron=ltrim($cron);
		if ($cron[0]==='#') {
			continue;
		}
		$slices = preg_split("/[\s]+/", $cron, 6);
		if( count($slices) !== 6 )
			continue;
	
		$cmd= trim(array_pop($slices));
		$mailArr=getMailList($cmd);
		$cron_time = implode(' ', $slices);
		$next_time = Crontab::parse($cron_time, $now);
		if ( $next_time !== $now )
			continue;

		$pid = pcntl_fork();
		if ($pid == -1) {
			die('could not fork');
		} else if ($pid) {
			// we are the parent
			pcntl_wait($status, WNOHANG); //Protect against Zombie children
		} else {
			// we are the child
// 			`$cmd`;
			$stdout = $stderr = $result = null;
			$result=Sexec($cmd, $stdout, $stderr, 36000);
			$stdout=rtrim($stdout);
			$stderr=rtrim($stderr);
			$exec_time=time()-$now;
			$now=date('Y-m-d H:i:s',$now);
			$suff=date('Y-m-d');
			if(($fp=@fopen('/tmp/cron.log.'.$suff, 'a'))===FALSE){
				echo 'error open cron log file';
				exit(1);
			}
			@flock($fp,LOCK_EX);
			@fwrite($fp, "$now cmd:'$cmd' result code:$result Exec time:$exec_time\nstdout:$stdout\nstderr:$stderr\n");
			@flock($fp,LOCK_UN);
			@fclose($fp);
			if ($result!='0') {
				writeTempError("$now cmd:'$cmd' result code:$result Exec time:$exec_time\nstdout:$stdout\nstderr:$stderr\n",$errFilePath);
				foreach ($mailArr as $mail){
					$ip=trim(getIP($eth));
					shell_exec('cat '.$errFilePath.' | sed \'s/\r//\' >'.$newErrFile);
					shell_exec('export LANG=en_US.UTF-8;mail -s "crontab exec Error at '.$ip.'" '.$mail.'<'.$newErrFile);
				}
			}
			exit();
		}
	}
}

function getIP($eth){
	return shell_exec("/sbin/ifconfig $eth|grep \"inet addr:\"|cut -d: -f2|awk '{print $1}'");
}

function writeTempError($str,$filePath){
	if(($fp=@fopen($filePath, 'w'))===FALSE){
		exit(1);
	}
	@flock($fp,LOCK_EX);
	@fwrite($fp, $str);
	@flock($fp,LOCK_UN);
	@fclose($fp);
}

function getMailList(&$cmd){
	if (($pos=strpos($cmd, '#'))!==false) {
		$mailStr=substr($cmd, $pos+1);
		$cmd=substr($cmd, 0,$pos);
		return explode(',', $mailStr);
	}
	return array();
}

function delOldLog($now){
	$name='/tmp/cron.log.'.date('Y-m-d',$now-SECDEL);
	@unlink($name);
}

/**
 * 执行系统命令
 * @param string $cmd 要执行的命令
 * @param string $stdout 该命令的标准输出
 * @param string $stderr 该命令的标准出错
 * @param integer $timeout 命令执行超时时间
 * @return integer|null 命令的返回值，成功的话为0
 */
function Sexec($cmd, &$stdout, &$stderr, $timeout = 3600){
	if ($timeout <= 0) $timeout = 3600;
	$descriptors = array(
			1 => array("pipe", "w"),
			2 => array("pipe", "w")
	);

	$stdout = $stderr = $status = null;
	$process = proc_open($cmd, $descriptors, $pipes);

	$time_end = time() + $timeout;
	if (is_resource($process)) {
		do {
			$time_left = $time_end - time();
			$read = array($pipes[1]);
			stream_select($read, $null, $null, $time_left, NULL);
			$stdout .= fread($pipes[1], 2048);
		} while (!feof($pipes[1]) && $time_left > 0);
		fclose($pipes[1]);

		if ($time_left <= 0) {
			proc_terminate($process);
			$stderr = 'process terminated for timeout.';
			return -1;
		}

		while (!feof($pipes[2])) {
			$stderr .= fread($pipes[2], 2048);
		}
		fclose($pipes[2]);

		$status = proc_close($process);
	}

	return $status;
}

/* https://github.com/jkonieczny/PHP-Crontab */
class Crontab {
	/**
	 * Finds next execution time(stamp) parsin crontab syntax,
	 * after given starting timestamp (or current time if ommited)
	 *
	 * @param string $_cron_string:
	 *
	 * 0 1 2 3 4
	 * * * * * *
	 * - - - - -
	 * | | | | |
	 * | | | | +----- day of week (0 - 6) (Sunday=0)
	 * | | | +------- month (1 - 12)
	 * | | +--------- day of month (1 - 31)
	 * | +----------- hour (0 - 23)
	 * +------------- min (0 - 59)
	 * @param int $_after_timestamp timestamp [default=current timestamp]
	 * @return int unix timestamp - next execution time will be greater
	 * than given timestamp (defaults to the current timestamp)
	 * @throws InvalidArgumentException
	 */
	public static function parse($_cron_string,$_after_timestamp=null)
	{
		if(!preg_match('/^((\*(\/[0-9]+)?)|[0-9\-\,\/]+)\s+((\*(\/[0-9]+)?)|[0-9\-\,\/]+)\s+((\*(\/[0-9]+)?)|[0-9\-\,\/]+)\s+((\*(\/[0-9]+)?)|[0-9\-\,\/]+)\s+((\*(\/[0-9]+)?)|[0-9\-\,\/]+)$/i',trim($_cron_string))){
			throw new InvalidArgumentException("Invalid cron string: ".$_cron_string);
		}
		if($_after_timestamp && !is_numeric($_after_timestamp)){
			throw new InvalidArgumentException("\$_after_timestamp must be a valid unix timestamp ($_after_timestamp given)");
		}
		$cron = preg_split("/[\s]+/i",trim($_cron_string));
		$start = empty($_after_timestamp)?time():$_after_timestamp;

		$date = array( 'minutes' =>self::_parseCronNumbers($cron[0],0,59),
				'hours' =>self::_parseCronNumbers($cron[1],0,23),
				'dom' =>self::_parseCronNumbers($cron[2],1,31),
				'month' =>self::_parseCronNumbers($cron[3],1,12),
				'dow' =>self::_parseCronNumbers($cron[4],0,6),
		);
		// limited to time()+366 - no need to check more than 1year ahead
		for($i=0;$i<=60*60*24*366;$i+=60){
			if( in_array(intval(date('j',$start+$i)),$date['dom']) &&
			in_array(intval(date('n',$start+$i)),$date['month']) &&
			in_array(intval(date('w',$start+$i)),$date['dow']) &&
			in_array(intval(date('G',$start+$i)),$date['hours']) &&
			in_array(intval(date('i',$start+$i)),$date['minutes'])

			){
				return $start+$i;
			}
		}
		return null;
	}

	/**
	 * get a single cron style notation and parse it into numeric value
	 *
	 * @param string $s cron string element
	 * @param int $min minimum possible value
	 * @param int $max maximum possible value
	 * @return int parsed number
	 */
	protected static function _parseCronNumbers($s,$min,$max)
	{
		$result = array();

		$v = explode(',',$s);
		foreach($v as $vv){
			$vvv = explode('/',$vv);
			$step = empty($vvv[1])?1:$vvv[1];
			$vvvv = explode('-',$vvv[0]);
			$_min = count($vvvv)==2?$vvvv[0]:($vvv[0]=='*'?$min:$vvv[0]);
			$_max = count($vvvv)==2?$vvvv[1]:($vvv[0]=='*'?$max:$vvv[0]);

			for($i=$_min;$i<=$_max;$i+=$step){
				$result[$i]=intval($i);
			}
		}
		ksort($result);
		return $result;
	}
}
