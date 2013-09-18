<?php
require_once __DIR__.DIRECTORY_SEPARATOR.'Page.php';
require_once dirname(__DIR__).DIRECTORY_SEPARATOR."cReqDB.php";

class CReqPageDB extends CReqPage{
	private $name;
	private $cReqDB;
	public function __construct($name='default'){
		$this->name = $name;
		$this->cReqDB = new CReqDB($this->name);
		parent::__construct($name);
	}
	
	public static function actionNew(){
		self::_renderTo('db_new', array(
				'dbCreateUrl' => self::_actionUri('create')
		));
	}
	
	public static function actionCreate($name){
		try {
			new CReqDB($name,true);
		}catch (Exception $e){
			self::_error($e);
			return ;
		}
		self::_renderTo('db_create', array(
				'name'=>$name,
				'url'=>self::_actionUri('default',array(),array($name)),
		));
	}
	
	public function actionAttachClient($client){
		$result = $this->cReqDB->attachTo($client);
		return $result;
	}
	
	public function actionRemoveAttachedClient($client){
		$result = $this->cReqDB->removeAttach(array($client));
		return $result;
	}
	
	public function actionDefault(){
		$defaultEvent = $this->cReqDB->getDefaultEvent();
		$clients = $this->cReqDB->getAttachedClients();
		$actionEvents = $this->cReqDB->getAllActionEvents();
		self::_renderTo('db_default', array(
				'name'=>$this->name,
				'defaultEvent'=>$defaultEvent,
				'clients'=>$clients,
				'actionEvents'=>$actionEvents,
				'urlApplyDB'=>self::_actionUri('apply',array('_time'=>microtime(true))),
				'urlSetDefaultEvent'=>self::_actionUri('setDefaultEvent',array('_time'=>microtime(true))),
				'urlAttachClient'=>self::_actionUri('attachClient', array('_return'=>'json')),
				'urlRemoveAttachedClient'=>self::_actionUri('removeAttachedClient', array('_return'=>'json')),
		));
// 		echo '<pre>';
// 		echo 'Default Event:';print_r($defaultEvent);
// 		echo 'Attach To:';print_r($clients);
// 		echo 'Action Event list:';print_r($actionEvents);
// 		echo '</pre>';
// 		echo sprintf('设置缺省事件&nbsp;&nbsp;Sleep下界:%s&nbsp;Sleep上界:%s<br>'
// 				,self::_componentsInput('ipt_sets_min_sleep',50,0)
// 				,self::_componentsInput('ipt_sets_max_sleep',50,0)
// 				,self::_componentsInput('ipt_sets_exception_prob',50,0)
// 				);
//		echo sprintf('<a href="%s">应用以上数据</a>',addslashes(self::_actionUri('apply')));
	}
	
	private static function _componentsInput($id,$width=50,$defaultValue=''){
		return sprintf('<input type="text" id="%1$s" name="%1$s" style="width:%2$dpx"/>',$id,$id,$width);
	}
	
	public function actionSetDefaultEvent($min_sleep, $max_sleep, $exception_prob){
		$min_sleep = intval($min_sleep);
		$max_sleep = intval($max_sleep);
		$exception_prob = intval($exception_prob);
		if ($min_sleep>30000 || $max_sleep>30000) {
			echo "Reject Your Submit, Causeed By: Sleep Time(random($min_sleep, $max_sleep) ms) is maybe more than 30s.";
		}elseif($min_sleep>$max_sleep){
			echo "Reject Your Submit, Causeed By: min_sleep($min_sleep ms) is larger than max_sleep($max_sleep ms).";
		}elseif($exception_prob > 100||$exception_prob < 0){
			echo "Reject Your Submit, Causeed By: exception_prob($exception_prob%) should be 0~1.";
		}else{
			$this->cReqDB->setDefaultEvent($min_sleep, $max_sleep, $exception_prob);
			echo "Success. DefaultEvent(min_sleep:$min_sleep, max_sleep:$max_sleep, exception_prob:$exception_prob) is set.<br>";
		}
		echo sprintf('<input type="button" value="Back" onclick="history.back()"/>');
	}
	
	public function actionApply(){
		$this->cReqDB->apply();
		echo 'Success.<br>';
		echo '<script type="text/javascript">if(typeof(parent.refreshCurrentCfg)=="function"){parent.refreshCurrentCfg('.json_encode(CReqUtil::config()).');}</script>';
		echo sprintf('<input type="button" value="Back" onclick="history.back()"/>');
		//echo sprintf('<a href="%s">返回</a>',addslashes(self::_actionUri('')));
	}
}

CReqPageDB::run();