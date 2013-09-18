<?php
namespace circle\models;

use Sky\db\DBCommand;
use skyosadmin\components\PublicModel;

/** table res.circle_list
 *  table res.circle_category 
 * 
 * @author Zhengyun
 */
class CircleManageModel extends \Sky\db\ActiveRecord{
	/**
	 *@return CircleManageModel
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}		
	
	
	////////////////circle_list///////////////////////
	//删除
	public static function deleteCircle($circle_id){
		return parent::createSQL(
				"DELETE
				FROM    
				 	  `res`.`circle_list`				
				WHERE `circle_id` = :circle_id ",
				array(
						"circle_id"=>(int)$circle_id
				)
		)->exec();
		
		
	}
	
	//编辑($array)
	public static function updateCircle($arr){
		extract($arr);
		return parent::createSQL(
			"UPDATE 
			  res.`circle_list` 
			SET
			  `circle_title`=:circle_title,
			  `circle_content`=:circle_content,
			  `circle_pic`=:circle_pic,
			  `circle_state`=:circle_state,
			  `cc_id`=:cc_id,
			  `max_user_count`=:max_user_count  
			WHERE `circle_id`=:circle_id ",
				array(
					  'circle_title'=>$circle_title,
					  'circle_content'=>$circle_content,
					  'circle_pic'=>$circle_pic,
					  'circle_state'=>$circle_state,
					  'cc_id'=>$cc_id,
					  'max_user_count'=>$max_user_count,
					  'circle_id'=>$circle_id
						
				)
		)->exec();	
				
	}
	
	
	//添加($array)
	 /**
	  * 
	  * @param array $arr
	  * @return 添加成功，返回新增id（id>0），0-添加失败
	  */
	public static function insertCircle($arr){
		extract($arr);
		$result=parent::createSQL(
				"INSERT INTO res.`circle_list` (
				  `circle_title`,
				  `circle_content`,
				  `circle_pic`,
				  `circle_state`,
				  `cc_id`,
				  `max_user_count`			  
				) 
				VALUES
				  ( 
				    :circle_title,
				    :circle_content,
				    :circle_pic,
					:circle_state,
					:cc_id,
					:max_user_count)",
				array(
					  'circle_title'=>$circle_title,
					  'circle_content'=>$circle_content,
					  'circle_pic'=>$circle_pic,
					  'circle_state'=>$circle_state,
					  'cc_id'=>$cc_id,
					  'max_user_count'=>$max_user_count			
				)
			);
		if($result->exec()!=0){
			$result->getPdoInstance();
			$result=$result->lastInsertID();
			return $result;
		}
		return 0;
		
	}		
	
	//获取一个圈子信息
	public static function getCircleByID($circle_id){		
		return parent::createSQL(
			"SELECT 
				  rci.`circle_id`,
				  rci.`circle_title`,
				  rci.`circle_content`,
				  rci.`circle_pic`,
				  rci.`circle_state`,
				  rci.`creat_date`,
				  rci.`max_user_count`,
				  rci.cc_id,
				  rcca.`cc_name`
			FROM
				 res.`circle_list` AS rci 
			LEFT JOIN res.`circle_category` AS rcca 
			ON rci.`cc_id`=rcca.`cc_id`
			WHERE
			  `circle_id`=:circle_id",
			array(					
					'circle_id'=>$circle_id	
			)
		)->toList();		
	}
	
	
	//获取所有圈子个数
	/**
	 * 
	 * @param Int $cc_id (if cc_id==0,get the whole of circle_list)
	 * @return number
	 */
	public static function getAllCircleCount($cc_id){
		$s_where='';
		if($cc_id!=0)
			$s_where=sprintf("where `rci`.`cc_id`=%d",$cc_id);
		return parent::createSQL(
			"SELECT 
			  count(*) 
			FROM
				res.`circle_list` AS rci 
			LEFT JOIN res.`circle_category` AS rcca 
			ON rci.`cc_id`=rcca.`cc_id` ".$s_where			
		)->toValue();
	}
	//获取所有圈子
	/**
	 * 
	 * @param Int $cc_id (if cc_id==0,get the whole of circle_list)
	 * @param Int $start
	 * @param Int $limit
	 * @param Int $orderCondition
	 * @return number
	 */
	public static function getAllCircleList($cc_id,$start,$limit,$orderCondition=array('circle_id'=>'DESC')){
		$s_where='';
		if($cc_id!=0)
			$s_where=sprintf("where `rci`.`cc_id`=%d",$cc_id);
		$orderString=PublicModel::controlArray($orderCondition);
		$sql=sprintf(
				"SELECT 				  
				  rci.`circle_id`,
				  rci.`circle_title`,
				  rci.`circle_content`,
				  rci.`circle_pic`,
				  rci.`circle_state`,
				  rci.`creat_date`,
				  rci.`max_user_count`,				
				  rci.cc_id,
				  rcca.`cc_name`
				FROM
				  res.`circle_list` AS rci 
				LEFT JOIN res.`circle_category` AS rcca 
				ON rci.`cc_id`=rcca.`cc_id`
    			%s
				ORDER BY %s
				LIMIT %d,%d",$s_where,$orderString,$start,$limit);
		$result=parent::createSQL($sql)->toList();
		return $result;
		
	}	
	
	//搜索个数
	public static function searchCircleCount($searchCondition){
		$searchString=PublicModel::controlsearch($searchCondition);
    	if($searchString!='')
    		$searchString=' WHERE  '.$searchString;
    	$sql=sprintf(
    			"SELECT 
				  count(*)
				FROM
				  res.`circle_list` AS rci 
				LEFT JOIN res.`circle_category` AS rcca 
				ON rci.`cc_id`=rcca.`cc_id`
    			%s
				",$searchString);
    	$result=parent::createSQL($sql)->toValue();
    	return $result;
	}
	//搜索
	public static function searchCircle($searchCondition,$start,$limit,$orderCondition=array('circle_id'=>'DESC')){
		$orderString=PublicModel::controlArray($orderCondition);
    	$searchString=PublicModel::controlsearch($searchCondition);
    	if($searchString!='')
    		$searchString=' WHERE  '.$searchString;
    	$sql=sprintf(
				"SELECT 
				  rci.`circle_id`,
				  rci.`circle_title`,
				  rci.`circle_content`,
				  rci.`circle_pic`,
				  rci.`circle_state`,
				  rci.`creat_date`,
				  rci.`max_user_count`,    			
				  rci.cc_id,
				  rcca.`cc_name`
				FROM
				  res.`circle_list` AS rci 
				LEFT JOIN res.`circle_category` AS rcca 
				ON rci.`cc_id`=rcca.`cc_id`
    			%s
				ORDER BY %s 
				LIMIT %d,%d",$searchString,$orderString,$start,$limit);
		$result=parent::createSQL($sql)->toList();
		return $result;  
	}
	
	////////////////circle_category///////////////////////
	//添加圈子分类
	public static function insertCircleCategory($arr){
		extract($arr);
		$result=parent::createSQL(
				"INSERT INTO res.`circle_category` (`cc_name`, `logo`, `cc_order`)  
				VALUES
				  (:cc_name, :logo, :cc_order)",
				array(
						'cc_name'=>$cc_name,
						'logo'=>$logo,
						'cc_order'=>$cc_order
				)
		);
		if($result->exec()!=0){
			$result->getPdoInstance();
			$result=$result->lastInsertID();
			return $result;
		}
		
		return 0;
	}
	
	//更新圈子分类
	public static function updateCircleCategory($arr){
		extract($arr);
		$result=parent::createSQL(
				"UPDATE
					res.`circle_category`
				SET
					`cc_name` = :cc_name,
					`logo` = :logo,
					`cc_order` = :cc_order
				WHERE `cc_id` = :cc_id",
				array(
						'cc_name'=>$cc_name,
						'logo'=>$logo,
						'cc_order'=>$cc_order,
						'cc_id'=>$cc_id
				)
		)->exec();
		
		return $result;
	}
	
	//删除圈子分类
	public static function deleteCircleCategory($circle_category_id){
		return $result=parent::createSQL(
				"DELETE FROM
					res.`circle_category`
				WHERE `cc_id` = :cc_id",
				array(
						'cc_id'=>$circle_category_id
				)
		)->exec();
	}
	
	//获取圈子分类数量
	public static function getCircleCategoryCount(){
		$result=parent::createSQL("SELECT
				  count(*)
				FROM
				  res.`circle_category`
    			")->toValue();
    	return $result;
	}
	
	//获取圈子分类列表
	public static function getCircleCategoryList($start,$limit,$orderCondition=array('cc_id'=>'ASC')){
		$orderString=PublicModel::controlArray($orderCondition);
    	$sql=sprintf(
    			"SELECT 
				  `cc_id`,
				  `cc_name`,
				  `logo`,
				  `cc_order` 
				FROM
				  res.`circle_category` 
    			ORDER BY %s 
				LIMIT %d,%d",$orderString,$start,$limit);
    	$result=parent::createSQL($sql)->toList();
    	return $result;
	}
	
	//搜索圈子分类数量
	public static function searchCircleCategoryCount($searchCondition){
		$searchString=PublicModel::controlsearch($searchCondition);
    	if($searchString!='')
    		$searchString=' WHERE  '.$searchString;
    	$sql=sprintf(
    			"SELECT
    			  count(*)
				FROM
				  res.`circle_category` 
    			%s
				",$searchString);
    	$result=parent::createSQL($sql)->toValue();
    	return $result;
	}
	
	//搜索圈子分类列表
	public static function searchCircleCategoryList($searchCondition,$start,$limit,$orderCondition=array('cc_id'=>'ASC')){
		$orderString=PublicModel::controlArray($orderCondition);
    	$searchString=PublicModel::controlsearch($searchCondition);
    	if($searchString!='')
    		$searchString=' WHERE  '.$searchString;
    	$sql=sprintf(
				"SELECT 
				  `cc_id`,
				  `cc_name`,
				  `logo`,
				  `cc_order` 
				FROM
				  res.`circle_category` 
    			%s
				ORDER BY %s 
				LIMIT %d,%d",$searchString,$orderString,$start,$limit);
		$result=parent::createSQL($sql)->toList();
		return $result;  
	}
	
	
}