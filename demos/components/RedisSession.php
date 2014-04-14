<?php
namespace demos\components;

use Sky\web\SSession;
use Sky\Sky;
use Sky\utils\RedisClient;
/**
 * 使用Redis存储session
 * @author Jiangyumeng
 *
 */
class RedisSession extends SSession{
	/**
	 * @var string|Redis
	 */
	public $redis='redis';
	/**
	 * @var string 通过临时会话解析出的mac
	 */
	private $_temMac='';
	/**
	 * @var boolean 是否是临时会话
	 */
	private $_temSession=false;
	
	public function init()
	{
		if (is_string($this->redis)) {
			$this->redis = Sky::$app->getComponent($this->redis);
		}
		if (!$this->redis instanceof RedisClient) {
			throw new \Exception("RedisSession::redis must be either a redis instance or the application component ID of a redis.");
		}
		
		parent::init();
	}
	
	/**
	 * 获取客户端提交的session id
	 * @return string 客户端提交的sessionid
	 */
	protected function getClientSessionId()
	{
		if (isset($GLOBALS['session']) && $GLOBALS['session']) {
			return $GLOBALS['session'];
		}elseif($this->getId()!=''){
			return $this->getId();
		}else
			return null;
	}
	
	/**
	 * 生成session id
	 * @return string
	 */
	protected function generateID()
	{
		$ip=Sky::$app->getRequest()->getUserHostAddress();
		return md5($ip.microtime(true).rand(100000,999999999999));
	}
	
	/**
	 * 从数据库中读取指定sessionid 的session内容
	 * @param string $id Session id
	 * @return array
	 */
	protected function sessionRead($id)
	{
		if ($this->isTempSession($id)) {
			return array(TvInfo::MAC=>$this->_temMac,TvInfo::IP=>'127.0.0.1',TvInfo::USERID=>0);
		}
		return $this->redis->hashGet($id, null, 2);
	}
	
	/**
	 * 将session数据写入数据库
	 * @param string $id session id
	 * @param mixed $value
	 */
	protected function sessionWrite($id,$value)
	{
		if ($this->_temSession || !isset($value[TvInfo::USERID])) {
	 		return ;
	 	}
		$this->redis->tranStart();
		$this->redis->hashSet($id, $value);
		$this->redis->setKeyExpire($id, $this->lifeTime);
		$ret=$this->redis->tranCommit();
		var_dump($ret);
	}
	
	/**
	 * 从数据库中删除指定session id的会话
	 * @param string $id
	 */
	protected function sessionDestroy($id)
	{
		$this->redis->delete(array($id));
		$this->setId('');
		$this->emtpySessionArray();
	}
	
	/*
	 * session回收
	* @see \Sky\web\SSession::sessionGC()
	*/
	protected function sessionGC()
	{
		
	}
	
	/**
	 * 验证session合法性
	 * @return boolean
	 */
	protected function badSession()
	{
		if ($this->_temSession) {
			return false;
		}
		 
		$uid=isset($this[TvInfo::USERID])?$this[TvInfo::USERID]:null;
		if (!empty($uid)) {
			return false;
		}else
			return true;
	}
	
	/**
	 * 判断会话是否是临时会话
	 * @param string $id Session id
	 * @return boolean
	 */
	protected function isTempSession($id)
	{
		if (($pos=strpos($id, ':'))!==false) 
		{
			$this->_temMac=substr($id, $pos+1);
			$this->_temSession=true;
			return true;
		}else{
			return false;
		}
	}
	
	/**
	 * 当前会话是否为临时绘画
	 * @return boolean 
	 */
	public function isTemp()
	{
		return $this->_temSession;
	}
}