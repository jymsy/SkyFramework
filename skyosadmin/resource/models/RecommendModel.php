<?php
namespace resource\models;

use Sky\db\DBCommand;
use skyosadmin\components\PublicModel;

/** table skyg_res.res_top                    
 *
 * @author Zhengyun
 */
class RecommendModel extends \Sky\db\ActiveRecord{
	/**
	 *@return RecommendModel
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}	
	
	//获取分类列表
	public static function getAllTopCategory($cat_id=11){
		return parent::createSQL(
				"SELECT `category_id`,`category_name` FROM skyg_res.`res_category` WHERE parent=$cat_id order by sequence asc"
		)->toList();
	}

	//添加列表
	public static function addTop($arr){
		extract($arr);		
		$sql=sprintf("INSERT INTO skyg_res.`res_top` (
				  `recommend_type`,
				  `sequence`,
				  `source_id`,
				  `source_type`
				) 
				select 
				    %d,
				    IFNULL(max(sequence),0)+1,
				    %d,
				    %d
				from skyg_res.`res_top` where `recommend_type`=%d
				  ",$recommend_type,$source_id,$source_type,$recommend_type);
		
		$result=parent::createSQL($sql);
		
		if($result->exec()!=0){
			$result->getPdoInstance();
			$result=$result->lastInsertID();
			return $result;
		}			
		return 0;
	
	}
	
	
	//删除
	public static function deleteTop($top_id){
		return parent::createSQL(
				"DELETE
				FROM    
				 	  `skyg_res`.`res_top`				
				WHERE `top_id` = :top_id ",
				array(
						"top_id"=>(int)$top_id
				)
		)->exec();
		
	}
	
	
	//排行上升
	public static function setSequenceRise($source_type,$cat_id,$sequence,$top_id) {
		$sql = sprintf("SELECT 
				  `sequence`,
				  `top_id` 
				FROM
				  `skyg_res`.`res_top` 
				WHERE `recommend_type` = %d 
				  AND `source_type` = '%s' 
				  AND `sequence` =%d 
				LIMIT 1 ",$cat_id,$source_type,$sequence+1);
		$result = parent::createSQL($sql)->toList();
		if($result==null){
			return false;
		}
		
		$sqlFormat ="UPDATE `skyg_res`.`res_top` SET `sequence`=%d WHERE `top_id`=%d;
					UPDATE `skyg_res`.`res_top` SET `sequence`=%d WHERE `top_id`=%d";
		$sql = sprintf($sqlFormat,$sequence,$result[0]["top_id"],$result[0]["sequence"],$top_id);
		$result = parent::createSQL($sql)->exec();
		return $result;
	}
	
	//排行下降
	public static function setSequenceDecline($source_type,$cat_id,$sequence,$top_id) {
		$sql = sprintf("SELECT 
				  `sequence`,
				  `top_id` 
				FROM
				  `skyg_res`.`res_top` 
				WHERE `recommend_type` = %d 
				  AND `source_type` = '%s' 
				  AND `sequence` =%d 
				LIMIT 1 ",$cat_id,$source_type,$sequence-1);
		$result = parent::createSQL($sql)->toList();
		
		if(count($result)==0){
			return false;
		}
		
		$sqlFormat ="UPDATE `skyg_res`.`res_top` SET `sequence`=%d WHERE `top_id`=%d;
					UPDATE `skyg_res`.`res_top` SET `sequence`=%d WHERE `top_id`=%d;";
		$sql = sprintf($sqlFormat,$sequence,$result[0]["top_id"],$result[0]["sequence"],$top_id);
		$result = parent::createSQL($sql)->exec();
		return $result;
	}

	
	
	//获取最大sequence
	public static function getTopMaxSequence($source_type,$cat_id) {
		$sql = sprintf("SELECT 
				  MAX(`sequence`) 
				FROM
				  `skyg_res`.`res_top` 
				WHERE `recommend_type` = %d 
				  AND `source_type` = '%s'",$cat_id,$source_type); 
		return parent::createSQL($sql)->toValue();
		
	}
	
	
	
	//正常列表统计
	public static function getTopCount($cat_id){
		if($cat_id!="")
			$recommend_type=sprintf(" WHERE `recommend_type` = '%s'",$cat_id);
		$sql=sprintf(
				"SELECT 
				  count(*) 
				FROM
				  `skyg_res`.`res_top` AS rt 
				JOIN  `skyg_res`.`res_video` AS rv 
				ON rt.`source_id` = rv.v_id
				JOIN `skyg_res`.`res_category` AS rc
				ON rc.`category_id`=rt.`recommend_type` 
				 %s",
				$recommend_type);
		$result=parent::createSQL($sql)->toValue();
		return $result;
		
	}
	// 正常列表
	public static function getTopList($cat_id,$start,$limit,$orderCondition=array('sequence'=>'ASC')){
		$orderString=PublicModel::controlArray($orderCondition);
		$recommend_type="";	
		if($cat_id!="")
			$recommend_type=sprintf("AND rt.`recommend_type` = '%s'",$cat_id);		
		$sql=sprintf(
				"SELECT 
				  rt.`top_id`,
				  rt.`sequence`,
				  rt.`recommend_type`,
				  rc.`category_name`,
				  rt.`source_type`,
				  CASE
				    rt.`source_type` 
				    WHEN 1 
				    THEN '影视' 
				  END AS source_type_name,
				  rv.`title` 
				FROM
				  `skyg_res`.`res_top` AS rt 
				JOIN  `skyg_res`.`res_video` AS rv 
				ON rt.`source_id` = rv.v_id
				JOIN `skyg_res`.`res_category` AS rc
				ON rc.`category_id`=rt.`recommend_type` 
				%s
				 ORDER BY %s 
				LIMIT $start, $limit",$recommend_type,$orderString,$start,$limit);																								
		$result=parent::createSQL($sql)->toList();
		return $result;		
		
	}      
	
	//搜索列表统计 ()
	public static function searchTopCount($cat_id,$searchCondition) {		
		$recommend_type="";
		if($cat_id!="")
			$recommend_type=sprintf("WHERE `recommend_type` = '%s'",$cat_id);
		$searchString=PublicModel::controlsearch($searchCondition);
		if($searchString!='')
		{
			if($recommend_type!="")
				$searchString=' AND  '.$searchString;
			else 
				$searchString=' WHERE  '.$searchString;
		}
		$searchString=str_replace("category_name", "rc`.`category_name", $searchString);
		$sql=sprintf(
				"SELECT 
				  count(*) 
				FROM
				  `skyg_res`.`res_top` AS rt 
				JOIN  `skyg_res`.`res_video` AS rv 
				ON rt.`source_id` = rv.v_id
				JOIN `skyg_res`.`res_category` AS rc
				ON rc.`category_id`=rt.`recommend_type`
				 %s
				 %s",
				$recommend_type,$searchString);
		$result=parent::createSQL($sql)->toValue();
		return $result;
		
	}
	//搜索列表公式 ()
	public static function searchTopList($cat_id,$searchCondition,$start,$limit,$orderCondition=array('sequence'=>'ASC')) {
		$searchString=PublicModel::controlsearch($searchCondition);
		if($searchString!='')
			$searchString=' AND  '.$searchString;
		$orderString=PublicModel::controlArray($orderCondition);
		$recommend_type="";
		if($cat_id!="")
			$recommend_type=sprintf("AND rt.`recommend_type` = '%s'",$cat_id);
		$searchString=str_replace("category_name", "rc`.`category_name", $searchString);
		$sql=sprintf(
				"SELECT 
				  rt.`top_id`,
				  rt.`sequence`,
				  rt.`recommend_type`,
				  rc.`category_name`,
				  rt.`source_type`,
				  CASE
				    rt.`source_type` 
				    WHEN 1 
				    THEN '影视' 
				  END AS source_type_name,
				  CASE
				    rt.`source_type` 
				    WHEN 1 
				    THEN '影视' 
				  END AS source_type,
				  rv.`title` 
				FROM
				  `skyg_res`.`res_top` AS rt 
				JOIN  `skyg_res`.`res_video` AS rv 
				ON rt.`source_id` = rv.v_id
				JOIN `skyg_res`.`res_category` AS rc
				ON rc.`category_id`=rt.`recommend_type` 
				 %s 
				 %s
				ORDER BY %s 
				LIMIT $start, $limit",$recommend_type,$searchString,$orderString,$start,$limit);	
		$result=parent::createSQL($sql)->toList();
		return $result;		
	} 
	
	//未推荐的影视列表
	public static function getUnrecommendVideoList($start,$limit,$orderCondition=array('v_id'=>'DESC')){
		$orderString=PublicModel::controlArray($orderCondition);
		$sql=sprintf(
				"SELECT
				  rv.`v_id`,
				  rv.`title`,
				  rv.`actor`,
				  rv.`category`,
				  rv.`category_name`,
				  rv.`classfication`,
				  rv.`total_segment`,
				  BIT_AND(rvs.expired) AS expired,
				  GROUP_CONCAT(rvs.`source`) AS `source`
				FROM
				  `skyg_res`.`res_video` AS rv
				JOIN `skyg_res`.`res_video_site` AS rvs
				    ON rv.`v_id` = rvs.`v_id`
				WHERE rv.`v_id` NOT IN (SELECT source_id FROM skyg_res.res_top)
				GROUP BY rv.`v_id`
				ORDER BY %s
				LIMIT %d,%d",$orderString,$start,$limit);
		$result=parent::createSQL($sql)->toList();
		return $result;
	
	}
	
	//未推荐的影视列表统计
	public static function getUnrecommendVideoCount(){
		$sql="SELECT 
				  count(DISTINCT rv.`v_id`) 
				FROM
				  `skyg_res`.`res_video` AS rv 
				JOIN `skyg_res`.`res_video_site` AS rvs 
				    ON rv.`v_id` = rvs.`v_id` 
				WHERE rv.`v_id` NOT IN (SELECT source_id FROM skyg_res.res_top)";
		$result=parent::createSQL($sql)->toValue();
		return $result;		
		
	} 

	//搜索未推荐的影视列表
	public static function searchUnrecommendVideoList($searchCondition,$start,$limit,$orderCondition=array('v_id'=>'DESC')){
		$orderString=PublicModel::controlArray($orderCondition);
		$searchString=PublicModel::controlsearch($searchCondition);
		if($searchString!='')
			$searchString=' and  '.$searchString;
		$searchString=str_replace("v_id", "rv`.`v_id", $searchString);
		$searchString=str_replace("title", "rv`.`title", $searchString);
		$sql=sprintf(
				"SELECT
				  rv.`v_id`,
				  rv.`title`,
				  rv.`actor`,
				  rv.`category`,
				  rv.`category_name`,
				  rv.`classfication`,
				  rv.`total_segment`,
				  BIT_AND(rvs.expired) AS expired,
				  GROUP_CONCAT(rvs.`source`) AS `source`
				FROM
				  `skyg_res`.`res_video` AS rv
				JOIN `skyg_res`.`res_video_site` AS rvs
				    ON rv.`v_id` = rvs.`v_id`
				WHERE rv.`v_id` NOT IN (SELECT source_id FROM skyg_res.res_top)
				 %s
				GROUP BY rv.`v_id`
				ORDER BY %s
				LIMIT %d,%d",$searchString,$orderString,$start,$limit);
		$result=parent::createSQL($sql)->toList();
		return $result;
	
	}
	
	//搜索未推荐的影视列表统计
	public static function searchUnrecommendVideoCount($searchCondition){
		$searchString=PublicModel::controlsearch($searchCondition);
		if($searchString!='')
			$searchString=' and  '.$searchString;
		$searchString=str_replace("v_id", "rv`.`v_id", $searchString);
		$searchString=str_replace("title", "rv`.`title", $searchString);
		$sql=sprintf("SELECT
				  count(DISTINCT rv.`v_id`)
				FROM
				  `skyg_res`.`res_video` AS rv
				JOIN `skyg_res`.`res_video_site` AS rvs
				    ON rv.`v_id` = rvs.`v_id`
				WHERE rv.`v_id` NOT IN (SELECT source_id FROM skyg_res.res_top)
				 %s",$searchString);
		$result=parent::createSQL($sql)->toValue();
		return $result;
	
	}
	
	
	
	
		
}