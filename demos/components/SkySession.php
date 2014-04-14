<?php
namespace demos\components;

use Sky\Sky;
use Sky\web\SSession;
use Sky\db\ConnectionPool;
/**
 * @see SSession
 * @author Jiangyumeng
 *
 */
class SkySession extends SSession{
	const USERID='user_id';
	const IP='ss_ip';
	const MAC='dev_mac';
	
	private static $_cache;
	/**
	 * @var int 验证会话结果的缓存时间
	 */
	const verifyTime=7200;
	/**
	 * @var int 临时会话在cache中的保存时间
	 */
	const temSessionLife=3600;
	/**
	 * @var ConnectionPool|string
	 */
	public $db = 'db';
	/**
	 * @var string 通过临时会话解析出的mac
	 */
	private $_temMac='';
	/**
	 * @var boolean 是否是临时会话
	 */
	private $_temSession=false;
	/**
	 * @var string Session表名
	 */
	public $sessionTable = 'skyg_base.base_session';
	
	public function init()
	{
		if (is_string($this->db)) {
			$this->db = Sky::$app->getComponent($this->db);
		}
		if (!$this->db instanceof ConnectionPool) {
			throw new \Exception("SkySession::db must be either a DB connection instance or the application component ID of a DB connection.");
		}
		parent::init();
	}
	
	/**
	 * 获取客户端提交的session id
	 * @return string 客户端提交的sessionid
	 */
	protected function getClientSessionId(){
		if (isset($GLOBALS['session']) && $GLOBALS['session']) {
			return $GLOBALS['session'];
		}else 
			return null;
	}

	/**
	 * 生成session id
	 * @return string
	 */
	protected function generateID(){
		$ip=Sky::$app->request->userHostAddress;
		return md5(md5($ip.microtime(true).rand(100000,999999999999)));
	}
	
	 /**
	  * 通过session获取，电视信息
	  * mac,chip,model,platform,barcode,screen_size,system_version
	  * @return array 电视信息 
	  */
	 public function getTVInfo(){
	 	if ($this->_temSession) {
	 		return $this->getTVinfoByMac($this->_temMac);
	 	}
	 	if(self::getCache() && ($tvinfo=self::getCache()->get(__METHOD__.$this->getId()))!==false){
	 		return $tvinfo;
	 	}
	 	
	 	$adminMac='';
	 	if (empty($this[self::MAC])) {
	 		$adminMac=$this->getAdminMac($this[self::USERID]);
	 	}
	 	$tvinfo=$this->db->createCommand(
	 			'select bd.dev_mac,bd.chip,bd.model,bd.platform,bd.barcode,bd.screen_size,bd.system_version
					from `skyg_base`.`base_device` as bd
					where bd.dev_mac=:mac',
	 			array(
	 					'mac'=>empty($this[self::MAC])?$adminMac:$this[self::MAC],
	 			)
	 	)->toList();
	 	
	 	if(count($tvinfo)){
	 		if (self::getCache()) 
	 			self::getCache()->set(__METHOD__.$this->getId(), $tvinfo[0],$this->lifeTime);
	 		return $tvinfo[0];
	 	}else
	 		return $tvinfo;
	 }
	 
	 /**
	  * 通过mac查询tvinfo
	  * @param string $mac
	  * @return array tvinfo
	  */
	 private function getTVinfoByMac($mac){
	 	if(self::getCache() && ($tvinfo=self::getCache()->get(__METHOD__.$mac))!==false){
	 		return $tvinfo;
	 	}
	 	
	 	$tvinfo=$this->db->createCommand(
	 			'select dev_mac,chip,model,platform,barcode,screen_size,system_version
					from `skyg_base`.`base_device` 
					where dev_mac=:mac',
	 			array(
	 					'mac'=>$mac,
	 			)
	 	)->toList();
	 	
	 	if(count($tvinfo)){
	 		if (self::getCache())
	 			self::getCache()->set(__METHOD__.$mac, $tvinfo[0],self::temSessionLife);
	 		return $tvinfo[0];
	 	}else
	 		return $tvinfo;
	 }
	 
	 /**
	  * 通过子用户id查询电视mac
	  * @param integer $userId 子用户id
	  * @return string 电视mac，如果没有的话返回空字符串。
	  */
	 private function getAdminMac($userId){
	 	return $this->db->createCommand(
	 			'SELECT dev_mac FROM `skyg_base`.`base_dev_user_map` where user_id=
	 			(SELECT main_user_id from `skyg_base`.`base_user_user_map` where sub_user_id=:userId)',
	 			array('userId'=>$userId)
	 	)->toValue();
	 }
	 
	 /**
	  * 判断会话是否是临时会话
	  * @param string $id Session id
	  * @return boolean
	  */
	 public function isTempSession($id){
	 	if (($pos=strpos($id, ':'))!==false) {
	 		$this->_temMac=substr($id, $pos+1);
	 		$this->_temSession=true;
	 		return true;
	 	}else{
	 		return false;
	 	}
	 }
	 
