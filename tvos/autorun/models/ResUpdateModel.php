<?php
namespace tvos\autorun\models;
/**            
 * 
 * @author xiaokeming
 */

class ResUpdateModel extends \Sky\db\ActiveRecord{
	/**
	 *@return ResUpdateModel
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}
	
	/**
	 * 把最新的影视变成OLD
	 * @return number   
	 */
	public static function updateVideoIsOld(){
		return parent::createSQL("update `skyg_res`.`res_video`
				                     set `is_new` = 0
				                   where `is_new` = 1")->exec();
	}
	
	/**
	 *
	 * @param string $v_schemaname   数据库名
	 * @param string $v_tablename    表名
	 * @param string $v_keys         拼接好后的字段名
	 * @param string $v_keyvalues    拼接好后的插入值
	 * @return number                插入成功返回值大于0反之等于0
	 */
	public static function importdata($v_schemaname,$v_tablename,$v_keys,$v_keyvalues){
		$par=parent::createSQL( "INSERT INTO `".$v_schemaname."`.`".$v_tablename."`(".$v_keys.") VALUES(".$v_keyvalues.")");
		if($par->exec()){
			$par->getPdoInstance();
			return $par->lastInsertID();
		}else{
			return 0;
		}
		
	}
	
	/**
	 *
	 * @param string $v_schemaname   数据库名
	 * @param string $v_tablename    表名
	 * @param string $v_set          拼接好后的修改值的字段名
	 * @param string $v_where        拼接好后的修改条件
	 * @return number                修改成功返回值大于0反之等于0
	 */
	public static function modifydata($v_schemaname,$v_tablename,$v_set,$v_where){
		return parent::createSQL("UPDATE `".$v_schemaname."`.`".$v_tablename."` SET ".$v_set." WHERE ".$v_where
				)->exec();
	}
	
	/**
	 *
	 * @param string $v_schema      数据库名
	 * @param string $v_table       数据表名
	 * @param string $v_wheres      拼接后的查询条件
	 * @param string $syscondition  策略控制条件
	 */
	public static function querydatacount($v_schema,$v_table,$v_wheres){
		
		return parent::createSQL( "SELECT
					  COUNT(*) AS `linecount`
					FROM
					  `".$v_schema."`.`".$v_table."`
					WHERE ".$v_wheres)->toValue();
	}
	
	/**
	 *
	 * @param int $autoid   影视播放源表主键ID
	 * @return number       修改成功返回值大于0反之等于0
	 */
	public static function modifyvideosite($autoid){
		 
		return parent::createSQL("UPDATE `skyg_res`.`res_video_site` AS r
									SET
									  r.`v_id` =
									  (SELECT
									    v.`v_id`
									  FROM
									    `skyg_res`.`res_video` AS v
									  WHERE v.`resmark` = r.`v_id`
									  LIMIT 1)
									WHERE r.`vs_id` =:v_autoid ",
				               array( "v_autoid"=>(int)$autoid
				               		  ))->exec();
	}
	
	/**
	 *
	 * @param int $autoid   影视播放源播放地址子集表主键ID
	 * @return number       修改成功返回值大于0反之等于0
	 */
	public static function modifyvideourl($autoid){
		return parent::createSQL("UPDATE `skyg_res`.`res_video_url` AS r
										SET
										  r.`vs_id` =
										  (SELECT
										    v.`vs_id`
										  FROM
										    `skyg_res`.`res_video_site` AS v
										  WHERE v.`resmark` = r.`vs_id`
										  LIMIT 1)
										WHERE r.`vu_id` = :v_autoid ",
				               array( "v_autoid"=>(int)$autoid
				               		  ))->exec();
		 
		 
	}
	
	/**
	 *
	 * @param int $autoid   影视评论表主键ID
	 * @return number       修改成功返回值大于0反之等于0
	 */
	public static function modifyvideocomment($autoid){
		return parent::createSQL("UPDATE `skyg_res`.`res_video_comment` AS r
									SET
									  r.`v_id` =
									  (SELECT
									    v.`v_id`
									  FROM
									    `skyg_res`.`res_video` AS v
									  WHERE v.`resmark` = r.`v_id`
									  LIMIT 1)
									WHERE r.`vc_id` = :v_autoid ",
				               array( "v_autoid"=>(int)$autoid
				               		  ))->exec();
	}
	
	/**
	 *
	 * @param int $autoid   音乐排行表主键ID
	 * @return number       修改成功返回值大于0反之等于0
	 */
	public static function modifymusictopbyid($autoid){
		return parent::createSQL("UPDATE `skyg_res`.`res_music_top` AS mtop
									SET
									  mtop.`category_id` =
									  (SELECT
									    c.category_id
									  FROM
									    `skyg_res`.`res_category` AS c
									  WHERE c.category_name = mtop.resource
									  LIMIT 1)
									WHERE mtop.music_top_id = :v_autoid ",
				               array( "v_autoid"=>(int)$autoid
				               		  ))->exec();
	}
	
	/**
	 *
	 * @param int $autoid    海报信息表主键ID
	 * @return number        修改成功返回值大于0反之等于0
	 */
	public static function modifyplaybillbyid($autoid){
		return parent::createSQL("UPDATE `skyg_res`.`res_playbill` AS p
									SET
									  p.`relation_id` =
									  (SELECT
									    v.`v_id`
									  FROM
									    `skyg_res`.`res_video` AS v
									  WHERE p.`resmark_relation_id` = v.`resmark`
									  LIMIT 1)
									WHERE p.`playbill_id` = :v_autoid ",
				               array( "v_autoid"=>(int)$autoid
				               		  ))->exec();
		 
	}
	
	/**
	 * 根据爬虫ID关联查询出VIDEO表中的主键ID,更新res_playbill表的relation_id
	 * @return number  修改成功返回值大于0反之等于0
	 */
	public static function modifyplaybill(){
		return parent::createSQL("REPLACE INTO `skyg_res`.`res_playbill` (
											  `playbill_id`,
											  `type`,
											  `picture_url`,
											  `small_picture_url`,
											  `picture_size`,
											  `picture_name`,
											  `relation_id`,
											  `resmark_relation_id`,
											  `resmark`
											) 
											SELECT 
											  p.`playbill_id`,
											  p.`type`,
											  p.`picture_url`,
											  p.`small_picture_url`,
											  p.`picture_size`,
											  p.`picture_name`,
											  v.`v_id` AS `relation_id`,
											  p.`resmark_relation_id`,
											  p.`resmark`    
											FROM
											  `skyg_res`.`res_playbill` AS p 
											  JOIN `skyg_res`.`res_video` AS v 
											WHERE p.`resmark_relation_id` = v.`resmark` 
											  AND p.`relation_id` = 0  ")->exec();
	}
	
	/**
	 *
	 * @return number  修改成功返回值大于0反之等于0
	 */
	public static function modifymusictop(){
		
		 
		//$sql = "UPDATE
		//	       `skyg_res`.`res_music_top`
		//	     SET
		//	       `expired` = 1
		//	     WHERE `page_index` = 0";
		// 此处更改编码是为了满足客户端提出的用户收藏了的歌曲不在榜单了仍然能够播放。
		 
		return parent::createSQL("UPDATE `skyg_res`.`res_music_top` AS mtop
									SET
									  mtop.`category_id` =(CASE WHEN mtop.`page_index`=0 THEN 0 ELSE
									  (SELECT
									    rca.category_id
									  FROM
									    `skyg_res`.`res_category` AS rca
									  WHERE rca.category_name = mtop.resource
									  LIMIT 1) END)")->exec();
	}
	
	/**
	 * 根据video_url表中的统计插入video_site表中
	 * @return number  修改成功返回值大于0反之等于0
	 */
	public static function insertvidesitebyvideourl(){
		return parent::createSQL("UPDATE `skyg_res`.`res_video_site` AS rvs,(SELECT
								  `vs_id`,
								  COUNT(*) AS `total`
								FROM
								  `skyg_res`.`res_video_url`
								GROUP BY `vs_id`) AS rvu 
				                SET rvs.`segment`=rvu.`total`
				               WHERE rvs.`vs_id`=rvu.`vs_id`
				                 AND rvs.`segment`<>rvu.`total`")->exec();
	}
	
	/**
	 * 
	 * 获取资讯表中最大的RESMARK
	 */
	public static function getnewsmaxresmark(){
		return parent::createSQL("select max(`resmark`)
				                    from `skyg_res`.`res_news`")->toValue();
	}
	
	/**
	 * 
	 * 返回权重为0的VIDEO SITE数据COUNT值
	 */
	public static function getSiteWeightCount(){
		return parent::createSQL("select count(1) 
				                    from `skyg_res`.`res_video_site`
				                   where `sequence` = 0")->toValue();
	}
	
	/**
	 * 
	 * @param int $start
	 * @param int $pagesize
	 * @return multitype:   返回权重为0的site 列表
	 */
	public static function getSiteWeightList($start,$pagesize){
		return parent::createSQL("select rv.`category`,
				                         rvs.`vs_id`,
										 rvs.`v_id`,
										 rvs.`source`,
										 rvs.`width`,
										 rvs.`height`,
										 rvs.`run_time`,
										 rvs.`segment`,
										 rvs.`resmark`,
										 rvs.`current_segment`,
										 rvs.`expired`,
										 rvs.`playurl`,
										 rvs.`play_action`,
										 rvs.`price`,
										 rvs.`sequence`
				                    from `skyg_res`.`res_video_site` as rvs,
				                         `skyg_res`.`res_video` as rv
				                   where rvs.`v_id` = rv.`v_id`
				                     and rvs.`sequence`= 0 
				                   order by rvs.`vs_id`
				                   limit :start,:pagesize",
				                 array("start"=>(int)$start,
				                 	   "pagesize"=>(int)$pagesize
				                 		))->toList();
	}
	
	/**
	 * 
	 * @param int $vsid       影视源表主键ID
	 * @param int $vsequence  权重值
	 * @return number         更新成功返回大于0的数，反之返回0
	 */
	public static function updateSiteSequenceByVsid($vsid,$vsequence){
		return parent::createSQL("update `skyg_res`.`res_video_site`
				                     set `sequence`=:vsequence
				                   where `vs_id`=:vsid",
				                 array("vsid"=>(int)$vsid,
				                 	   "vsequence"=>(int)$vsequence	
				                 		))->exec();
		
	}
	
	/**
	 *
	 * @param string $v_schemaname   数据库名
	 * @param string $v_tablename    表名
	 * @param string $v_keys         拼接好后的字段名
	 * @param string $v_keyvalues    拼接好后的插入值
	 * @return number                插入或更新成功返回值大于0反之等于0
	 */
	public static function replaceData($v_schemaname,$v_tablename,$v_keys,$v_keyvalues){
	
		return parent::createSQL( "REPLACE INTO `".$v_schemaname."`.`".$v_tablename."`(".$v_keys.") VALUES(".$v_keyvalues.")")->exec();
		
	}
	
	/**
	 * 
	 * 
	 * @return number 
	 * 清空附加权重表，因为每次跑AUTORUN会重新导入最新的附加权重值
	 */
	public static function deleteExtraWeight(){
		return parent::createSQL("delete from  `skyg_res`.`res_extra_weight`")->exec();
			
		
	}
	
	/**
	 * 
	 * @return number
	 * 删除节目表中今天之前的数据
	 */
	public static function deleteProgamThereDaysAgo(){
		return parent::createSQL("DELETE FROM `skyg_res`.`res_program` WHERE `begintime`<DATE_FORMAT((NOW() - INTERVAL 1 DAY),'%Y-%m-%d 23:59:59')")->exec();
	}
}