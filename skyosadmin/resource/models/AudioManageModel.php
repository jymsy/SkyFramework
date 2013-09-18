<?php
namespace resource\models;

use Sky\db\DBCommand;
use skyosadmin\components\PublicModel;

/** table skyg_res. 
 * 
 * @author Zhengyun
 */
class AudioManageModel extends \Sky\db\ActiveRecord{
	/**
	 *@return AudioManageModel
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}		
	
	//删除
	public static function deleteMusicTop($music_top_id){
		return parent::createSQL(
				"DELETE
				FROM    
				 	  `skyg_res`.`res_music_top`				
				WHERE `music_top_id` = :music_top_id ",
				array(
						"music_top_id"=>(int)$music_top_id
				)
		)->exec();
		
	}
	
	//Song编辑($array)
	public static function updateMusicTop($arr){
		extract($arr);
		return parent::createSQL(
			"UPDATE skyg_res.`res_music_top` 
			 SET
				`category_id`=:category_id,
				`title`=:title,							
				`resource`=:resource,	
				`page_index`=:page_index,	
				`level`=:level  
			WHERE `music_top_id` = :music_top_id ",
			array(
				'category_id'=>$category_id,
				'title'=>$title,							
				'resource'=>$resource,	
				'page_index'=>$page_index,	
				'level'=>$level,
			    "music_top_id"=>(int)$music_top_id					
			)
		)->exec();				
	}

	
	//Song上下架
	public static function setMusicTopStatus($music_top_id,$expired){	
		$sql = "update `skyg_res`.`res_music_top` set `expired`=".$expired." where `music_top_id` =".$music_top_id;
		$result=parent::createSQL($sql)->exec();
		return $result;	
	}		
	
	
	
	//Song正常列表统计
	public static function getMusicTopCount(){
		$result=parent::createSQL(
				"SELECT 
				  COUNT(*) 
				FROM
				  `skyg_res`.`res_music_top`"
				)->toValue();
		return $result;
		
	}
	//Song 正常列表
	public static function getMusicTopList($start,$limit,$orderCondition=array('music_top_id'=>'DESC')){
		$orderString=PublicModel::controlArray($orderCondition);		
		$sql=sprintf(
				"SELECT 
				 	rmt.`music_top_id`,
					rmt.`title`,
					rmt.`singer`,
					rmt.`url`,
					rmt.`page_index`,
					rmt.`lrc`,
					rmt.`source`,
					rmt.`expired`,
					rmt.`category_id`,
					rmt.`created_date`,
					rmt.`first_chars`,
					rmt.`resmark`,
					rca.`category_name`,
					rmt.`level`  
				FROM
				  `skyg_res`.`res_music_top`  AS rmt
				LEFT JOIN `skyg_res`.`res_category` AS rca
				ON rmt.`category_id`=rca.`category_id`
				ORDER BY %s
				LIMIT %d,%d",$orderString,$start,$limit);
		$result=parent::createSQL($sql)->toList();
		return $result;		
		
	}      
	
	//Song搜索列表统计 ()
	public static function searchMusicTopCount($searchCondition) {
		$searchString=PublicModel::controlsearch($searchCondition);
		if($searchString!='')
			$searchString=' WHERE  '.$searchString;
		$searchString=str_replace("category_id", "rmt`.`category_id", $searchString);
		$sql=sprintf(
				"SELECT 
				 	count(*)  
				FROM
				  `skyg_res`.`res_music_top`  AS rmt
				LEFT JOIN `skyg_res`.`res_category` AS rca
				ON rmt.`category_id`=rca.`category_id` 
				%s 	",
				$searchString);
		$result=parent::createSQL($sql)->toValue();
		return $result;
		
	}
	//Song搜索列表公式 ()
	public static function searchMusicTopList($searchCondition,$start,$limit,$orderCondition=array('music_top_id'=>'DESC')) {
		$searchString=PublicModel::controlsearch($searchCondition);
		if($searchString!='')
			$searchString=' WHERE  '.$searchString;
		$orderString=PublicModel::controlArray($orderCondition);

		$searchString=str_replace("category_id", "rmt`.`category_id", $searchString);
		$orderString=str_replace("category_id", "rmt`.`category_id", $orderString);
		
		$sql=sprintf(
				"SELECT 
				 	rmt.`music_top_id`,
					rmt.`title`,
					rmt.`singer`,
					rmt.`url`,
					rmt.`page_index`,
					rmt.`lrc`,
					rmt.`source`,
					rmt.`expired`,
					rmt.`category_id`,
					rmt.`created_date`,
					rmt.`first_chars`,
					rmt.`resmark`,
					rca.`category_name`,
					rmt.`level`  
				FROM
				  `skyg_res`.`res_music_top`  AS rmt
				LEFT JOIN `skyg_res`.`res_category` AS rca
				ON rmt.`category_id`=rca.`category_id`					
				%s
				ORDER BY %s 
				LIMIT %d,%d",
				$searchString,$orderString,$start,$limit
		);
		$result=parent::createSQL($sql)->toList();
		return $result;		
	} 	
	
}