<?php
namespace resource\models;

use Sky\db\DBCommand;
use skyosadmin\components\PublicModel;

/** table skyg_res. 
 * 
 * @author Zhengyun
 */
class CategoryManageModel extends \Sky\db\ActiveRecord{
	/**
	 *@return CategoryManageModel
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}		
	
	//删除
	public static function deleteCategory($category_id){
		return parent::createSQL(
				"DELETE
				FROM    
				 	  `skyg_res`.`res_category`				
				WHERE `category_id` = :category_id ",
				array(
						"category_id"=>(int)$category_id
				)
		)->exec();
		
	}
	
	//编辑($array)
	public static function updateCategory($arr){
		extract($arr);
		return parent::createSQL(
			"UPDATE 
			  `skyg_res`.`res_category` 
			SET
			  `category_name` = :category_name,
			  `small_logo` = :small_logo,
			  `big_logo` = :big_logo,
			  `valid` = :valid,
			  `sequence` = :sequence,
			  `action` = :action 
			WHERE `category_id` = :category_id",
				array(
						"category_name" =>$category_name,
						"small_logo" =>$small_logo,
						"big_logo" =>$big_logo,
						"valid" =>$valid,
						"sequence"=>$sequence,
						"action" =>$action,
						"category_id"=>(int)$category_id
				)
		)->exec();			
	}
	
	
	
	//添加($array)
	 /**
	  * 
	  * @param array $arr
	  * @return 添加成功，返回新增id（id>0），0-添加失败
	  */
	public static function insertCategory($arr){
		extract($arr);
		$result=parent::createSQL(
				"CALL skyg_res.`proc_add_category` 
				   (:v_category_name ,
					:i_parent,
					:v_small_logo ,
					:v_big_logo ,
					:i_valid,
					:i_sequence,
					:v_action)",
				array(
						"v_category_name" =>$category_name,
						"i_parent"=>$parent,
						"v_small_logo"=>$small_logo ,
						"v_big_logo"=>$big_logo ,
						"i_valid"=>$valid,
						"i_sequence"=>$sequence,
						"v_action"=>$action						
						)
				)->toValue();
		return $result;
	}		
	
	//获取一个分类
	public static function getCategoryByID($category_id){
		return parent::createSQL(
				"SELECT 
				  `category_id`,
				  `category_name`,
				  `parent`,
				  `path`,
				  `small_logo`,
				  `big_logo`,
				  `valid`,
				  `final_node`,
				  `sequence`,
				  `childs_num`,
				  `childs_update_num`,
				  `action`,
				  `level` 
				FROM
				  `skyg_res`.`res_category` 
				WHERE `category_id` = :category_id ",
				array(
						"category_id"=>(int)$category_id
				)
		)->toList();
	}
	
	//获取第一级分类个数
	public static function getParentCategoryCount(){
		return parent::createSQL(
				"SELECT
				  count(*)
				FROM
				  `skyg_res`.`res_category`
				WHERE `parent` = 0"				
		)->toValue();
	}
	
	//获取第一级分类
	public static function getParentCategory($start,$limit,$orderCondition=array('category_id'=>'ASC')){
		$orderString=PublicModel::controlArray($orderCondition);
		$sql=sprintf(
				"SELECT
				  `category_id`,
				  `category_name`,
				  `parent`,
				  `path`,
				  `small_logo`,
				  `big_logo`,
				  `valid`,
				  `final_node`,
				  `sequence`,
				  `childs_num`,
				  `childs_update_num`,
				  `action`,
				  `level`
				FROM
				  `skyg_res`.`res_category`
				WHERE `parent` = 0
				ORDER BY %s 
				LIMIT %d,%d",$orderString,$start,$limit);
		$result=parent::createSQL($sql)->toList();
		return $result;
	}
	
	//获取子分类个数
	public static function getCategoryByParentIDCount($parent_id){
		return parent::createSQL(
				"SELECT
				  count(*)
				FROM
				  `skyg_res`.`res_category`
				WHERE `parent` = :parent",
				array("parent"=>$parent_id)				
		)->toValue();
	}
	//获取子分类
	public static function getCategoryByParentID($parent_id,$start,$limit,$orderCondition=array('category_id'=>'ASC')){
		$orderString=PublicModel::controlArray($orderCondition);
		$sql=sprintf(
				"SELECT
				  `category_id`,
				  `category_name`,
				  `parent`,
				  `path`,
				  `small_logo`,
				  `big_logo`,
				  `valid`,
				  `final_node`,
				  `sequence`,
				  `childs_num`,
				  `childs_update_num`,
				  `action`,
				  `level`
				FROM
				  `skyg_res`.`res_category`
				WHERE `parent` = %d
				ORDER BY %s 
				LIMIT %d,%d",$parent_id,$orderString,$start,$limit);
		$result=parent::createSQL($sql)->toList();
		return $result;
	}
	
	//获取所有分类个数
	public static function getAllCategoryCount(){
		return parent::createSQL(
				"SELECT
				  count(*)
				FROM
				  `skyg_res`.`res_category`"		
		)->toValue();
	}
	//获取所有分类
	public static function getAllCategory($start,$limit,$orderCondition=array('category_id'=>'ASC')){
		$orderString=PublicModel::controlArray($orderCondition);
		$sql=sprintf(
				"SELECT
				  `category_id`,
				  `category_name`,
				  `parent`,
				  `path`,
				  `small_logo`,
				  `big_logo`,
				  `valid`,
				  `final_node`,
				  `sequence`,
				  `childs_num`,
				  `childs_update_num`,
				  `action`,
				  `level`
				FROM
				  `skyg_res`.`res_category`
				ORDER BY %s 
				LIMIT %d,%d",$orderString,$start,$limit);
		$result=parent::createSQL($sql)->toList();
		return $result;
	}	
	
	//获取所有分类个数
	public static function searchCategoryCount($searchCondition){
		$searchString=PublicModel::controlsearch($searchCondition);
		if($searchString!='')
			$searchString=' WHERE  '.$searchString;
		$sql=sprintf("SELECT
				  count(*)
				FROM
				  `skyg_res`.`res_category`
				 %s",$searchString);
		//var_dump($sql);
		return parent::createSQL($sql)->toValue();
	}
	//获取所有分类
	public static function searchCategory($searchCondition,$start,$limit,$orderCondition=array('category_id'=>'ASC')){
		$orderString=PublicModel::controlArray($orderCondition);
		$searchString=PublicModel::controlsearch($searchCondition);
		if($searchString!='')
			$searchString=' WHERE  '.$searchString;
		$sql=sprintf(
				"SELECT
				  `category_id`,
				  `category_name`,
				  `parent`,
				  `path`,
				  `small_logo`,
				  `big_logo`,
				  `valid`,
				  `final_node`,
				  `sequence`,
				  `childs_num`,
				  `childs_update_num`,
				  `action`,
				  `level`
				FROM
				  `skyg_res`.`res_category`
				%s 
				ORDER BY %s
				LIMIT %d,%d",$searchString,$orderString,$start,$limit);
		$result=parent::createSQL($sql)->toList();
		return $result;
	}
	
}