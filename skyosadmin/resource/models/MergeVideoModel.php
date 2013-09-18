<?php

namespace resource\models;

use skyosadmin\components\PublicModel;


use Sky\db\DBCommand;

/**table 
 */
class MergeVideoModel extends \Sky\db\ActiveRecord{
	/**
	 *@return MergeVideoModel
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}	
	
	/**
	 * @param string $star
	 * @param string $pagesize
	 * @return multitype:  返回合并未合并列表
	 */
	public static function getVideoList($start,$limit,$orderCondition=array('v_id'=>'DESC')){
		$orderString=PublicModel::controlArray($orderCondition);
		$sqls="SELECT
					`v_id`,
					`title`,
					`created_date`,
				    `actor`,
				    `director`,
					(
					CASE
					WHEN `parent_id` IS NULL
					THEN 0
					ELSE 1
					END
					) AS `status`
					FROM
					skyg_res.`res_video`
				WHERE IFNULL(parent_id, 0) = 0
				ORDER BY %s
				LIMIT %d,%d";
		
		$sqls = sprintf($sqls,$orderString,$start,$limit);
		return  parent::createSQL($sqls)->toList(); 
	}
	/**
	 * 
	 * @return Ambigous <NULL, unknown> 返回count值
	 */
	public static function getVideoListCount(){
		$sqls="SELECT
					count(1)
					FROM
					skyg_res.`res_video`
				WHERE IFNULL(parent_id, 0) = 0";
		return  parent::createSQL($sqls)->toValue();
	}
    
	/**
	 * 
	 * @param int $v_pid           合并未合并标识
	 * @param string $star
	 * @param string $pagesize
	 * @return multitype:          返回合并/未合并列表
	 */
	public static function getVideoByPid(array $v_string,$star,$pagesize){
		$sql="";
		if(!empty($v_string)){
			foreach($v_string as $k=>$v){
				if ($k=='')continue;
				
				if ($sql){
					if($k=='status'){
						$k=='parent_id';
						if($v==0){
							$sql.=" and `$k` is null ";
						}else {
							$sql.=" and `$k`=0 ";
						}
						
					}else{
					$sql .= " and `$k` like '%".$v."%'";
					}
				}else {
					if($k=='status'){
						$k=='parent_id';
						if($v==0){
							$sql.="`$k` is null ";
						}else {
							$sql.="`$k`=0 ";
						}
					
					}else{
					$sql .= " `$k` like '%".$v."%' ";
					}
				}
			}
		}
		
		
		return parent::createSQL("SELECT 
									  `v_id`,
									  `title`,
									  `created_date`,
				                      `actor`,
				                      `director`,
									  (
									    CASE
									      WHEN `parent_id` IS NULL 
									      THEN 0 
									      ELSE 1 
									    END
									  ) AS `status` 
									FROM
									  skyg_res.`res_video` where ".$sql." 
				                    LIMIT :star,:pagesize",
				               array( "star"=>(int)$star,
				               		  "pagesize"=>(int)$pagesize
				               		  ))->toList();
	}
	
