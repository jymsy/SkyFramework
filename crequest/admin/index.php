<?php
require_once __DIR__.DIRECTORY_SEPARATOR.'Page.php';
require_once dirname(__DIR__).DIRECTORY_SEPARATOR."cReqDB.php";

class CReqPageIndex extends CReqPage{
	const QUICK_SET_CLIENT = 'ctest.skyworth-cloud.com';
	
	public function actionDefault(){
		$data = array();
		if (CReqUtil::config()==array()) {
			$data['quickSetUrl'] = self::_actionUri('quickSet',array(
					'event'=>'0|0|0',
					'client'=>self::QUICK_SET_CLIENT,
			));
		}
		$dbUrls = array();
		foreach (CReqUtil::listDBFiles() AS $key => $value){
			$dbUrls[$key] = self::_generateUrl('default',array(), array($key), 'db.php');
		}
		
		self::_renderTo('index_default', array_merge($data,array(
				'config'=>CReqUtil::config(),
				'clients'=>CReqUtil::listAllClient(),
				'logList'=>CReqUtil::listLogFiles(),
				'dbUrls'=>$dbUrls,
				'dbNewUrl'=>self::_generateUrl('new',array(),array(),'db.php'),
				'logDivideUrl'=>self::_actionUri('logDivide',array('_time'=>microtime(true))),
		)));
	}

	static function actionLogDivide($client){
		$config = CReqUtil::config();
		if(array_key_exists($client, $config)){
			if (($pos = strpos($client, '.'))!==false){
				$clientSortName = substr($client, 0, $pos);
			}else $clientSortName = $client;
			$logFile = CReqUtil::getLogFilePath($clientSortName.'_'.date('md'));
			
			CReqUtil::setConfig($client, array(
				'log'=>CReqUtil::incFilePath($logFile,false)
			));
		}
		self::_renderTo('index_logdivide', array(
				'config'=>CReqUtil::config(),
		));
	}
	
	static function actionRemoveClient($client){
		$allCfg = CReqUtil::config();
		if (array_key_exists($client, $allCfg)) {
			CReqUtil::removeConfig($client, array_keys($allCfg[$client]));
		}
		return true;
	}
	
	static public function actionQuickSet($event,$client='',$storeTo='default'){
		$cReqDB = new CReqDB($storeTo,true);

		$eventdata = explode("|", $event);
		if (count($eventdata)==3) {
			$cReqDB->setDefaultEvent($eventdata[0], $eventdata[1], $eventdata[2]);
			echo sprintf("Set Default Event->min_sleep: %d, max_sleep: %d, exception_probabilty: %d<br>",$eventdata[0], $eventdata[1], $eventdata[2]);
		}
		
		$clients = explode("|", $client);
		$cReqDB->attachTo($clients);
		echo sprintf("Attach Event To Client->%s<br>",$client);
		$cReqDB->apply();
		if (error_get_last()) {
			echo "\n<pre>Error:";
			print_r(error_get_last());
			echo "</pre>\n";
		}else echo "Init success!<br>";
		echo sprintf('<input type="button" value="Back" onclick="history.back()"/>');
		//echo sprintf('<a href="%s">GOTO HOME</a>',addslashes(self::_actionUri('')));
	}

	static public function actionError($msg,$from){
		echo __CLASS__." Error Page<br>\n";
		parent::actionError($msg,$from);
	}
}
CReqPageIndex::run();