<?php
namespace epg\models;

use Sky\db\DBCommand;
use skyosadmin\components\PublicModel;

/** table skyg_res.res_epg 
 * 
 * @author Zhengyun
 */
class EPGManageModel extends \Sky\db\ActiveRecord{
	/**
	 *@return EPGManageModel
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}		
	
	
	////////////////epg_category///////////////////////
	//删除
	public static function deleteEPGCategory($epg_cat_id){
		return parent::createSQL(
				"DELETE
				FROM    
				 	  `skyg_res`.`res_epg_category`				
				WHERE `epg_cat_id` = :epg_cat_id ",
				array(
						"epg_cat_id"=>(int)$epg_cat_id
				)
		)->exec();
		
		
	}
	
	//编辑($array)
	public static function updateEPGCategory($arr){
		extract($arr);
		return parent::createSQL(
			"UPDATE 
			  skyg_res.`res_epg_category` 
			SET
			  `epg_cat_name` = :epg_cat_name,
			  `index` = :index 
			WHERE `epg_cat_id` = :epg_cat_id ",
				array(
					  'epg_cat_name'=>$epg_cat_name,
					  'index'=>$index,
					  'epg_cat_id'=>$epg_cat_id						
				)
		)->exec();	
				
	}
	
	
	//添加($array)
	 /**
	  * 
	  * @param array $arr
	  * @return 添加成功，返回新增id（id>0），0-添加失败
	  */
	public static function insertEPGCategory($arr){
		extract($arr);
		$result=parent::createSQL(
				"INSERT INTO skyg_res.`res_epg_category` (`epg_cat_name`, `index`) 
				VALUES
				  (:epg_cat_name,:index)",
				array(
					  'epg_cat_name'=>$epg_cat_name,
					  'index'=>$index			
				)
			);
		if($result->exec()!=0){
			$result->getPdoInstance();
			$result=$result->lastInsertID();
			return $result;
		}
		return 0;
		
	}		
	
	//获取一个分类信息
	public static function getEPGCategoryByID($epg_cat_id){		
		return parent::createSQL(
			"SELECT 
			  `epg_cat_name`,
			  `index`,
			  `created_date` 
			FROM
			  `skyg_res`.`res_epg_category` 
			WHERE `epg_cat_id` =:epg_cat_id",
			array(					
					'epg_cat_id'=>$epg_cat_id	
			)
		)->toList();		
	}
	
	
	
