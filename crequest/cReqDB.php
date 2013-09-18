<?php
require_once __DIR__.DIRECTORY_SEPARATOR.'cReqUtil.php';

class CReqDB{
	private $name;
	private $sqlite;
	const FETCH_LIST = 0;
	const FETCH_ROW = 1;
	const FETCH_VALUE = 2;
	
	public function __construct($name,$autoCreate = false){
		$this->name = $name;
		$filePath = __DIR__.DIRECTORY_SEPARATOR.CReqUtil::getDBPath($name);
		if (file_exists($filePath)) {
			$this->sqlite = new SQLite3($filePath);
		}else{
			if (!$autoCreate) throw new Exception("DB[$name] doesn't exists.");
			
			$this->sqlite = $db = new SQLite3($filePath);
			$db->exec("Begin transaction");
			$db->exec("CREATE TABLE tbl_events(action TEXT PRIMARY KEY, min_sleep NUMERIC, max_sleep NUMERIC, exception_prob NUMERIC)");
			$db->exec("CREATE TABLE tbl_clients(client TEXT PRIMARY KEY)");
			$this->setDefaultEvent(0, 0, 0);
			$db->exec("End transaction");
		}
		
	}
	
	public function __destruct(){
		$this->sqlite->close();
	}
	
	/**
	 * 
	 * @param int $min_sleep
	 * @param int $max_sleep
	 * @param int $exception_prob
	 */
	public function setDefaultEvent($min_sleep,$max_sleep,$exception_prob){
		$this->insertEventRecord("*", $min_sleep, $max_sleep, $exception_prob);
	}
	
	public function getDefaultEvent(){
		$sql = "select min_sleep,max_sleep,exception_prob from tbl_events where action = '*';";
		if ($result = $this->query($sql,self::FETCH_ROW)) {
			return $result;
		}else return array ('min_sleep' => 0,'max_sleep' => 0,'exception_prob' => 0);
	}
	
	public function getAllActionEvents(){
		$sql = "select action,min_sleep,exception_prob from tbl_events where action != '*';";
		$events = array();
		foreach ($this->query($sql) AS $eRow){
			$events[$eRow['action']] = array(
					'sleep' => $eRow['min_sleep'],
					'exception' => $eRow['exception_prob']?true:false
			);
		}
		return $events;
	}
	
	/**
	 * 
	 * @param String $action
	 * @param int $sleep
	 * @param boolean $exception
	 */
	public function setEvent($action, $sleep, $exception){
		$this->insertEventRecord($action, $sleep, $sleep, $exception?100:0);
	}
	
	/**
	 * 
	 * @param String|array $clients
	 * @return boolean
	 */
	public function attachTo($clients){
		if (is_string($clients)) {
			$clients = array($clients);
		}
		$sql = "insert or ignore into tbl_clients(client) values(?);";
		$this->beginTransaction();
		$this->batchExec($sql, $clients);
		$this->endTransaction();
		return true;
	}
	
	public function getAttachedClients(){
		$sql = "select client from tbl_clients;";
		$clients = array();
		foreach ($this->query($sql) AS $cRow){
			$clients[] = $cRow['client'];
		}
		return $clients;
	}
	
	public function removeAttach(array $clients){
		$sql = "delete from tbl_clients where client = ?;";
		return $this->batchExec($sql, $clients);
	}
	
	public function apply(){
		$defaultEvents = $this->getDefaultEvent();
		$actionEvents = $this->getAllActionEvents();
		$eventFile = CReqUtil::createEventsFile($this->name, $defaultEvents['min_sleep'], $defaultEvents['max_sleep'], $defaultEvents['exception_prob'],$actionEvents);
		foreach ($this->getAttachedClients() AS $client){
			CReqUtil::setConfig($client, array(
				'events' => $eventFile
			));
		}
	}
	
	private function insertEventRecord($action,$min_sleep,$max_sleep,$exception_prob){
		$sql = "replace into tbl_events(action,min_sleep,max_sleep,exception_prob) values(?,?,?,?);";
		$this->exec($sql, array($action,$min_sleep,$max_sleep,$exception_prob));
		return true;
	}
	
	//[Package Sqlite Function-->
	private function checkSqliteError(){
		if($this->sqlite->lastErrorCode()){
			throw new Exception($this->sqlite->lastErrorMsg(),$this->sqlite->lastErrorCode());
		}
	}
	
	private function beginTransaction(){
		$this->sqlite->exec("Begin transaction");
	}
	
	private function endTransaction(){
		$this->sqlite->exec("End transaction");
	}
	
	private function query($sql,$fetchMode = self::FETCH_LIST,$bindParams = array()){
		if (($stmt = $this->sqlite->prepare($sql))===false) {
			$this->checkSqliteError();
			throw new Exception("Failed to prepare sql: [$sql].");
			return array();
		}
		
		foreach ($bindParams AS $key=>$value){
			$stmt->bindValue(is_int($key)?$key+1:$key, $value);
		}
		$result = $stmt->execute();
		switch ($fetchMode){
		case self::FETCH_ROW:
			$return = $result->fetchArray(SQLITE3_ASSOC);
			if (!$return) {
				$return = null;
			}
			break;
		case self::FETCH_VALUE:
			$return = $result->fetchArray(SQLITE3_NUM);
			$return = count($return)?$return[0]:null;
			break;
		default:
			$return = array();
			while ($row=$result->fetchArray(SQLITE3_ASSOC)){
				array_push($return, $row);
			}
			break;
		}

		$result->finalize();
		$stmt->close();
		$this->checkSqliteError();
		return $return;
	}
	
	private function exec($sql,$bindParams = array()){
		if (($stmt = $this->sqlite->prepare($sql))!==false) {
			foreach ($bindParams AS $key=>$value){
				$stmt->bindValue(is_int($key)?$key+1:$key, $value);
			}
			$stmt->execute()->finalize();
			$stmt->close();
			$this->checkSqliteError();
			return true;
		}else{
			$this->checkSqliteError();
			throw new Exception("Failed to prepare sql: [$sql].");
			return false;
		}
	}
	
	private function batchExec($sql,$bindParamsArr,$stepCheckError = false){
		if (($stmt = $this->sqlite->prepare($sql))===false) {
			$this->checkSqliteError();
			throw new Exception("Failed to prepare sql: [$sql].");
			return false;
		}
		
		foreach ($bindParamsArr AS $bindParams){
			$stmt->reset();
			if (is_array($bindParams)) {
				foreach ($bindParams AS $key=>$value){
					$stmt->bindValue(is_int($key)?$key+1:$key, $value);
				}
			}else $stmt->bindValue(1, $bindParams);
			$stmt->execute();
			if ($stepCheckError) $this->checkSqliteError();
		}
		$stmt->close();
		if (!$stepCheckError) $this->checkSqliteError();
		return true;
	}
	//<--Package Sqlite Function]
}