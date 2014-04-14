<?php
namespace demos\components;

use Sky\base\Component;
use Sky\Sky;
use Sky\db\ConnectionPool;
class OptLog extends Component{
	public $logTable = 'tbl_log';
	/**
	 * @var ConnectionPool|string
	 */
	public $db = 'db';
	/*
	 * @see \Sky\base\Component::init()
	*/
	public function init()
	{
		if (is_string($this->db)) {
			$this->db = Sky::$app->getComponent($this->db);
		}
		if (!$this->db instanceof ConnectionPool) {
			throw new \Exception("DbLogRoute::db must be either a DB connection instance or the application component ID of a DB connection.");
		}

		Sky::$app->attachEventHandler('onEndRequest',array($this,'process'));
	}
	
	public function process()
	{
		$routeVar=Sky::$app->getUrlManager()->routeVar;
			
		if (!isset($_REQUEST[$routeVar]) || empty($_REQUEST[$routeVar])) {
			$route='null';
		}else
			$route=$_REQUEST[$routeVar];
		
		$username=$this->getUserName();
		$this->db->createCommand(
				sprintf('insert into %s (user_name,message)
						values (:username, :message)',addslashes($this->logTable)),
				array(
						'username'=>$username,
						'message'=>$route
				)
		)->exec();
	}
	
	protected function getUserName()
	{
		if(isset($_COOKIE['username']))
		{
			return $_COOKIE['username'];
		}else{
			return 'unknown';
		}
	}
}