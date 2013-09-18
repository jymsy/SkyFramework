<?php
namespace advert\boot\models;

use Sky\db\DBCommand;
use skyosadmin\components\PublicModel;

/** table  
 * 
 * @author Zhengyun
 */
class BootUIManageModel extends \Sky\db\ActiveRecord{
	/**
	 *@return BootUIManageModel
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}		
	
	
	
	//删除
	public static function deleteBootUI($boot_ui_id){
		return parent::createSQL(
				"UPDATE   
				 	  `skyg_base`.`base_boot_ui`
				SET 	`is_deleted`=1 			
				WHERE `boot_ui_id` = :boot_ui_id ",
				array(
						"boot_ui_id"=>(int)$boot_ui_id
				)
		)->exec();		
	}
	
	//删除某一类型的全面开机画面
	public static function deleteBootUIByType($type){
		return parent::createSQL(
				"UPDATE
				 	  `skyg_base`.`base_boot_ui`
				SET 	`is_deleted`
				WHERE `type` = :type ",
				array(
						"type"=>(int)$type
				)
		)->exec();
	}
		
	//编辑($array)
	public static function updateBootUI($arr){
		extract($arr);
		return parent::createSQL(
			"UPDATE 
			  `skyg_base`.`base_boot_ui` 
			SET
			  `name`=:name,
			  `type`=:type,
			  `begin_time`=:begin_time,
			  `end_time`=:end_time,
			  `url`=:url,
			  `md5`=:md5,
			  `is_deleted`=:is_deleted,
			  `is_publish`=:is_publish  
			WHERE `boot_ui_id`=:boot_ui_id ",
				array(
					  'name'=>$name,
					  'type'=>$type,
					  'begin_time'=>$begin_time,
					  'end_time'=>$end_time,
					  'url'=>$url,
					  'md5'=>$md5,
					  'is_deleted'=>$is_deleted,
					  'is_publish'=>$is_publish,
					  'boot_ui_id'=>$boot_ui_id					
				)
		)->exec();	
				
	}
	
	
	//添加($array)
	 /**
	  * 
	  * @param array $arr
	  * @return 添加成功，返回新增id（id>0），0-添加失败
	  */
	public static function insertBootUI($arr){
		extract($arr);
		$result=parent::createSQL(
				"INSERT INTO skyg_base.`base_boot_ui` (
				  `name`,
				  `type`,
				  `begin_time`,
				  `end_time`,
				  `url`,
				  `md5`,
				  `is_deleted`,
				  `is_publish`
				) 
				VALUES
				  (
				    :name,
				    :type,
				    :begin_time,
				    :end_time,
				    :url,
				    :md5,
				    :is_deleted,
				    :is_publish
				  )",
				array(
					  'name'=>$name,
					  'type'=>$type,
					  'begin_time'=>$begin_time,
					  'end_time'=>$end_time,
					  'url'=>$url,
					  'md5'=>$md5,
					  'is_deleted'=>$is_deleted,
					  'is_publish'=>$is_publish			
				)
			);
		if($result->exec()!=0){
			$result->getPdoInstance();
			$result=$result->lastInsertID();
			return $result;
		}
		return 0;
		
	}	

	//获取所有类型的开机画面信息
	public static function getAllType(){
		return parent::createSQL(
				"SELECT 
				  DISTINCT `type` 
				FROM
				  `skyg_base`.`base_boot_ui` "				
		)->toList();
	}
	
	//获取某类型的开机画面信息
	public static function getBootUIByType($type){		
		return parent::createSQL(
			"SELECT 
			  `boot_ui_id`,
			  `name`,
			  `type`,
			  `begin_time`,
			  `end_time`,
			  `url`,
			  `md5`,
			  `is_deleted`,
			  `is_publish` 
			FROM
			  `skyg_base`.`base_boot_ui` 
			WHERE
			  `type`=:type",
			array(					
					'type'=>(int)$type	
			)
		)->toList();		
	}
	
	
	/**
	 * 获取有效的开机画面, 包括将要推送的和现在正在推送的。
	 */
	public static function getActiveBootUI(){
		return parent::createSQL(
				"SELECT 
				  `boot_ui_id`,
				  `name`,
				  `type`,
				  `begin_time`,
				  `end_time`,
				  `url`,
				  `md5`,
				  `created_date`,
				  `is_deleted`,
				  `is_publish` 
				FROM
				  `skyg_base`.`base_boot_ui` 
				WHERE NOW() > `begin_time`
				  AND NOW()<  `end_time`  
				  AND `is_deleted` = 0 
				  AND `is_publish` = 1 
				ORDER BY `boot_ui_id` "
				)->toList();
	}
	
	//获取开机画面列表总数
	public static function getBootUIListCount() {
		$sql=sprintf(
				"SELECT
				  count(*) 
				FROM
				  `skyg_base`.`base_boot_ui`
				WHERE `is_deleted` = 0");
		$result=parent::createSQL($sql)->toValue();
		return $result;
	}
	//获取开机画面列表
	public static function getBootUIList($start,$limit,$orderCondition=array('boot_ui_id'=>'DESC')) {
		$orderString=PublicModel::controlArray($orderCondition);
		$sql=sprintf(
				"SELECT 
				  `boot_ui_id`,
				  `name`,
				  `type`,
				  `begin_time`,
				  `end_time`,
				  `url`,
				  `md5`,
				  `created_date`,
				  `is_deleted`,
				  `is_publish` 
				FROM
				  `skyg_base`.`base_boot_ui` 
				WHERE `is_deleted` = 0 
				ORDER BY %s
				LIMIT %d,%d",$orderString,$start,$limit);
		$result=parent::createSQL($sql)->toList();
		return $result;
	}
	
	//搜索开机画面列表总数
	public static function searchBootUIListCount($searchCondition) {
		$searchString=PublicModel::controlsearch($searchCondition);
		if($searchString!='')
			$searchString=' AND  '.$searchString;
		$sql=sprintf(
				"SELECT
				  count(*)
				FROM
				  `skyg_base`.`base_boot_ui`
				WHERE `is_deleted` = 0
				%s",$searchString);
		$result=parent::createSQL($sql)->toValue();
		return $result;
	}
	
	//搜索开机画面列表
	public static function searchBootUIList($searchCondition,$start,$limit,$orderCondition=array('boot_ui_id'=>'DESC')) {
		$searchString=PublicModel::controlsearch($searchCondition);
		if($searchString!='')
			$searchString=' AND  '.$searchString;
		$orderString=PublicModel::controlArray($orderCondition);
		$sql=sprintf(
				"SELECT
				  `boot_ui_id`,
				  `name`,
				  `type`,
				  `begin_time`,
				  `end_time`,
				  `url`,
				  `md5`,
				  `created_date`,
				  `is_deleted`,
				  `is_publish`
				FROM
				  `skyg_base`.`base_boot_ui`
				WHERE `is_deleted` = 0
				%s
				ORDER BY %s
				LIMIT %d,%d",$searchString,$orderString,$start,$limit);
		$result=parent::createSQL($sql)->toList();
		return $result;
	}
	
	
	
	
	//改变开机画面的发布状态
	///同一时间段如果有有效开机画面则返回-1
	public static function updateBootUIStatus($boot_ui_id,$is_publish) {		
		if($is_publish==1)
		{
			$result=parent::createSQL(		
				"SELECT 
				  count(*)
				FROM
				  `skyg_base`.`base_boot_ui` a,
				  `skyg_base`.`base_boot_ui` b 
				WHERE a.`boot_ui_id` = :id 
				  AND b.`is_deleted` = 0 
				  AND b.`is_publish` = 1 
				  AND (
				    (
				      b.`begin_time` <= a.`begin_time` 
				      AND b.`end_time` >= a.`end_time`
				    ) 
				    OR (
				      b.`begin_time` > a.`begin_time` 
				      AND b.`end_time` < a.`end_time`
				    ) 
				    OR (
				      b.`begin_time` BETWEEN a.`begin_time` 
				      AND a.`end_time`
				    ) 
				    OR (
				      b.`end_time` BETWEEN a.`begin_time` 
				      AND a.`end_time`
				    )
				  )",
					array(
					'id'=>$boot_ui_id
					)
			)->toValue();
						
			if($result>0)
				return -1;			
		}
		
		$result=parent::createSQL(
				"UPDATE 
				  skyg_base.`base_boot_ui` 
				SET
				  is_publish =:is_publish   
				WHERE `boot_ui_id` =:boot_ui_id ",
				array(
					'is_publish'=>(int)$is_publish,
					'boot_ui_id'=>(int)$boot_ui_id)
				)->exec();
		return $result;		
	}	
	
	
}