	 /**
	  * 从数据库中读取指定sessionid 的session内容
	  * @param string $id Session id
	  * @return array
	  */
	 protected function sessionRead($id){
	 	if ($this->isTempSession($id)) {
	 		return array(self::MAC=>$this->_temMac,self::IP=>'127.0.0.1',self::USERID=>0);
	 	}
	 	if(self::getCache() && ($sessArr=self::getCache()->get(__METHOD__.$id))!==false){
	 		return $sessArr;
	 	}
	 	$sessArr=$this->db->createCommand(
	 			'select `user_id`,`ss_ip`,`dev_mac` from '.$this->sessionTable.' where session_id=:id and ss_end_time>:now',
	 			array(
	 					'id'=>$id,
	 					'now'=>date('Y-m-d H:i:s'),
	 			)
	 	)->toList();
	 	if (count($sessArr)) {
	 		if (self::getCache()) {
	 			self::getCache()->set(__METHOD__.$id,$sessArr[0],$this->lifeTime);
	 		} 		
	 		return $sessArr[0];
	 	}else
	 		return $sessArr;
	 }
	 
	 /**
	  * 将session数据写入数据库
	  * @param string $id session id
	  * @param mixed $value
	  */
	 protected function sessionWrite($id,$value){
		if ($this->_temSession || !isset($value[self::USERID])) {
	 		return ;
	 	}
	 	$sid=$this->db->createCommand(
	 			'select `session_id` from '.$this->sessionTable.' where `session_id`=:id',
	 			array('id'=>$id)
	 	)->toValue();
	 	if (empty($sid)) {
	 		$this->db->createCommand(
	 				'insert into '.$this->sessionTable.' (`session_id`,`user_id`,`ss_ip`,`ss_start_time`,`ss_end_time`,`dev_mac`)
							VALUES(:session_id, :u_id,:ip,:beginTime,:endTime,:mac)',
	 				array(
	 						'session_id'=>$id,
	 						'u_id'=>isset($value[self::USERID])?$value[self::USERID]:0,
	 						'ip'=>isset($value[self::IP])?$value[self::IP]:'127.0.0.1',
	 						'beginTime'=>date('Y-m-d H:i:s'),
	 						'endTime'=>date('Y-m-d H:i:s',time()+$this->lifeTime),
	 						'mac'=>isset($value[self::MAC])?$value[self::MAC]:'',
	 				)
	 		)->exec();
	 	}else{
	 		$this->db->createCommand(
	 				'update '.$this->sessionTable.' set `ss_ip`=:ip,`dev_mac`=:mac where `session_id`=:session_id',
	 				array(
	 						'ip'=>isset($value[self::IP])?$value[self::IP]:'127.0.0.1',
	 						'mac'=>isset($value[self::MAC])?$value[self::MAC]:'',
	 						'session_id'=>$id,
	 				)
	 		)->exec();
	 	}
	 }
	 
	/**
	 * 验证session合法性
	 * @return boolean
	 */
	 protected function badSession(){
	 	if ($this->_temSession) {
	 		return false;
	 	}
// 	 	if(!self::getCache() || ($uid=self::getCache()->get(__METHOD__.$this->getId()))===false){
// 	 		$uid=$this->db->createCommand(
// 	 				'select `user_id` from '.$this->sessionTable.' where `session_id`=:id',
// 	 				array('id'=>$this->getId())
// 	 		)->toValue();
// 	 		if (self::getCache()) {
// 	 			self::getCache()->set(__METHOD__.$this->getId(),$uid,self::verifyTime);
// 	 		}
// 	 	} 	
	 	
	 	$uid=isset($this[self::USERID])?$this[self::USERID]:null;
	 	if (!empty($uid)) {
	 		return false;
	 	}else
	 		return true;
	 }
	 
	 /**
	  * 从数据库中删除指定session id的会话
	  * @param string $id
	  */
	 protected function sessionDestroy($id){
	 	$this->db->createCommand(
	 			'delete from '.$this->sessionTable.' where `session_id`=:id',
	 			array('id'=>$id)
	 	)->exec();
	 	
	 	$this->setId('');
	 	$this->emtpySessionArray();
	 }
	 
	 private static function getCache(){
	 	if (self::$_cache==null) {
	 		self::$_cache=Sky::$app->cache;
	 	}
	 	return self::$_cache;
	 }
	 
	 /* 
	  * session回收
	  * @see \Sky\web\SSession::sessionGC()
	  */
	 protected function sessionGC(){
	 	if (date('H') == '05' && mt_rand(1, 10) == 1) {
	 		$this->db->createCommand(
	 				'delete from '.$this->sessionTable.' where `ss_end_time` < :time',
	 				array('time'=>date('Y-m-d H:i:s'))
	 		)->exec();
	 	}
	 }
}