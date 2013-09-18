<?php
require_once __DIR__.DIRECTORY_SEPARATOR.'Page.php';

class CReqPageLog extends CReqPage{
	const GROUP_DELIMITER = '_';
	private $_name;
	private $_filePath;
	public function __construct($name,$initMode=0){
		if ($initMode==1) {
			$name = CReqUtil::getLogName($name);
		}
		$this->_name = $name;
		$this->_filePath = CReqUtil::getLogFilePath($name,true);
		parent::__construct($name);
		if (!file_exists($this->_filePath)) {
			self::_renderTo('log_not_found', array(
					'name'=>$name
			));
			die();
		}
	}
	
	public function actionMonitor(){
		$data = array(
				'dataUrl'=>$this->_actionUri('follow',array('_return'=>'json'))
		);
		self::_renderTo('log_monitor', $data);
	}
	
	public function actionFollow($offset){
		//Add execute interval to prevent calling frequencyly.
		usleep(200 * 1000);
// 		/throw new Exception("sdgf!#@@#Y@");
		$size = filesize($this->_filePath);
		//var_dump($size);
		if ($size>$offset) {
			$handle = fopen($this->_filePath, 'r');
			fseek($handle, $offset);
			$data = '';
			while ($line = fread($handle, 8192)){
				$data .= $line;
			}
			fclose($handle);
			return array($size,$data);
		}else return array($size,null);
	}
	
	public function actionReport(){
		require_once  __DIR__.DIRECTORY_SEPARATOR.'LogReport.php';
		$report = new LogReport();
		$handle = fopen($this->_filePath, 'r');
		while ($line = fgets($handle)){
			$report->addLine(rtrim($line));
		}
		fclose($handle);
		$data = $report->data;
		$report->make();
		
		self::_renderTo('log_report', array(
				'total'=>$report->stReport(),
				'request'=>$report->requestSTReport(),
				'data'=>$data,
		));
	}
	
	static public function actionDefault($prefix=''){
		$list = CReqUtil::listLogFiles($prefix);
		$logUrls = array();
		$logGroup = array();
		foreach ($list AS $name => $file){
			$logUrls[$name] = array(
					'report'=>self::_actionUri('report',array(),array($name)),
					'monitor'=>self::_actionUri('monitor',array(),array($name)),
			);
			$grKey = substr($name, 0, strpos($name, self::GROUP_DELIMITER));
			if (array_key_exists($grKey, $logGroup)) {
				array_unshift($logGroup[$grKey], $name);
			}else {
				$logGroup[$grKey] = array($name);
			}
		}
		ksort($logGroup);
		self::_renderTo('log_default', array(
				'logUrls' => $logUrls,
				'logGroup' => $logGroup,
		));
	}

	static public function actionError($msg,$from){
		echo __CLASS__." Error Page<br>\n";
		parent::actionError($msg,$from);
	}
}
CReqPageLog::run();