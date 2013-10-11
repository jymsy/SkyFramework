<?php
namespace res\models;

use Sky\db\DBCommand;
use skyosadmin\components\PublicModel;

/** table skyg_res.res_program_recorded                    
 *
 * @author Zhengyun
 */
class ProgramRecordModel extends \Sky\db\ActiveRecord{
	/**
	 *@return ProgramRecordModel
	 */
public static function model($className=__CLASS__){
		return parent::model($className);
	}	
	
//添加录制节目
	/**
	 * 
	 * @param int $user_id
	 * @param int $program_id
	 * @param string $record_content
	 * @return >0 添加成功，=0添加失败，-1已添加过
	 */
	public static function insertProgramRecord($user_id,$program_id,$record_content){
		$result=parent::createSQL(
				"SELECT 
				  COUNT(*) 
				FROM
				  `skyg_res`.`res_program_recorded` 
				WHERE `user_id` = :user_id 
				  AND `program_id` = :program_id ",
				array(
						'user_id'=>(int)$user_id,
						'program_id'=>(int)$program_id)
				)->toValue();
		if($result>0)
			return -1;
		
		$result=parent::createSQL(
				"INSERT INTO skyg_res.`res_program_recorded` (
				  `user_id`,
				  `program_id`,
				  `record_content`
				) 
				VALUES
				  (
				    :user_id,
				    :program_id,
				    :record_content
				  )",
					array(
					"user_id"=>(int)$user_id,
					"program_id"=>(int)$program_id,
					"record_content"=>$record_content)
		);
	
		if($result->exec()!=0){
			$result->getPdoInstance();
			$result=$result->lastInsertID();
			return $result;
		}
		return 0;	
	}
	//获取所有录制节目数量
	/**
	 * 
	 * @param int $user_id
	 * @return int count
	 */
	public static function getProgramRecordCount($user_id){
		$sql=sprintf("SELECT 
					  count(*)
					FROM
					  `skyg_res`.`res_program_recorded` AS rpr
					 JOIN `skyg_res`.`res_program` AS rp
					 ON rpr.`program_id`=rp.`program_id`
					 JOIN `skyg_res`.`res_channel` AS rc
					 ON rc.`channel_id`=rp.`channel_id`
					WHERE rpr.user_id = %d",$user_id);	
		$result=parent::createSQL($sql)->toValue();
		return $result;
	}
	
	//获取所有录制的节目单
	/**
	 * 
	 * @param int $user_id
	 * @param int $start
	 * @param int $limit
	 * @param array $orderCondition
	 * @return array
	 */
	public static function getProgramRecordList($user_id,$start,$limit,$orderCondition=array('create_date'=>'desc')){
		$orderString=PublicModel::controlArray($orderCondition);
		$orderString=str_replace("program_id", "rpr`.`program_id", $orderString);
		$orderString=str_replace("create_date", "rpr`.`create_date", $orderString);		
		$sql=sprintf("SELECT 
					  rpr.`user_id`,  
					  rpr.`record_content`,
					  rpr.`program_id`,
					  rpr.`create_date`,
					  rp.`begintime`,
					  rp.`endtime`,
					  rp.`program_name`,
					  rp.`playback_url` ,
					  rc.`channel_name`
					FROM
					  `skyg_res`.`res_program_recorded` AS rpr
					 JOIN `skyg_res`.`res_program` AS rp
					 ON rpr.`program_id`=rp.`program_id`
					 JOIN `skyg_res`.`res_channel` AS rc
					 ON rc.`channel_id`=rp.`channel_id`
					WHERE rpr.user_id = %d
					ORDER BY %s 
					LIMIT %s,%s",$user_id,$orderString,$start,$limit);
		$result=parent::createSQL($sql)->toList();
		return $result;
	}
	
	
	//删除录制的节目单
	/**
	 * 
	 * @param int $user_id
	 * @param int $program_id
	 * @return >0删除成功，=0删除失败
	 */
	public static function delProgramRecord($user_id,$program_id){
		$result=parent::createSQL("DELETE 
				FROM
				  skyg_res.`res_program_recorded` 
				WHERE `user_id` = :user_id 
				  AND `program_id` = :program_id ",
				array(
						"user_id"=>(int)$user_id,
						"program_id"=>(int)$program_id)
		)->exec();
		return $result;
	}
		
}