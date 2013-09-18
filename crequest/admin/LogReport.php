<?php
class LogReport{
	public $data = array();
	public $reqCount = array();
	public $reqStatusCount = array();
	public $statusCount = array();
	public $ipCount = array();
	public $reqExeTimeSum = array();
	public $reqTimeDetail = array();
	public $totalTimeDetail = array();
	public $stat = array();
	private $startTime;
	private $endTime;
	public static $ReqTimeInterval = array(
			1=>1,
			2 => 20,
			10 => 1000,
	);
	public $questionReq = array();
	
	function addLine($line){
		$this->data[] = $line;
	}
	
	function make(){
		$delimiter = "\t";
		if (count($this->data)) {
			if (strpos($this->data[0], $delimiter)===false) {
				$delimiter = " ";
			};
		}
		while (($line=array_shift($this->data)) != null){
			$info = explode($delimiter, $line);
			if (count($info)>=6) {
				list($reqId, $ip, $time, $eventCosts, $exeCosts, $status) = $info;
				self::_incCount($reqId, $this->reqCount);
				self::_incCount($reqId.' '.$status, $this->reqStatusCount);
				self::_incCount($status, $this->statusCount);
				self::_incCount($ip, $this->ipCount);
				self::_incSum($reqId, $exeCosts, $this->reqExeTimeSum);
				self::_incPush($reqId, $time, $this->reqTimeDetail);
				self::_incPush('total', $time, $this->totalTimeDetail);
				self::_maxValue('endTime', $time, $this->stat);
				self::_minValue('beginTime', $time, $this->stat);
			}
		}
		$this->startTime = floor(self::_getValue('beginTime',$this->stat,0));
		$this->endTime = ceil(self::_getValue('endTime',$this->stat,0)+0.1);
		foreach ($this->reqTimeDetail AS $reqId => $timeArr){
			$qLog = self::checkInterval($timeArr);
			if (!is_null($qLog)) {
				$this->questionReq[$reqId] = $qLog;
			}
		}
	}
	
	public function stReport(){
		$exeTime = 0;
		foreach ($this->reqExeTimeSum AS $value){
			$exeTime+=$value;
		}
		return array(
				'beginTime' => date("Y-m-d H:i:s",self::_getValue('beginTime',$this->stat)),
				'endTime' => date("Y-m-d H:i:s",self::_getValue('endTime',$this->stat)),
				'count' => self::_getValue('OK', $this->statusCount, 0)+self::_getValue('ERROR', $this->statusCount, 0),
				'countOK' => self::_getValue('OK', $this->statusCount, 0),
				'countError' => self::_getValue('ERROR', $this->statusCount, 0),
				'exeTime' => $exeTime,
				'timeSection' => $this->_toSectionArr(self::_getValue('total', $this->totalTimeDetail, array()), $this->startTime, $this->endTime)
		);
	}
	
	private function _toSectionArr(array $data,$sBegin,$sEnd,$sliceNum = 10){
		$sArr = array_fill(0, $sliceNum, 0);
		$step = ($sEnd - $sBegin)/$sliceNum;
		foreach ($data AS $value){
			$x=floor($value-$sBegin)/$step;
			if ($x>=0 && $x<$sliceNum) {
				$sArr[$x]++;
			}
		}
		return $sArr;
	}
	
	public function requestSTReport(){
		$data = array();
		foreach ($this->reqCount AS $reqId =>$value){
			$stRep = array();
			$stRep['count']=$value;
			$stRep['countOK'] = self::_getValue($reqId.' OK', $this->reqStatusCount, 0);
			$stRep['countError'] = self::_getValue($reqId.' ERROR', $this->reqStatusCount, 0);
			$stRep['exeTime'] = self::_getValue($reqId, $this->reqExeTimeSum, 0);
			$stRep['question'] = self::_getValue($reqId, $this->questionReq);
			$stRep['timeSection'] = $this->_toSectionArr(self::_getValue($reqId, $this->reqTimeDetail, array()), $this->startTime, $this->endTime);
			$data[$reqId] = $stRep;
		}
		return $data;
	}
	
	private static function _getValue($key,array $data,$defaultValue=null){
		return array_key_exists($key, $data)?$data[$key]:$defaultValue;
	}
	
	private static function _incCount($key, array &$data){
		if (array_key_exists($key, $data)) {
			++$data[$key];
		}else{
			$data[$key] = 1;
		}
	}
	
	private static function _incSum($key, $value, array &$data){
		if (array_key_exists($key, $data)) {
			$data[$key] += $value;
		}else{
			$data[$key] = $value;
		}
	}
	
	private static function _incPush($key, $value, array &$data){
		if (array_key_exists($key, $data)) {
			array_push($data[$key], $value);
		}else{
			$data[$key] = array($value);
		}
	}
	
	private static function _maxValue($key, $value, array &$data){
		if (array_key_exists($key, $data)) {
			$data[$key] = max($data[$key], $value);
		}else {
			$data[$key] = $value;
		}
	}
	
	private static function _minValue($key, $value, array &$data){
		if (array_key_exists($key, $data)) {
			$data[$key] = min($data[$key], $value);
		}else {
			$data[$key] = $value;
		}
	}
	
	static function checkInterval($timeArr, array $interval = null){
		if (is_null($interval)) {
			$interval = self::$ReqTimeInterval;
		}
		if (count($timeArr) < 1) {
			return ;
		}
		sort($timeArr, SORT_NUMERIC);
		for($i = 1,$n = count($timeArr);$i<$n;++$i){
			foreach ($interval AS $key => $value){
				if ($key > $i) {
					continue;
				}else{
					if (($timeArr[$i]-$timeArr[$i-$key])<$value) {
						return array_slice($timeArr, $i-$key, $key+1);
					}
				}
			}
		}
		return ;
	}
}