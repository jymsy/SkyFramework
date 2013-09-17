<?php
namespace res\models;
/**
 * @property  int          correlation_id    自增ID              
 * @property  int          target_id         目标资源ID        
 * @property  string       target_type_id    目标资源类型ID  
 * @property  int          relation_id       相关资源ID        
 * @property  string       relation_title    相关资源Title     
 * @property  string       relation_type_id  相关资源类型ID  
 * @property  string       created_time      创建时间                                       
 * 
 * @author xiaokeming
 */

class RelationModel extends \Sky\db\ActiveRecord{
	/**
	 *@return RelationModel
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}

	protected static $tableName="skyg_res.res_cross_relation";
	protected static $primeKey=array("correlation_id");
	
	/**
	 * 
	 * @param int $targetId         目标资源ID
	 * @param string $syscondition  策略控制条件
	 * @return multitype:
	 */
	
	public static function showrelationbytarget($targetId,$syscondition){
		if ($syscondition!=''){
			$v_sql=' and '.$syscondition;
		}else{
			$v_sql='';
		}
		return parent::createSQL(
				"SELECT
					  rcr.`correlation_id` AS id,
					  rcr.`target_id`,
					  rcr.`target_type_id`,
					  rcr.`relation_id`,
					  rcr.`relation_title`,
					  rcr.`relation_type_id`,
					  rcr.`created_time`
					FROM
					  `skyg_res`.`res_cross_relation` AS rcr
					WHERE rcr.target_id = :targetId ".$v_sql,
				array(
						"targetId"=>(int)$targetId
				)
		)->toList();
	}
	
	
	/**
	 * 
	 * @param int    $v_target_typeid  目标资源类型ID
	 * @param string $epgname          相关资源Title
	 * @param string $syscondition     策略控制条件
	 */
	public static function showrelationbytitle($v_target_typeid,$epgname,$syscondition){
		$v_target_typeid = addslashes($v_target_typeid);
		if ($syscondition!=''){
			$v_sql=' and '.$syscondition;
		}else{
			$v_sql='';
		}
		
		$epgname="'".$epgname."%'";
		return parent::createSQL(
				"SELECT
					  rcr.`correlation_id` AS id,
					  rcr.`target_id`,
					  rcr.`target_type_id`,
					  rcr.`relation_id`,
					  rcr.`relation_title`,
					  rcr.`relation_type_id`,
					  rcr.`created_time`
					FROM
					  `skyg_res`.`res_cross_relation` AS rcr
					WHERE rcr.`target_type_id` = :v_target_typeid
					  AND rcr.`relation_title` LIKE ".$epgname.$v_sql,
				array(
						"v_target_typeid"=>(int)$v_target_typeid
				)
		)->toList();
	}
	
	
	/**
	 * 
	 * @param int $v_target_typeid  目标资源类型ID
	 * @param int $targetid         目标资源ID   
	 * @param string $syscondition  策略控制条件
	 * 
	 */
	public static function showrelationbytwoid($v_target_typeid,$targetid,$syscondition){
		if ($syscondition!=''){
			$v_sql=' and '.$syscondition;
		}else{
			$v_sql='';
		}
		$v_target_typeid = addslashes($v_target_typeid);
		return parent::createSQL(
				"SELECT
					  rcr.`correlation_id` AS id,
					  rcr.`target_id`,
					  rcr.`target_type_id`,
					  rcr.`relation_id`,
					  rcr.`relation_title`,
					  rcr.`relation_type_id`,
					  rcr.`created_time`
					FROM
					  `skyg_res`.`res_cross_relation` AS rcr
					WHERE rcr.`target_type_id` = :v_target_typeid
					  AND rcr.`target_id` = :targetid ".$v_sql,
				array(
						"v_target_typeid"=>(int)$v_target_typeid,
						"targetid"=>(int)$targetid
				)
		)->toList();
	}
	
	/**
	 * 
	 * @param int $sid              objectid
	 * @param int $v_type           objecttype
	 * @param string $syscondition  策略控制条件
	 */
	public static function queryarelationmark($sid,$v_type,$syscondition){
		if ($syscondition!=''){
			$v_sql=' and '.$syscondition;
		}else{
			$v_sql='';
		}
		return parent::createSQL(
				"SELECT
					  rcr.`relationmark_id` AS id,
					  rcr.`objecttype`,
					  rcr.`objectid`,
					  rcr.`name`,
					  rcr.`showname`,
					  rcr.`isattribute`
					FROM
					  `skyg_res`.`res_relation_mark` AS rcr
					WHERE rcr.`objectid` = :sid
					  AND rcr.`objecttype` = :v_type ".$v_sql,
				array(
						"sid"=>(int)$sid,
						"v_type"=>(int)$v_type
				)
		)->toList();
	}
}
