<?php

namespace upgrade\models;

use Sky\db\DBCommand;
use skyosadmin\components\PublicModel;

/**
 * @author Zhengyun
 */
class UpgradeModel extends \Sky\db\ActiveRecord{
	/**
	 *@return UpgradeModel
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}	
	
	//protected static $tableName="skyg_res.res_advert";
	//protected static $primeKey=array("ad_id");
	
	
	
	/**
	 * 
	 * @param array $arr包括$upgrade_id,$module_name,$download_url,$module_version,
	 *                      $module_type,$is_enforce,$md5,$icon,$bag_name
	 * @return unknown|number
	 */
    public static function insertModuleUpgrade($arr){ 
    	extract($arr);
    	$result=parent::createSQL(
    			"INSERT INTO `skyg_base`.`base_upgrade_module_info` (
				  `upgrade_id`,
				  `module_name`,
				  `module_type`,
				  `module_version`,
				  `download_url`,
				  `is_enforce`,
    			  `md5`,
    			  `filesize`,
    			  `mac_start`,
    			  `mac_end`,
    			  `desc`,
    			  `icon`,
    			  `bag_name`
				) 
				VALUES
				  (
				    :upgrade_id,
				    :module_name,
				    :module_type,
				    :module_version,
				    :download_url,
				    :is_enforce,
    			    :md5,
    			    :filesize,
    			    :mac_start,
    			    :mac_end,
    			    :desc,
    			    :icon,
    			    :bag_name
				  )",
    			array(
    					'upgrade_id'=>$upgrade_id,
    					'module_name'=>$module_name,
    					'module_type'=>$module_type,
    					'module_version'=>$module_version,
    					'download_url'=>$download_url,
    					'is_enforce'=>$is_enforce,
    					'md5'=>$md5,
    					'filesize'=>$filesize,
    					'mac_start'=>$mac_start,
    					'mac_end'=>$mac_end,
    					'desc'=>$desc,
    					'icon'=>$icon,
    					'bag_name'=>$bag_name
    				)
    		);
    	if($result->exec()!=0){
			$result->getPdoInstance();
			$result=$result->lastInsertID();
			return $result;
		}
		return 0;
    	
    }
    
    /**
     * 
     * @param array $arr包括$upgrade_id,$module_name,$download_url,$module_version,
     *                      $module_type,$is_enforce,$md5,$upgrade_module_id
     * @return unknown
     */
    public static function updateModuleUpgrade($arr){
    	extract($arr);
    	$result=parent::createSQL(
    			"UPDATE 
				  `skyg_base`.`base_upgrade_module_info` 
				SET
				  `upgrade_id` = :upgrade_id,
				  `module_name` = :module_name,
				  `module_type` = :module_type,
				  `module_version` = :module_version,
				  `download_url` = :download_url,
				  `is_enforce` = :is_enforce ,
    			  `md5`=:md5,
    			  `filesize`=:filesize,
    			  `mac_start`=:mac_start,
    			  `mac_end`=:mac_end ,
    			  `desc`=:desc ,
    			  `icon`=:icon,
    			  `bag_name`=:bag_name 
				WHERE `upgrade_module_id` = :upgrade_module_id ",
    			array(
    					'upgrade_id'=>$upgrade_id,
    					'module_name'=>$module_name,
    					'module_type'=>$module_type,
    					'module_version'=>$module_version,
    					'download_url'=>$download_url,
    					'is_enforce'=>$is_enforce,
    					'md5'=>$md5,
    					'filesize'=>$filesize,
    					'upgrade_module_id'=>$upgrade_module_id,
    					'mac_start'=>$mac_start,
    					'mac_end'=>$mac_end,
    					'desc'=>$desc,
    					'icon'=>$icon,
    					'bag_name'=>$bag_name
    			)
    	)->exec();
    	
    	return $result;
    }
    
    /**
     * 
     * @param Int $upgrade_module_id
     * @return unknown
     */
    public static function deleteModuleUpgrade($upgrade_module_id){
    	$result=parent::createSQL(
    			"DELETE 
				FROM
				  `skyg_base`.`base_upgrade_module_info` 
				WHERE `upgrade_module_id` = :upgrade_module_id ",
    			array(
    					'upgrade_module_id'=>$upgrade_module_id
    			)
    	)->exec();
    	 
    	return $result;
    }
    
    /**
     * 
     * @param Array $searchCondition e.g. array('product_name'=>'GOOGLE','product_owner_name'=>'RSR')
     * @param Int $start
     * @param Int $limit
     * @param Array $orderCondition e.g. array("upgrade_module_id"=>"DESC")
     * @return multitype:
     */
    public static function searchModuleUpgrade($upgrade_id,$searchCondition,$start,$limit,$orderCondition=array("createtime"=>"DESC")){
    	$orderString=PublicModel::controlArray($orderCondition);
    	$searchString=PublicModel::controlsearch($searchCondition);
    	if($searchString!='')
    		$searchString=' AND  '.$searchString;
    	$sql=sprintf(
				"SELECT
    			  `upgrade_id`,
    			  `upgrade_module_id`,
				  `module_name`,
				  `module_type`,
				  `module_version`,
				  `download_url`,
				  `is_enforce`,
    			  `md5`,
    			  `filesize`,
    			  `mac_start`,
    			  `mac_end`,
    			  `desc`,
    			  `icon`,
    			  `bag_name`
				FROM
				  `skyg_base`.`base_upgrade_module_info` 
    			WHERE `upgrade_id`=%d
    			%s
				ORDER BY %s 
				LIMIT %d,%d",$upgrade_id,$searchString,$orderString,$start,$limit);
		$result=parent::createSQL($sql)->toList();
		return $result;    	
    	
    }
    
    /**
     * 
     * @param Int $upgrade_id
     * @param array $searchCondition
     * @return Ambigous <NULL, unknown>
     */
    public static function searchModuleUpgradeCount($upgrade_id,$searchCondition){
    	$searchString=PublicModel::controlsearch($searchCondition);
    	if($searchString!='')
    		$searchString=' AND  '.$searchString;
    	$sql=sprintf(
    			"SELECT
    			  count(*)
				FROM
				  `skyg_base`.`base_upgrade_module_info`
    			WHERE `upgrade_id`=%d
    			%s
				",$upgrade_id,$searchString);
    	$result=parent::createSQL($sql)->toValue();
    	return $result;
    	 
    }
    
    /**
     * 
     * @param Int $upgrade_id
     * @return multitype:
     */
    public static function getModuleUpgradeLists($upgrade_id,$start,$limit,$orderCondition=array('createtime'=>'DESC')){
    	$orderString=PublicModel::controlArray($orderCondition);
    	$sql=sprintf(
    			"SELECT
    			  `upgrade_id`,
    			  `upgrade_module_id`,
				  `module_name`,
				  `module_type`,
				  `module_version`,
				  `download_url`,
				  `is_enforce`,
    			  `md5`,
    			  `filesize`,
    			  `mac_start`,
    			  `mac_end`,
    			  `desc`,
    			  `icon`,
    			  `bag_name`
				FROM
				  `skyg_base`.`base_upgrade_module_info` 
    			WHERE `upgrade_id`=%d
    			ORDER BY %s 
				LIMIT %d,%d",$upgrade_id,$orderString,$start,$limit);
    	$result=parent::createSQL($sql)->toList();
    	return $result;
    	 
    }
    
    public static function getModuleUpgradeCount($upgrade_id){
    	$sql=sprintf(
    			"SELECT
				  count(*)
				FROM
				  `skyg_base`.`base_upgrade_module_info`
    			WHERE `upgrade_id`=%d",$upgrade_id);
    	$result=parent::createSQL($sql)->toValue();
    	return $result;
    
    }
    
    /**
     * 更新升级模块包下载地址
     * @param Int $id
     * @param String $download_url
     * @return number
     */
    public static function updateUpgradeModDownloadUrl($id,$download_url) {
    	$result=parent::createSQL(
    			"update `skyg_base`.`base_upgrade_module_info` set `download_url`=:download_url where `upgrade_module_id`=:id",
    			array(
    					'download_url'=>$download_url,
    					'id'=>(int)$id
    			)
    	)->exec();
    	return $result;
    }
    
    /*
     * SysUpgrade
    */
    
    /**
     * 
     * @param string $area
     * @param Int $start
     * @param Int $pagesize
     * @return multitype:
     */
    public static function getSysUpgrade($start,$pagesize,$orderCondition=array('createtime'=>'DESC')) {
    	$orderString=PublicModel::controlArray($orderCondition);
    	$sql=sprintf(
    			"SELECT 
    			    `upgrade_id`,
				  	`core_style`,
					`core_chip`,
					`init_version`,
					`final_version`,
					`download_url`,
					`mac_start`,
					`mac_end`,
					`md5`,
					`package_type`,
					`package_owner`,
					`area`,
					`is_import`,
					`filesize`,
					`platform`,
					`bag_type`,
					`screen_size`,
					`thirdparty_info`,
					`desc` ,
					`introduce_page` 
				FROM
				  `skyg_base`.`base_upgrade_info`
				ORDER BY %s 
				LIMIT %d,%d ",$orderString,$start,$pagesize);
    	$result=parent::createSQL($sql)->toList();
    	return $result;
    }
    
    /**
     * 
     * @param String $area
     * @param Int $start
     * @param Int $pagesize
     * @return Ambigous <NULL, unknown>
     */
    public static function getSysUpgradeCount() {
    	$result=parent::createSQL(
    			"SELECT 
				  count(*)
				FROM
				  `skyg_base`.`base_upgrade_info`"
	    	)->toValue();
    	return $result;
    }
    
    /**
     * 
     * @param Int $id
     * @return number
     */
    public static function deleteSysUpgrade($id) {
    	$result1=parent::createSQL(
    			"delete from `skyg_base`.`base_upgrade_info`  where `upgrade_id`=:id",
    	      array(
    	      		'id'=>(int)$id
    	      		)
    		)->exec();
    	
    	$result2=parent::createSQL(
    			"delete from `skyg_base`.`base_upgrade_module_info`  where `upgrade_id`=:id",
    			array(
    					'id'=>(int)$id
    			)
    	)->exec();
    	
    	if(($result1==0)&&($result2==0))
    		return 0;
    	
    	return 1;
    }
    
    
    /**
     * 更新升级包说明文件
     * @param Int $id
     * @param String $introduce_page
     * @return number
     */
    public static function updateSysUpgradeIntroduce($id,$introduce_page) {
    	$result=parent::createSQL(
    			"update `skyg_base`.`base_upgrade_info` set `introduce_page`=:introduce_page where `upgrade_id`=:id",
    		array(
    				'introduce_page'=>$introduce_page,
    				'id'=>(int)$id
    				)
    		)->exec();
    	return $result;
    }
    
    /**
     * 更新升级包下载地址
     * @param Int $id
     * @param String $download_url
     * @return number
     */
    public static function updateSysUpgradeDownloadUrl($id,$download_url) {
    	$result=parent::createSQL(
    			"update `skyg_base`.`base_upgrade_info` set `download_url`=:download_url where `upgrade_id`=:id",
    			array(
    					'download_url'=>$download_url,
    					'id'=>(int)$id
    			)
    	)->exec();
    	return $result;
    }
    
    /**通过id查找升级包信息
     * 
     * @param Int $id
     * @return multitype:
     */
    public static function getSysUpgradeById($id) {
    	$result=parent::createSQL(
    			"SELECT
    			    `upgrade_id`,
				  	`core_style`,
					`core_chip`,
					`init_version`,
					`final_version`,
					`download_url`,
					`mac_start`,
					`mac_end`,
					`md5`,
					`package_type`,
					`package_owner`,
					`area`,
					`is_import`,
					`filesize`,
					`platform`,
					`bag_type`,
					`screen_size`,
					`thirdparty_info`,
					`desc` ,
					`introduce_page`
				FROM
				  `skyg_base`.`base_upgrade_info`
				WHERE upgrade_id=:id ",
    			array(
    					'id'=>(int)$id
    			)
    	)->toList();
    	
    	return $result;
    }
    
    /**更新升级包全属性
     * 
     * @param String $mac_start
     * @param String $mac_end
     * @param int $id
     * @return number
     */
    public static function updateSysUpgrade($arr) {
    	extract($arr);
    	$result=parent::createSQL(
    			"UPDATE 
				  `skyg_base`.`base_upgrade_info` 
				SET
				  `core_style` = :core_style,
				  `core_chip` = :core_chip,
				  `init_version` = :init_version,
				  `final_version` = :final_version,
				  `mac_start` = :mac_start,
				  `mac_end` = :mac_end,
				  `area` = :area,
				  `platform` = :platform,
				  `bag_type` = :bag_type,
				  `screen_size` = :screen_size,
				  `thirdparty_info` = :thirdparty_info,
				  `desc` = :desc,
				  `introduce_page` = :introduce_page 
				WHERE `upgrade_id` = :upgrade_id ",
    			array(
    					'core_style'=>$core_style,
						'core_chip'=>$core_chip,
						'init_version'=>(int)$init_version,
						'final_version'=>(int)$final_version, 
						'mac_start'=>$mac_start,
						'mac_end'=>$mac_end, 
						'area'=>$area, 
						'platform'=>$platform,
						'bag_type'=>$bag_type,
						'screen_size'=>$screen_size,
						'thirdparty_info'=>$thirdparty_info,
						'desc'=>$desc, 
						'introduce_page'=>$introduce_page,
    					'upgrade_id'=>$upgrade_id
    					)
    			)->exec();
    	
    	return $result;
    }
    
    
    /**添加升级包
     * 
     * @param array $arr include：$core_style,$core_chip,$init_version,$final_version,
     * 							  $download_url,$mac_start,$mac_end,$md5,$package_type,
     *                            $package_owner,$area,$is_import,$filesize,$platform ,
     *                            $bag_type,$screen_size,$thirdparty_info,$desc
     * @return unknown|number
     */
     public static function insertSysUpgrade($arr) {
    	extract($arr);
    	$result=parent::createSQL(
    			"INSERT INTO `skyg_base`.`base_upgrade_info` (
	    			`core_style`,
	    			`core_chip`,
	    			`init_version`,
	    			`final_version`,
	    			`download_url`,
	    			`mac_start`,
	    			`mac_end`,
	    			`md5`,
	    			`package_type`,
	    			`package_owner`,
	    			`area`,
	    			`is_import`,
	    			`filesize`,
	    			`platform`,
	    			`bag_type`,
	    			`screen_size`,
	    			`thirdparty_info`,
	    			`desc`
    			)
    			VALUES
    			(
	    			:core_style,
	    			:core_chip,
	    			:init_version,
	    			:final_version,
	    			:download_url,
	    			:mac_start,
	    			:mac_end,
	    			:md5,
	    			:package_type,
	    			:package_owner,
	    			:area,
	    			:is_import,
	    			:filesize,
	    			:platform ,
	    			:bag_type,
	    			:screen_size,
	    			:thirdparty_info,
	    			:desc
    			)",
    			array(
    					'core_style'=>$core_style,
						'core_chip'=>$core_chip,
						'init_version'=>$init_version,
						'final_version'=>$final_version,
						'download_url'=>$download_url,
						'mac_start'=>$mac_start,
						'mac_end'=>$mac_end,
						'md5'=>$md5,
						'package_type' =>$package_type,
						'package_owner'=>$package_owner,
						'area'=>$area,
						'is_import'=>$is_import,
						'filesize'=>$filesize,
						'platform'=>$platform ,
						'bag_type'=>$bag_type,
						'screen_size'=>$screen_size,
						'thirdparty_info'=>$thirdparty_info,
						'desc' =>$desc
    					)
    			);
    	if($result->exec()!=0){
    		$result->getPdoInstance();
    		$result=$result->lastInsertID();
    		return $result;
    	}	
    	
    	return 0;
    }
    
    /**导入升级包信息更新
     * 
     * @param Int $id
     * @param String $download_url
     * @param String $md5
     * @param Int $is_import
     * @return number
     */
    public static function importUpdateSys($id,$download_url,$md5,$is_import=0) {
    	$result=parent::createSQL(
    			"UPDATE 
				  `skyg_base`.`base_upgrade_info` 
				SET
				  `download_url` = :download_url,
				  `is_import` = :is_import,
				  `md5` = :md5
				WHERE `upgrade_id` = :id ",
    			array(
    					'download_url'=>$download_url,
    					'is_import'=>(int)$is_import,
    					'md5'=>$md5,
    					'id'=>(int)$id
    					)
    			)->exec();
    	return $result;
    }
    
    /**更新升级包信息
     * 
     * @param String $style
     * @param String $chip
     * @param Int $init_version
     * @param Int $final_version
     * @return number
     */
    public static function judgeSysUpgrade($style,$chip,$init_version,$final_version) {
    	$result=parent::createSQL(
    			"SELECT 
				  COUNT(*) 
				FROM
				  skyg_base.`base_upgrade_info` 
				WHERE `core_style` = :core_style 
				  AND `core_chip` = :core_chip 
				  AND `init_version` = :init_version
				  AND `final_version` = :final_version ",
    		array(
    				'core_style'=>$style,
    				'core_chip'=>$chip,
    				'init_version'=>(int)$init_version,
    				'final_version'=>(int)$final_version
    				)
    		)->toValue();
    	return $result;
    }
    
    /**查询升级包详情
     * 
     * @param array $searchCondition e.g. array('product_name'=>'GOOGLE','product_owner_name'=>'RSR')
     * @param Int $start
     * @param Int $limit
     * @param array $orderCondition e.g. array("upgrade_module_id"=>"DESC")
     * @return multitype:
     */
    public static function searchUpgradeInfo($searchCondition,$start,$limit,$orderCondition=array("createtime"=>"DESC")){
    	$orderString=PublicModel::controlArray($orderCondition);
    	$searchString=PublicModel::controlsearch($searchCondition);
    	if($searchString!='')
    		$searchString=' WHERE  '.$searchString;
    	$sql=sprintf(
    			"SELECT
    			    `upgrade_id`,
				    `core_style`,
					`core_chip`,
					`init_version`,
					`final_version`,
					`download_url`,
					`mac_start`,
					`mac_end`,
					`md5`,
					`package_type`,
					`package_owner`,
					`area`,
					`is_import`,
					`filesize`,
					`platform`,
					`bag_type`,
					`screen_size`,
					`thirdparty_info`,
					`desc` ,
					`introduce_page`
				FROM
				  `skyg_base`.`base_upgrade_info`
    			%s
				ORDER BY %s
				LIMIT %d,%d",$searchString,$orderString,$start,$limit);
    	$result=parent::createSQL($sql)->toList();
    	return $result;
    	 
    }
    
    /**查询升级包数量
     *
     * @param array $searchCondition e.g. array('product_name'=>'GOOGLE','product_owner_name'=>'RSR')
     * @param Int $start
     * @param Int $limit
     * @param array $orderCondition e.g. array("upgrade_module_id"=>"DESC")
     * @return multitype:
     */
    public static function searchUpgradeCount($searchCondition){
    	$searchString=PublicModel::controlsearch($searchCondition);
    	if($searchString!='')
    		$searchString=' WHERE  '.$searchString;
    	$sql=sprintf(
    			"SELECT
				  count(*)
				FROM
				  `skyg_base`.`base_upgrade_info`
    			%s ",$searchString);
    	$result=parent::createSQL($sql)->toValue();
    	return $result;
    
    }
    
    /**通过md5值查询升级包
     * 
     * @param String $md5
     * @return 0-升级包不存在, >0升级包已存在
     */
    public static function checkPackageExistByMD5($md5){
    	$result=parent::createSQL(
    			"SELECT
				  COUNT(*)
				FROM
				  skyg_base.`base_upgrade_info`
				WHERE `md5`=:md5 ",
    			array(
    					'md5'=>$md5
    			)
    	)->toValue();
    	return $result;    	
    }
    
    /**通过md5值查询模块升级包
     * 
     * @param String $md5
     * @return 0-模块升级包不存在, >0模块升级包已存在
     */
    public static function checkModuleExistByMD5($md5){
    	$result=parent::createSQL(
    			"SELECT
				  COUNT(*)
				FROM
				  skyg_base.`base_upgrade_module_info`
				WHERE `md5`=:md5 ",
    			array(
    					'md5'=>$md5
    			)
    	)->toValue();
    	return $result;
    	 
    }
    
    /**通过core_style,core_chip,init_version,final_version查询升级包是否存在
     * 
     * @param String $core_style
     * @param String $core_chip
     * @param int $init_version
     * @param int $final_version
     * @return 0-升级包不存在, >0升级包已存在
     */
    public static function checkpackageExist($core_style,$core_chip,$init_version,$final_version){
    	$result=parent::createSQL(
    			"SELECT
				  COUNT(*)
				FROM
				  skyg_base.`base_upgrade_info`
				WHERE `core_style`=:core_style 
				AND `core_chip`=:core_chip 
				AND `init_version`=:init_version 
				AND `final_version`=:final_version",
    			array(
    					'core_style'=>$core_style,
    					'core_chip'=>$core_chip,
    					'init_version'=>(int)$init_version,
    					'final_version'=>(int)$final_version
    			)
    	)->toValue();
    	return $result;
    
    }
    
    /**通过area过滤出区域升级包信息
     * 
     * @param unknown_type $area
     * @param unknown_type $start
     * @param unknown_type $limit
     * @param unknown_type $orderCondition
     * @return multitype:
     */
    public static function getSysUpgradeByArea($area,$start,$limit,$orderCondition=array('createtime'=>'DESC')) {
    	$orderString=PublicModel::controlArray($orderCondition);
    	$sql=sprintf(
    			"SELECT
    			    `upgrade_id`,
				  	`core_style`,
					`core_chip`,
					`init_version`,
					`final_version`,
					`download_url`,
					`mac_start`,
					`mac_end`,
					`md5`,
					`package_type`,
					`package_owner`,
					`area`,
					`is_import`,
					`filesize`,
					`platform`,
					`bag_type`,
					`screen_size`,
					`thirdparty_info`,
					`desc` 
				FROM
				  `skyg_base`.`base_upgrade_master_info`
				WHERE area='%s'  
    			ORDER BY %s 
    			limit %d,%d",$area,$orderString,$start,$limit);
    	$result=parent::createSQL($sql)->toList();
    	return $result;    
    }
    
    /**获取area区域升级包总数
     *
    * @param String $area
    * @param Int $start
    * @param Int $limit
    * @param array $orderCondition
    * @return int
    */
    public static function getSysUpgradeCountByArea($area) {
    	$sql=sprintf(
    			"SELECT
    			    count(*)
				FROM
				  `skyg_base`.`base_upgrade_master_info`
				WHERE area='%s'",$area);
    	$result=parent::createSQL($sql)->toValue();
    	return $result;
    }
    
    /**搜索区域升级包信息
     *
    * @param unknown_type $area
    * @param unknown_type $start
    * @param unknown_type $limit
    * @param unknown_type $orderCondition
    * @return multitype:
    */
    public static function searchSysUpgradeByArea($area,$start,$limit,$searchCondition,$orderCondition=array('createtime'=>'DESC')) {
    	$orderString=PublicModel::controlArray($orderCondition);
    	$searchString=PublicModel::controlsearch($searchCondition);
    	if($searchString!='')
    		$searchString=' AND  '.$searchString;
    	$sql=sprintf(
    			"SELECT
    			    `upgrade_id`,
				  	`core_style`,
					`core_chip`,
					`init_version`,
					`final_version`,
					`download_url`,
					`mac_start`,
					`mac_end`,
					`md5`,
					`package_type`,
					`package_owner`,
					`area`,
					`is_import`,
					`filesize`,
					`platform`,
					`bag_type`,
					`screen_size`,
					`thirdparty_info`,
					`desc` 
				FROM
				  `skyg_base`.`base_upgrade_master_info`
				WHERE area='%s'
    			 %s 
    			ORDER BY %s
    			limit %d,%d",$area,$searchString,$orderString,$start,$limit);
    	$result=parent::createSQL($sql)->toList();
    	return $result;
    }
    
    /**获取area区域升级包总数
     *
    * @param String $area
    * @param Int $start
    * @param Int $limit
    * @param array $orderCondition
    * @return int
    */
    public static function searchSysUpgradeCountByArea($area,$searchCondition) {
    	$searchString=PublicModel::controlsearch($searchCondition);
    	if($searchString!='')
    		$searchString=' AND  '.$searchString;
    	$sql=sprintf(
    			"SELECT
    			    count(*)
				FROM
				  `skyg_base`.`base_upgrade_master_info`
				WHERE area='%s' %s",$area,$searchString);
    	$result=parent::createSQL($sql)->toValue();
    	return $result;
    }
}