	/**
	 * 
	 * @param int $v_pid    合并未合并标识
	 * @return multitype:
	 */
	public static function getVideoByPidCount($v_string){
		$sql="";
		if(!empty($v_string)){
			foreach($v_string as $k=>$v){
				if ($k=='')continue;
				
				if ($sql){
					if($k=='parent_id'){
						if($v==0){
							$sql.=" and `$k` is null ";
						}else {
							$sql.=" and `$k`=0 ";
						}
						
					}else{
					$sql .= " and `$k` like '%".$v."%'";
					}
				}else {
					if($k=='parent_id'){
						if($v==0){
							$sql.="`$k` is null ";
						}else {
							$sql.="`$k`=0 ";
						}
					
					}else{
					$sql .= " `$k` like '%".$v."%' ";
					}
				}
			}
		}
		return parent::createSQL("select count(1)
									FROM
									  skyg_res.`res_video` where ".$sql)->toValue();
	}
    
	/**
	 * 
	 * @param int $vid      影视ID
	 * @return multitype:   返回详情列表
	 */
	public static function getVideoByVid($vid){
		return parent::createSQL("SELECT 
									  rv.`v_id`,
									  rv.`title`,
									  rv.`release_date`,
									  (
									    CASE
									      WHEN `parent_id` IS NULL 
									      THEN 0 
									      ELSE 1 
									    END
									  ) AS `status` ,
									  rv.`description`,
									  rv.`actor`,
									  rv.`director`,
									  rv.`classfication`,
									  rv.`score`,
				                      rv.`thumb`,
									  rvs.`source`
									FROM
									  skyg_res.`res_video` AS rv,skyg_res.`res_video_site` AS rvs
									WHERE rv.`v_id`=rvs.`v_id`
									  AND IFNULL(rv.`parent_id`, 0) = 0 
									  AND rv.`v_id`=:vid
									ORDER BY rv.`release_date` DESC
									LIMIT 1",
				               array( "vid"=>(int)$vid
				               		  ))->toList();
	}
	
	/**
	 * 
	 * @param string  $v_title   影片标题
	 * @return multitype:        返回条件为TITLE的模糊查询的列表
	 */
	public static function getVideoTitleRelation($v_andstring,$start,$limit,$orderCondition=array('v_id'=>'DESC')){
		$v_sql="";
		$v_sql=str_replace(array('title','status','actor','director'), array('rv.`title`','rv.`status`','rv.`actor`','rv.`director`'), $v_andstring);
		$v_sql=str_replace("rv.`status` = '0'","rv.`parent_id` is null",$v_sql);
		$v_sql=str_replace("rv.`status` = '1'","rv.`parent_id` = 0",$v_sql);
		
		$orderString=PublicModel::controlArray($orderCondition);
		$orderString=str_replace("v_id", "c`.`v_id", $orderString);
		$orderString=str_replace("`title`", "CONVERT(c.`title` USING gbk)", $orderString);
		$orderString=str_replace("created_date", "c`.`created_date", $orderString);
		$orderString=str_replace("status", "c`.`status", $orderString);
		$orderString=str_replace("`actor`", "CONVERT(c.`actor` USING gbk)", $orderString);
		$orderString=str_replace("`director`", "ONVERT(c.`director` USING gbk)", $orderString);
		$orderString=str_replace("source", "c`.`source", $orderString);
		$orderString=str_replace("url", "c`.`url", $orderString);
		
		
		$sqls="SELECT  c.`v_id`,
					  c.`title`,
					  c.`created_date`,
					  c.`status` ,
					  c.`actor`,
					  c.`director`,
					  c.`source`,
					  c.`url` FROM (
					SELECT  a.`v_id`,
					  a.`title`,
					  a.`created_date`,
					  a.`status` ,
					  a.`actor`,
					  a.`director`,
					  GROUP_CONCAT(a.`source`) AS `source`,
					  a.`url` FROM (
					SELECT 
					  rv.`v_id`,
					  rv.`title`,
					  rv.`created_date`,
					  (
					    CASE
					      WHEN `parent_id` IS NULL 
					      THEN 0 
					      ELSE 1 
					    END
					  ) AS `status` ,
					  rv.`actor`,
					  rv.`director`,
					  rvs.`source`,
					  rvu.`url`
					FROM
					  skyg_res.`res_video` AS rv,skyg_res.`res_video_site` AS rvs,skyg_res.`res_video_url` AS rvu
					WHERE rv.`v_id`=rvs.`v_id`
					  AND rvs.`vs_id`=rvu.`vs_id`
					  AND IFNULL(rv.`parent_id`, 0) = 0 ".$v_sql."
					  GROUP BY rv.`v_id`,rvs.`vs_id`
					  ORDER BY rv.`created_date` DESC,rvu.`collection` DESC) AS a
					  GROUP BY a.`v_id`) AS c ORDER BY ".$orderString." LIMIT ".$start.",".$limit;

		return parent::createSQL($sqls)->toList();
	}
	
	/**
	 * 
	 * @param string $v_title
	 * @return Ambigous <NULL, unknown>  返回TITLE模糊查询的COUNT值
	 */
	public static function getVideoTitleCount($v_andstring){
		$v_sql="";
		$v_sql=str_replace(array('title','status','actor','director'), array('rv.`title`','rv.`status`','rv.`actor`','rv.`director`'), $v_andstring);
		$v_sql=str_replace("rv.`status` = '0'","rv.`parent_id` is null",$v_sql);
		$v_sql=str_replace("rv.`status` = '1'","rv.`parent_id` = 0",$v_sql);
	
		$sqls="SELECT  count(1) FROM (
					SELECT  a.`v_id`,
					  a.`title`,
					  a.`created_date`,
					  a.`status` ,
					  a.`actor`,
					  a.`director`,
					  GROUP_CONCAT(a.`source`) AS `source`,
					  a.`url` FROM (
					SELECT
					  rv.`v_id`,
					  rv.`title`,
					  rv.`created_date`,
					  (
					    CASE
					      WHEN `parent_id` IS NULL
					      THEN 0
					      ELSE 1
					    END
					  ) AS `status` ,
					  rv.`actor`,
					  rv.`director`,
					  rvs.`source`,
					  rvu.`url`
					FROM
					  skyg_res.`res_video` AS rv,skyg_res.`res_video_site` AS rvs,skyg_res.`res_video_url` AS rvu
					WHERE rv.`v_id`=rvs.`v_id`
					  AND rvs.`vs_id`=rvu.`vs_id`
					  AND IFNULL(rv.`parent_id`, 0) = 0 ".$v_sql."
					  GROUP BY rv.`v_id`,rvs.`vs_id`
					  ORDER BY rv.`created_date` DESC,rvu.`collection` DESC) AS a
					  GROUP BY a.`v_id`) AS c ";
		  return parent::createSQL($sqls)->toValue();
	}
	
	/**
	 * 
	 * @param array $vid     vid数组
	 * @param int $vpid      父ID
	 * @return number        更新成功返回1，失败返回0
	 */
	public static function updateVideoAndSite(array $vid,$vpid){
		$vid=implode("','", $vid);
		
		return parent::createSQL("CALL skyg_res.`proc_merger_videosite`('".$vid."',".$vpid.")")->exec();
		
	}
}