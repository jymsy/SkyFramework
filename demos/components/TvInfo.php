<?php
namespace demos\components;

use Sky\base\Component;
use Sky\Sky;
use Sky\db\ConnectionPool;
/**
 * @author Jiangyumeng
 *
 */
class TvInfo extends Component{
	const USERID='user_id';
	const IP='ss_ip';
	const MAC='dev_mac';
	private static $_cache;
	public $keyPref='tianci.tvinfo:';
	public $timeout=3600;
	/**
	 * @var ConnectionPool|string
	 */
	public $db = 'db';
	/**
	 * @var RedisSession
	 */
	protected $_session;
	
	public function init()
	{
		if (is_string($this->db)) {
			$this->db = Sky::$app->getComponent($this->db);
		}
		if (!$this->db instanceof ConnectionPool) {
			throw new \Exception("SkySession::db must be either a DB connection instance or the application component ID of a DB connection.");
		}
		
		$this->_session=Sky::$app->session;
		$this->_session->open();
	}
	
	/**
	 * 通过session获取，电视信息
	 * mac,chip,model,platform,barcode,screen_size,system_version
	 * @return array 电视信息
	 */
	public function getTVInfo()
	{
		$mac=$this->_session[self::MAC];
		if ($this->_session->isTemp()) {
			return $this->getTVinfoByMac($mac);
		}
			
		$adminMac='';
		if (empty($mac)) {
			$adminMac=$this->getAdminMac($this->_session[self::USERID]);
		}
		$mac=empty($mac)?$adminMac:$mac;
		if(self::getCache() && ($tvinfo=self::getCache()->get($this->keyPref.$mac))!==false){
			return $tvinfo;
		}
		
		$tvinfo=$this->db->createCommand(
				'select bd.dev_mac,bd.chip,bd.model,bd.platform,bd.barcode,bd.screen_size,bd.system_version
					from `skyg_base`.`base_device` as bd
					where bd.dev_mac=:mac',
				array(
						'mac'=>$mac
				)
		)->toList();
			
		if(count($tvinfo)){
			if (self::getCache())
				self::getCache()->set($this->keyPref.$mac, $tvinfo[0],$this->timeout);
			return $tvinfo[0];
		}else
			return $tvinfo;
	}
	
	/**
	 * 通过子用户id查询电视mac
	 * @param integer $userId 子用户id
	 * @return string 电视mac，如果没有的话返回空字符串。
	 */
	private function getAdminMac($userId)
	{
		return $this->db->createCommand(
				'SELECT dev_mac FROM `skyg_base`.`base_dev_user_map` where user_id=
	 			(SELECT main_user_id from `skyg_base`.`base_user_user_map` where sub_user_id=:userId)',
				array('userId'=>$userId)
		)->toValue();
	}
	
	/**
	 * 通过mac查询tvinfo
	 * @param string $mac
	 * @return array tvinfo
	 */
	private function getTVinfoByMac($mac)
	{
		if(self::getCache() && ($tvinfo=self::getCache()->get($this->keyPref.$mac))!==false){
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
				self::getCache()->set($this->keyPref.$mac, $tvinfo[0],$this->timeout);
			return $tvinfo[0];
		}else
			return $tvinfo;
	}
	
	private static function getCache()
	{
		if (self::$_cache==null) {
			self::$_cache=Sky::$app->cache;
		}
		return self::$_cache;
	}
}