	//获取所有分类
	/**
	 * @param Int $start
	 * @param Int $limit
	 * @param Int $orderCondition
	 * @return number
	 */
	public static function getAllEPGCategoryList($start,$limit,$orderCondition=array('epg_cat_id'=>'DESC')){
		$orderString=PublicModel::controlArray($orderCondition);
		$sql=sprintf(
				"SELECT 
				  `epg_cat_id`,
				  `epg_cat_name`,
				  `index`,
				  `created_date` 
				FROM
				  `skyg_res`.`res_epg_category` 
				ORDER BY %s
				LIMIT %d,%d",$orderString,$start,$limit);
		$result=parent::createSQL($sql)->toList();
		return $result;		
	}	
	//获取所有分类
	/*
	 * @param Int $start
	 * @param Int $limit
	 * @param Int $orderCondition
	 * @return number
	 */
	public static function getAllEPGCategoryCount(){
		$sql=sprintf(
				"SELECT
				  count(*)
				FROM
				  `skyg_res`.`res_epg_category`");
		$result=parent::createSQL($sql)->toValue();
		return $result;
	}
	
	//搜索个数
	public static function searchEPGCategoryCount($searchCondition){
		$searchString=PublicModel::controlsearch($searchCondition);
    	if($searchString!='')
    		$searchString=' WHERE  '.$searchString;
    	$sql=sprintf(
    			"SELECT 
				  count(*)
				FROM
				  `skyg_res`.`res_epg_category`
    			%s
				",$searchString);
    	$result=parent::createSQL($sql)->toValue();
    	return $result;
	}
	
	//搜索
	public static function searchEPGCategory($searchCondition,$start,$limit,$orderCondition=array('epg_cat_id'=>'DESC')){
		$orderString=PublicModel::controlArray($orderCondition);
    	$searchString=PublicModel::controlsearch($searchCondition);
    	if($searchString!='')
    		$searchString=' WHERE  '.$searchString;
    	$sql=sprintf(
				"SELECT 
				  `epg_cat_id`,
				  `epg_cat_name`,
				  `index`,
				  `created_date`
				FROM
				  `skyg_res`.`res_epg_category`
    			%s
				ORDER BY %s 
				LIMIT %d,%d",$searchString,$orderString,$start,$limit);
		$result=parent::createSQL($sql)->toList();
		return $result;  
	}
	
	
	//////////////////epg_program///////////////////
	public static function getNewProgramCount(){
		$sql= sprintf("SELECT 
						  count(*)
						FROM
						  `skyg_res`.`res_program` AS p 
						LEFT JOIN skyg_res.`res_epg_pro_category` AS c
						    ON p.`program_name`=c.`pg_name`  
						JOIN skyg_res.`res_epg_category` AS rec
						    ON c.`epg_cat_id`=rec.`epg_cat_id`
						JOIN `skyg_res`.`res_channel` AS t
						    ON t.`channel_id` = p.`channel_id` 
						WHERE p.`begintime` > NOW() AND t.`channel_status` = 0
						GROUP BY p.`program_id` ");
		$result=parent::createSQL($sql)->toValue();
		return $result;
		
	}
	
	public static function getNewProgram($start,$limit,$orderCondition=array('program_id'=>'DESC')){
		$orderString=PublicModel::controlArray($orderCondition);
		$sql=
		sprintf("SELECT 
				  c.`pro_cat_id`,
				  p.`program_name`,
				  p.`channel_id`,
				  p.`begintime` AS `start_time`,
				  p.`endtime` AS `end_time`,
				  c.`epg_cat_id`,
				  t.`channel_name` ,
				  rec.`epg_cat_name`
				FROM
				  `skyg_res`.`res_program` AS p 
				LEFT JOIN skyg_res.`res_epg_pro_category` AS c
				    ON p.`program_name`=c.`pg_name`  
				JOIN skyg_res.`res_epg_category` AS rec
				    ON c.`epg_cat_id`=rec.`epg_cat_id`
				JOIN `skyg_res`.`res_channel` AS t
				    ON t.`channel_id` = p.`channel_id` 
				WHERE p.`begintime` > NOW() AND t.`channel_status` = 0
				GROUP BY p.`program_id`  
				ORDER BY %s 
				LIMIT %d, %d ",
			$orderString,$start,$limit);
		$result=parent::createSQL($sql)->toList();
		return $result;
		
	}
	
	public static function searchNewProgramCount($searchCondition){
		$searchString=PublicModel::controlsearch($searchCondition);
		if($searchString!='')
			$searchString=' AND  '.$searchString;
		$sql= sprintf("SELECT 
				  count(*)
				FROM
				  `skyg_res`.`res_program` AS p 
				LEFT JOIN skyg_res.`res_epg_pro_category` AS c
				    ON p.`program_name`=c.`pg_name`  
				JOIN skyg_res.`res_epg_category` AS rec
				    ON c.`epg_cat_id`=rec.`epg_cat_id`
				JOIN `skyg_res`.`res_channel` AS t
				    ON t.`channel_id` = p.`channel_id` 
				WHERE p.`begintime` > NOW() 
				AND t.`channel_status` = 0
				%s 
				GROUP BY p.`program_id`",$searchString);
		$result=parent::createSQL($sql)->toValue();
		return $result;
	
	}
	
	public static function searchNewProgram($searchCondition,$start,$limit,$orderCondition=array('program_id'=>'DESC')){
		$searchString=PublicModel::controlsearch($searchCondition);
		if($searchString!='')
    		$searchString=' AND  '.$searchString;
		$orderString=PublicModel::controlArray($orderCondition);
		$sql=
		sprintf("SELECT 
				  c.`pro_cat_id`,
				  p.`program_name`,
				  p.`channel_id`,
				  p.`begintime` AS `start_time`,
				  p.`endtime` AS `end_time`,
				  c.`epg_cat_id`,
				  t.`channel_name` ,
				  rec.`epg_cat_name`
				FROM
				  `skyg_res`.`res_program` AS p 
				LEFT JOIN skyg_res.`res_epg_pro_category` AS c
				    ON p.`program_name`=c.`pg_name`  
				JOIN skyg_res.`res_epg_category` AS rec
				    ON c.`epg_cat_id`=rec.`epg_cat_id`
				JOIN `skyg_res`.`res_channel` AS t
				    ON t.`channel_id` = p.`channel_id` 
				WHERE p.`begintime` > NOW() 
				AND t.`channel_status` = 0
				%s 
				GROUP BY p.`program_id`  
				ORDER BY %s 
				LIMIT %d, %d ",
			$searchString,$orderString,$start,$limit);
		$result=parent::createSQL($sql)->toList();
		return $result;
	
	}
	
	/**
	 * 人工更新节目
	 * @param unknown $id
	 * @param unknown $pg_name
	 * @param unknown $cg_id
	 * @param unknown $creater
	 * @return Ambigous <number, string, multitype:string >|boolean
	 */
	public static function updateProgram($id,$pg_name,$epg_cat_id){
		$updateST=array();
		if ($pg_name !=null){
			$updateST[]=sprintf('`pg_name`="%s"',addslashes($pg_name));
			//标记人工更新过，不能再被classifyProgram和addProgram更改
			$updateST[]='`pg_name_changed`=1';
		}
		if ($epg_cat_id!=null) $updateST[]=sprintf('`epg_cat_id`=%d',$epg_cat_id);
		if (count($updateST)){
			//$updateST[]=sprintf('`creater`="%s"',addslashes($creater));
			$updateST[]=sprintf('`modify_date`=now()');
			$sql=sprintf('UPDATE `skyg_res`.`res_epg_pro_category` SET %s WHERE `pro_cat_id`=%d',implode(", ", $updateST),$id);
			return parent::createSQL($sql)->exec();
		}else return false;
	}
	
	
}