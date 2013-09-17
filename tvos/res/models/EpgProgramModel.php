<?php
namespace res\models;
/**
 * @property  int          program_id         自增id                  
 * @property  int          channel_id         频道id，对应channel  
 * @property  string       program_name       节目名称              
 * @property  string       created_date                                 
 * @property  string       epg_type           EPG分类                 
 * @property  string       image              缩图                    
 * @property  string       time               时长                    
 * @property  string       director           导演                    
 * @property  string       actor              演员                    
 * @property  string       area               地区                    
 * @property  string       language           语言                    
 * @property  string       year               年份                    
 * @property  string       res_classfication  资源分类              
 * @property  string       begintime          节目开始时间        
 * @property  string       endtime            节目结束时间        
 * @property  string       description        简介                    
 * 
 * @author xiaokeming
 */

class EpgProgramModel extends \Sky\db\ActiveRecord{
	/**
	 *@return EpgProgramModel
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}

	protected static $tableName="skyg_res.res_program";
	protected static $primeKey=array("program_id");
	
	/**
	 * 
	 * @param arry $v_program_id    节目ID
	 * @param string $syscondition  策略控制条件
	 */
	public static function showepgprogram($v_program_id,$syscondition){
		if ($syscondition!=''){
			$v_sql=' and '.$syscondition;
		}else{
			$v_sql='';
		}
		return parent::createSQL(
				"SELECT
					  rpg.`program_id` AS pg_id,
					  rpg.`channel_id` AS ch_id,
					  rpg.`program_name` AS pg_name,
					  rpg.`created_date`,
					  rpg.`epg_type` AS type,
					  rpg.`image` AS img,
					  rpg.`time` AS timelong,
					  rpg.`director`,
					  rpg.`actor`,
					  rpg.`area`,
					  rpg.`language` AS lang,
					  rpg.`year`,
					  rpg.`res_classfication` AS subtype,
					  rpg.`begintime`,
					  rpg.`endtime`,
					  rpg.`description`
					FROM
					  `skyg_res`.`res_program` AS rpg
					WHERE rpg.`program_id` IN (".$v_program_id.")".$v_sql
				
		)->toList();
		
	
	}
	
	/**
	 * 
	 * @param int    $v_channel_id    频道ID
	 * @param string $v_begintime     开始时间
	 * @param string $v_endtime       结束时间
	 * @param string $syscondition    策略控制条件
	 */
	public static function listepgprogramcount($v_channel_id,$syscondition,$v_begintime,$v_endtime){
		if ($syscondition!=''){
			$v_sql=' and '.$syscondition;
		}else{
			$v_sql='';
		}
		return parent::createSQL(
				"SELECT
				       COUNT(*)
				  FROM
				       `skyg_res`.`res_program` AS rpg
				 WHERE rpg.`channel_id` = :v_channel_id
				   AND rpg.`begintime` >= :v_begintime
				   AND rpg.`endtime` <= :v_endtime".$v_sql,
				array(
						"v_channel_id"=>(int)$v_channel_id,
						"v_begintime"=>$v_begintime,
						"v_endtime"=>$v_endtime
				)
		)->toValue();
		
		 
	}
	
	/**
	 * 
	 * @param int    $v_channel_id  频道ID
	 * @param string $v_begintime   开始时间
	 * @param string $v_endtime     结束时间
	 * @param string $syscondition  策略控制条件
	 * @param int    $v_start
	 * @param int    $v_pagesize   
	 */
	public static function listepgprogram($v_channel_id,$syscondition,$v_begintime,$v_endtime,$v_start,$v_pagesize){
		if ($syscondition!=''){
			$v_sql=' and '.$syscondition;
		}else{
			$v_sql='';
		}
		return parent::createSQL(
				"SELECT
					  rpg.`program_id` AS pg_id,
					  rpg.`channel_id` AS ch_id,
					  rpg.`program_name` AS pg_name,
					  rpg.`created_date`,
					  rpg.`epg_type` AS type,
					  rpg.`image` AS img,
					  rpg.`time` AS timelong,
					  rpg.`director`,
					  rpg.`actor`,
					  rpg.`area`,
					  rpg.`language` AS lang,
					  rpg.`year`,
					  rpg.`res_classfication` AS subtype,
					  rpg.`begintime`,
					  rpg.`endtime`,
					  rpg.`description`
					FROM
					  `skyg_res`.`res_program` AS rpg
					WHERE rpg.`channel_id` = :v_channel_id
					  AND rpg.`begintime` >= :v_begintime
					  AND rpg.`endtime` <= :v_endtime".$v_sql."
					ORDER BY rpg.`begintime`
					LIMIT :v_start, :v_pagesize ",
				array(
						"v_channel_id"=>(int)$v_channel_id,
						"v_begintime"=>$v_begintime,
						"v_endtime"=>$v_endtime,
						"v_start"=>(int)$v_start,
						"v_pagesize"=>(int)$v_pagesize
				)
		)->toList();
		
		 
	}
}