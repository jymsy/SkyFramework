<?php

namespace base\upgrade\models;

use Sky\db\DBCommand;

/**
 * @author Zhengyun
 */
class UpgradeClientModel extends \Sky\db\ActiveRecord{
	/**
	 *@return UpgradeClientModel
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}	
			
	/**查找匹配的升级包信息
	 * 
	 * @param String $core_style
	 * @param String $core_chip
	 * @param String $mac
	 * @param String $area
	 * @param String $appName
	 * @param Int $appVersion
	 * @return array
	 */
	public static function getUpgradeVesionInfo($core_style,$core_chip,$mac,$appName,$appVersion){
		$sql ='SELECT 
				  core_style,
				  core_chip,
				  init_version,
				  final_version,
				  download_url,
				  `md5`,
				  filesize,
				  `desc`,
				  introduce_page 
				FROM
				  `skyg_base`.`base_upgrade_info` 
				WHERE (
				    `init_version` = '.$appVersion.' 
				    OR `init_version` = 0
				  ) 
				  AND `core_style` LIKE "%'.addslashes($core_style).'%" 
				  AND `core_chip` LIKE "%'.addslashes($core_chip).'%" 
				  AND "'.addslashes($mac).'" BETWEEN `mac_start` 
				  AND `mac_end` 
				  AND `package_owner` = "'.addslashes($appName).'" 
				  AND  `final_version`> '.$appVersion.' 
				ORDER BY `final_version` DESC,
				  `init_version` DESC 
				LIMIT 1 ';		
		$result=parent::createSQL($sql)->toList();
		if($result!=null)
			$result=$result[0];
		return $result;
		
	}
	
	
	/**通过upgrade_id,mac地址查找符合条件模块升级包信息
	 *
	 * @param Int $upgrade_id
	 * @return multitype:
	 */
	public static function getModuleUpgradeLists($upgrade_id,$mac){
		$result=parent::createSQL(
				"SELECT
				  `module_name`,
				  `module_type`,
				  `module_version`,
				  `download_url`,
				  `is_enforce`,
				  `md5`,
				  `desc`,
				  `filesize`,
    			  `icon`,
    			  `bag_name`
				FROM
				  `skyg_base`.`base_upgrade_module_info`
    			WHERE `upgrade_id`=:upgrade_id 
				AND :mac BETWEEN `mac_start` AND `mac_end`",
				array(
						'upgrade_id'=>$upgrade_id,
				        'mac'=>$mac)
		)->toList();
		return $result;
	}
	
	/**通过mac查找全量升级包id
	 * 
	 * @param string $dev_mac
	 * @return Int
	 */
	public static function getUpgradeIdByMac($dev_mac){
		$result=parent::createSQL(
				"SELECT 
				  bui.`upgrade_id` 
				FROM
				  `skyg_base`.`base_device` AS bd 
				  JOIN `skyg_base`.`base_upgrade_info` AS bui 
				    ON (
				      bd.`chip` = bui.`core_chip` 
				      AND bd.`model` = bui.`core_style` 
				      AND bd.`system_version` = bui.`final_version`
				    ) 
				WHERE bui.`init_version` = 0 
				  AND bd.`dev_mac` = :dev_mac ",
				array(
						"dev_mac"=>$dev_mac
				)
		)->toValue();	
	
		return $result;
	}
	

	/**查找dtv升级包id
	 * 
	 * @param string $dtv_version
	 * @param string $dtv_code
	 * @return array
	 */
	public static function getDtvUpgradeInfo($dtv_version,$dtv_code,$hw_version){
		$result=parent::createSQL(
				"SELECT 
				  `dtv_name`,
				  `dtv_code`,
				  `dtv_version`,
				  `download_url`,
				  `md5`,
				  `filesize` ,
				  `hw_version`
				FROM
				  `skyg_base`.`base_upgrade_dtv_info` 
				WHERE `dtv_code` = :dtv_code 
				  AND `dtv_version` != :dtv_version 
				  AND `hw_version`=:hw_version ",
				array(
						"dtv_code"=>$dtv_code,
						"dtv_version"=>$dtv_version,
						"hw_version"=>(int)$hw_version
				)
		)->toList();
	
		return $result;
	}
	
	
	
	/**查找IPTV升级包信息
	 * 
	 * @param Int $iptv_package_version
	 * @param string $session
	 * @return 
	 */
	public static function getIPTVUpgradeInfo($iptv_package_version,$area_name,$core_style,$core_chip){
		$sql=sprintf("SELECT 
				  buip.`iptv_package_id`,
				  buip.`iptv_package_name`,
				  buip.`iptv_package_icon`,
				  buip.`core_style`,
				  buip.`core_chip`,
				  buip.`area_id`,
				  buip.`iptv_package_version`,
				  buip.`download_url`,
				  buip.`md5`,
				  buip.`filesize` 
				FROM
				  `skyg_base`.`base_upgrade_iptv_package` buip 
				  JOIN skyg_base.`base_area` ba 
				    ON buip.`area_id` = ba.`area_id` 
				WHERE buip.`core_chip` = '%s' 
				  AND buip.`core_style` = '%s' 
				  AND ba.`area_name` LIKE  '%s%%'
				  AND buip.`iptv_package_version` > %d 
				ORDER BY buip.`iptv_package_version` desc
				LIMIT 1 ",$core_chip,$core_style,$area_name,$iptv_package_version);
		
				
		$result=parent::createSQL($sql)->toList();	
		return $result;
	} 

	/**查找IPTV升级包信息
	 *
	* @param Int $iptv_package_version
	* @param string $session
	* @return
	*/
	public static function getIPTVUpgradeInfo2($area_name,$core_style,$core_chip){
		$sql=sprintf("SELECT
				  buip.`iptv_package_id`,
				  buip.`iptv_package_name`,
				  buip.`iptv_package_icon`,
				  buip.`core_style`,
				  buip.`core_chip`,
				  buip.`area_id`,
				  buip.`iptv_package_version`,
				  buip.`download_url`,
				  buip.`md5`,
				  buip.`filesize`
				FROM
				  `skyg_base`.`base_upgrade_iptv_package` buip
				  JOIN skyg_base.`base_area` ba
				    ON buip.`area_id` = ba.`area_id`
				WHERE buip.`core_chip` = '%s'
				  AND buip.`core_style` = '%s'
				  AND ba.`area_name` LIKE  '%s%%'
				ORDER BY buip.`iptv_package_version` desc   
				LIMIT 1 ",$core_chip,$core_style,$area_name); 
		$result=parent::createSQL($sql)->toList();
		return $result;
	}
	
	
    
	
}