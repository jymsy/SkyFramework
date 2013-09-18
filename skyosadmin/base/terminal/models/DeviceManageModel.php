<?php

namespace base\terminal\models;

use Sky\base\Component;

use Sky\db\DBCommand;
use skyosadmin\components\PublicModel;

/**table 
 */
class DeviceManageModel extends \Sky\db\ActiveRecord{
	/**
	 *@return DeviceManageModel
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}	
	
	//protected static $tableName="skyg_res.res_advert";
	//protected static $primeKey=array("ad_id");
		
	//列表统计
	public static function getDeviceCount(){
		$sql=sprintf(
				"SELECT 
				  count(*)
				FROM
				  `skyg_base`.`base_device`");
		//echo($sql);
		$result=parent::createSQL($sql)->toValue();
		return $result;		
	}
	
	//列表详情
	public static function getDeviceList($page,$pagesize,$orderCondition=array("create_date"=>"DESC")){
		$orderString=PublicModel::controlArray($orderCondition);
		$sql=sprintf(
				"SELECT 
				  `dev_id`,
				  `dev_mac`,
				  `chip`,
				  `model`,
				  `system_version`,
				  `platform`,
				  `barcode`,
				  `screen_size`,
				  `resolution` 
				FROM
				  `skyg_base`.`base_device`
				ORDER BY %s LIMIT %d,%d",$orderString,$page,$pagesize);
		//echo($sql);
		$result=parent::createSQL($sql)->toList();
		return $result;
	}
	
	
	//搜索统计
	public static function searchDeviceCount($searchCondition){
		$searchString=PublicModel::controlsearch($searchCondition);
		if($searchString!='')
			$searchString=' WHERE  '.$searchString;
		$sql=sprintf("
				SELECT 
				  count(*) 
				FROM
				  `skyg_base`.`base_device`
				%s",$searchString);
		$result=parent::createSQL($sql)->toValue();
		
		return $result;
	}
	
	//搜索
	public static function searchDeviceDetail($searchCondition,$start,$limit,$orderCondition=array("create_date"=>"DESC")){
		$orderString=PublicModel::controlArray($orderCondition);
		$searchString=PublicModel::controlsearch($searchCondition);
		if($searchString!='')
			$searchString=' WHERE  '.$searchString;
		$sql=sprintf("SELECT 
					  `dev_id`,
					  `dev_mac`,
					  `chip`,
					  `model`,
					  `system_version`,
					  `platform`,
					  `barcode`,
					  `screen_size`,
					  `resolution` 
					FROM
					  `skyg_base`.`base_device` 
				%s
				ORDER BY %s
				limit %d,%d ",$searchString,$orderString,$start,$limit);
		$result=parent::createSQL($sql)->toList();
		
		return $result;
	}
	
	//机型机芯列表
	public static function getDeviceModelAndChip(){
		$sql=sprintf(
				"SELECT
				  chip,
				  model
				FROM
				  skyg_base.`base_device`
				GROUP BY chip,
				  model");
		$result=parent::createSQL($sql)->toList();
		return $result;
	}
	
	
	
}