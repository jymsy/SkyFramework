<?php
namespace resource\models;

use Sky\db\DBCommand;
use skyosadmin\components\PublicModel;

/** table skyg_res. 
 * 
 * @author Zhengyun
 */
class MenuManageModel extends \Sky\db\ActiveRecord{
	/**
	 *@return MenuManageModel
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}		
	
	//删除
	public static function deleteMenuCategory($menu_cat_id){		
		return parent::createSQL(
				"DELETE
				FROM    
				 	  `skyg_res`.`res_menu_category`				
				WHERE `menu_cat_id` = :menu_cat_id ",
				array(
						"menu_cat_id"=>(int)$menu_cat_id
				)
		)->exec();
		
	}
	
	//编辑($array)
	public static function updateMenuCategory($arr){
		extract($arr);
		return parent::createSQL(
			"UPDATE 
			  skyg_res.`res_menu_category` 
			SET
			  `menu_cat_name` = :menu_cat_name,
			  `menu_cat_type` = :menu_cat_type,
			  `pack_name` = :pack_name,
			  `pack_para` = :pack_para
			WHERE `menu_cat_id` = :menu_cat_id ",
			array(
					  "menu_cat_name"=>$menu_cat_name,
					  "menu_cat_type"=>$menu_cat_type,
					  "pack_name"=>$pack_name,
					  "pack_para"=>$pack_para,
					  "menu_cat_id"=>$menu_cat_id
				)
		)->exec();	
				
	}	
	
	
	//添加($array)
	 /**
	  * 
	  * @param array $arr
	  * 
	  * 
	  * @return 添加成功，返回新增id（id>0），0-添加失败
	  */
	public static function insertMenuCategory($arr){
		extract($arr);
		$result=parent::createSQL(
				"INSERT INTO skyg_res.`res_menu_category` (
				  `menu_cat_name`,
				  `menu_cat_type`,
				  `pack_name`,
				  `pack_para`
				) 
				VALUES
				  (
				    :menu_cat_name,
				    :menu_cat_type,
				    :pack_name,
				    :pack_para
				  ) ",
				array(
					  "menu_cat_name"=>$menu_cat_name,
					  "menu_cat_type"=>$menu_cat_type,
					  "pack_name"=>$pack_name,
					  "pack_para"=>$pack_para						
						)
				)->exec();
		return $result;
		
	}		
	
	//获取一个分类
	public static function getMenuCategoryByID($menu_cat_id){
		return parent::createSQL(
				"SELECT 
				  `menu_cat_name`,
				  `menu_cat_type`,
				  `pack_name`,
				  `pack_para` 
				FROM
				  skyg_res.`res_menu_category` 
				WHERE `menu_cat_id` = :menu_cat_id ",
				array(
						"menu_cat_id"=>(int)$menu_cat_id
				)
		)->toList();
		
	}	
	
	
	//获取分类个数
	public static function getMenuCategoryCount(){		
		return parent::createSQL(
				"SELECT
				  count(*)
				FROM
				  `skyg_res`.`res_menu_category`"		
		)->toValue();		
	}
	//获取分类详情
	public static function getMenuCategory($start,$limit,$orderCondition=array('menu_cat_id'=>'ASC')){
		$orderString=PublicModel::controlArray($orderCondition);
		$sql=sprintf(
				"SELECT
				  `menu_cat_id`,
				  `menu_cat_name`,
				  `menu_cat_type`,
				  `pack_name`,
				  `pack_para`
				FROM
				  `skyg_res`.`res_menu_category`
				ORDER BY %s 
				LIMIT %d,%d",$orderString,$start,$limit);
		$result=parent::createSQL($sql)->toList();
		return $result;
		
	}
	
	
	//分类搜索
	public static function searchMenuCategoryCount($searchCondition){
		$searchString=PublicModel::controlsearch($searchCondition);
		if($searchString!='')
			$searchString=' WHERE  '.$searchString;
		$sql=sprintf("SELECT
				  count(*)
				FROM
				  `skyg_res`.`res_menu_category`
				 %s",$searchString);
		//var_dump($sql);
		return parent::createSQL($sql)->toValue();
		
	}
	//分类搜索
	public static function searchMenuCategory($searchCondition,$start,$limit,$orderCondition=array('menu_cat_id'=>'ASC')){
		$orderString=PublicModel::controlArray($orderCondition);
		$searchString=PublicModel::controlsearch($searchCondition);
		if($searchString!='')
			$searchString=' WHERE  '.$searchString;
		$sql=sprintf(
				"SELECT
				  `menu_cat_name`,
				  `menu_cat_type`,
				  `pack_name`,
				  `pack_para`
				FROM
				  `skyg_res`.`res_menu_category`
				%s 
				ORDER BY %s
				LIMIT %d,%d",$searchString,$orderString,$start,$limit);
		$result=parent::createSQL($sql)->toList();
		return $result;
		
	}
	
	//////////////`skyg_res`.`res_menu_res_map`/////////////////////////
	
	//删除
	public static function deleteMenuResMap($menu_res_map_id){
		return parent::createSQL(
				"DELETE
				FROM
				 	  `skyg_res`.`res_menu_res_map`
				WHERE `menu_res_map_id` = :menu_res_map_id ",
				array(
						"menu_res_map_id"=>(int)$menu_res_map_id
				)
		)->exec();
	
	}
	
	//编辑($array)
	public static function updateMenuResMap($arr){
		extract($arr);
		return parent::createSQL(
			"UPDATE 
			  skyg_res.`res_menu_res_map` 
			SET
			  `menu_cat_id` = :menu_cat_id,
			  `res_id` = :res_id,
			  `res_type` = :res_type,
			  `title` = :title,
			  `url` = :url,
			  `img_url` = :img_url,
			  `img_url_big` = :img_url_big,
			  `index` = :index,
			  `state` = :state,
			  `pa_name` = :pa_name,
			  `platform_info` = :platform_info,
			  `source` = :source,
			  `pic_flag` = :pic_flag,
			  `version` = :version,
			  `version_int` = :version_int,
			  `res_size` = :res_size,
			  `pre_url`=:pre_url 
			WHERE `menu_res_map_id` = :menu_res_map_id ",
				array(
			  "menu_cat_id" =>$menu_cat_id,
			  "res_id" =>$res_id,
			  "res_type" =>$res_type,
			  "title" =>$title,
			  "url" =>$url,
			  "img_url" =>$img_url,
			  "img_url_big" =>$img_url_big,
			  "index" =>$index,
			  "state"=>$state,
			  "pa_name" =>$pa_name,
			  "platform_info" =>$platform_info,
			  "source" =>$source,
			  "pic_flag" =>$pic_flag,
			  "version" =>$version,
			  "version_int" =>$version_int,
			  "res_size" =>$res_size,
			  "pre_url"=>$pre_url,
			  "menu_res_map_id" =>$menu_res_map_id
				)
		)->exec();
	
	}
	
	
	//添加($array)
	/**
	 *
	 * @param array $arr
	 * @return 添加成功，返回新增id（id>0），0-添加失败
	 */
	public static function insertMenuResMap($arr){
		extract($arr);
		$result=parent::createSQL(
				"INSERT INTO skyg_res.`res_menu_res_map` (
				  `menu_cat_id`,
				  `res_id`,
				  `res_type`,
				  `title`,
				  `url`,
				  `img_url`,
				  `img_url_big`,
				  `index`,
				  `state`,
				  `pa_name`,
				  `platform_info`,
				  `source`,
				  `pic_flag`,
				  `version`,
				  `version_int`,
				  `res_size`,
				  `pre_url`
				) 
				VALUES
				  (
				    :menu_cat_id,
				    :res_id,
				    :res_type,
				    :title,
				    :url,
				    :img_url,
				    :img_url_big,
				    :index,
				    :state,
				    :pa_name,
				    :platform_info,
				    :source,
				    :pic_flag,
				    :version,
				    :version_int,
				    :res_size,
				    :pre_url 
				  ) ",
				array(
				  "menu_cat_id" =>$menu_cat_id,
				  "res_id" =>$res_id,
				  "res_type" =>$res_type,
				  "title" =>$title,
				  "url" =>$url,
				  "img_url" =>$img_url,
				  "img_url_big" =>$img_url_big,
				  "index" =>$index,
				  "state"=>$state,
				  "pa_name" =>$pa_name,
				  "platform_info" =>$platform_info,
				  "source" =>$source,
				  "pic_flag" =>$pic_flag,
				  "version" =>$version,
				  "version_int" =>$version_int,
				  "res_size" =>$res_size,
				  "pre_url"=>$pre_url
				)
		)->exec();
		return $result;	
	}
	
	//获取全部资源映射个数
	public static function getMenuResMapCount(){
		return parent::createSQL(
				"SELECT
				  count(*)
				FROM
				  skyg_res.`res_menu_res_map`"
		)->toValue();
	}
	//获取全部资源映射详情
	public static function getMenuResMap($start,$limit,$orderCondition=array('create_date'=>'DESC')){
		$orderString=PublicModel::controlArray($orderCondition);
		$sql=sprintf(
				"SELECT 
				  rmrm.`menu_res_map_id`,
				  rmrm.`menu_cat_id`,
				  rmrm.`res_id`,
				  rmrm.`res_type`,
				  rmrm.`title`,
				  rmrm.`url`,
				  rmrm.`img_url`,
				  rmrm.`img_url_big`,
				  rmrm.`index`,
				  rmrm.`state`,
				  rmrm.`create_date`,
				  rmrm.`pa_name`,
				  rmrm.`platform_info`,
				  rmrm.`source`,
				  rmrm.`pic_flag`,
				  rmrm.`version`,
				  rmrm.`version_int`,
				  rmrm.`res_size`,
				  rmrm.`pre_url`,
				  rmc.`menu_cat_name`
				FROM
				  skyg_res.`res_menu_res_map` AS rmrm
				 LEFT JOIN skyg_res.`res_menu_category` AS rmc
				 ON rmrm.`menu_cat_id`=rmc.`menu_cat_id` 
				ORDER BY %s
				LIMIT %d,%d",$orderString,$start,$limit);
		$result=parent::createSQL($sql)->toList();
		return $result;
	
	}
	//获取某种分类的资源映射个数
	public static function getMenuResMapCountByCat($menu_cat_id){
		return parent::createSQL(
				"SELECT
				  count(*)
				FROM
				  skyg_res.`res_menu_res_map`
				WHERE
				   `menu_cat_id`=:menu_cat_id",
				array(
					'menu_cat_id'=>(int)$menu_cat_id)
		)->toValue();
	}
	//获取某种分类的资源映射详情
	public static function getMenuResMapByCat($menu_cat_id,$start,$limit,$orderCondition=array('create_date'=>'DESC')){
		$orderString=PublicModel::controlArray($orderCondition);
		$sql=sprintf(
				"SELECT 
				  rmrm.`menu_res_map_id`,
				  rmrm.`menu_cat_id`,
				  rmrm.`res_id`,
				  rmrm.`res_type`,
				  rmrm.`title`,
				  rmrm.`url`,
				  rmrm.`img_url`,
				  rmrm.`img_url_big`,
				  rmrm.`index`,
				  rmrm.`state`,
				  rmrm.`create_date`,
				  rmrm.`pa_name`,
				  rmrm.`platform_info`,
				  rmrm.`source`,
				  rmrm.`pic_flag`,
				  rmrm.`version`,
				  rmrm.`version_int`,
				  rmrm.`res_size` ,
				  rmrm.`pre_url`,
				  rmc.`menu_cat_name`
				FROM
				  skyg_res.`res_menu_res_map` AS rmrm
				 LEFT JOIN skyg_res.`res_menu_category` AS rmc
				 ON rmrm.`menu_cat_id`=rmc.`menu_cat_id`
				WHERE
				   rmrm.`menu_cat_id`=%d 
				ORDER BY %s
				LIMIT %d,%d",$menu_cat_id,$orderString,$start,$limit);
		$result=parent::createSQL($sql)->toList();
		return $result;
	
	}
	
	//分类搜索
	public static function searchMenuResMapCount($searchCondition){
		$searchString=PublicModel::controlsearch($searchCondition);
		if($searchString!='')
			$searchString=' WHERE  '.$searchString;
		$searchString=str_replace("menu_cat_id", "rmrm`.`menu_cat_id", $searchString);
		$sql=sprintf("SELECT
				  count(*)
				FROM
				  skyg_res.`res_menu_res_map` AS rmrm
				 LEFT JOIN skyg_res.`res_menu_category` AS rmc
				 ON rmrm.`menu_cat_id`=rmc.`menu_cat_id`
				 %s",$searchString);
		//var_dump($sql);
		return parent::createSQL($sql)->toValue();
	
	}
	//分类搜索
	public static function searchMenuResMap($searchCondition,$start,$limit,$orderCondition=array('create_date'=>'DESC')){
		$orderString=PublicModel::controlArray($orderCondition);
		$searchString=PublicModel::controlsearch($searchCondition);
		if($searchString!='')
			$searchString=' WHERE  '.$searchString;
		$searchString=str_replace("menu_cat_id", "rmrm`.`menu_cat_id", $searchString);
		$sql=sprintf(
				"SELECT 
				  rmrm.`menu_res_map_id`,
				  rmrm.`menu_cat_id`,
				  rmrm.`res_id`,
				  rmrm.`res_type`,
				  rmrm.`title`,
				  rmrm.`url`,
				  rmrm.`img_url`,
				  rmrm.`img_url_big`,
				  rmrm.`index`,
				  rmrm.`state`,
				  rmrm.`create_date`,
				  rmrm.`pa_name`,
				  rmrm.`platform_info`,
				  rmrm.`source`,
				  rmrm.`pic_flag`,
				  rmrm.`version`,
				  rmrm.`version_int`,
				  rmrm.`res_size` ,
				  rmrm.`pre_url`,
				  rmc.`menu_cat_name`
				FROM
				  skyg_res.`res_menu_res_map` AS rmrm
				 LEFT JOIN skyg_res.`res_menu_category` AS rmc
				 ON rmrm.`menu_cat_id`=rmc.`menu_cat_id`				
				%s
				ORDER BY %s
				LIMIT %d,%d",$searchString,$orderString,$start,$limit);
		$result=parent::createSQL($sql)->toList();
		return $result;
	
	}
	
}