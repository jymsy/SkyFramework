<?php
namespace sns\models;
/**
 * @property  int          publish_id    自增id                                                                                                                                             
 * @property  int          source_id     来源ID                                                                                                                                             
 * @property  int          source_type   枚举值：为1时source_id为videoid,为2时source_id为musicid,为3时source_id为新闻id,为4时source_id为broadcastid,为5时source_id为空  
 * @property  int          user_id       发布人ID                                                                                                                                          
 * @property  string       url           用户自主上传的文件或图片相对保存路径                                                                                               
 * @property  string       title         标题                                                                                                                                               
 * @property  string       content       详细内容                                                                                                                                         
 * @property  string       logo_b        官方大LOGO                                                                                                                                        
 * @property  string       logo_s        官方小LOGO                                                                                                                                        
 * @property  int          publish_flag  枚举值：1:为失效，0:为有效，空:为未审核                                                                                             
 * @property  string       created_date  发布时间                                                                                                                                                       
 * 
 * @author xiaokeming
 */

class SnsSelfPublishModel extends \Sky\db\ActiveRecord{
	/**
	 *@return SnsSelfPublishModel
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}

	protected static $tableName="skyg_sns.sns_self_publish";
	protected static $primeKey=array("publish_id");
	
	/**
	/**
	 * 
	 * @param int $userid       用户ID
	 * @param int $vid          影视ID
	 * @return number           插入成功返回生成记录的ID,不成功返回0
	 */
	
	public static function insertByVideo($vid,$userid,$stype,$ptype){
	 $par=parent::createSQL("INSERT INTO skyg_sns.`sns_self_publish`(
	 		                          `source_id`,
	 		                          `source_type`,
	 		                          `user_id`,
	 		                          `title`,
	 		                          `content`,
	 		                          `logo_s`,
	 		                          `publish_flag`,
	 		                          `publish_type`)
							   select rv.`v_id`,
									  '".addslashes($stype)."' AS source_type,
									  ".addslashes($userid)." AS user_id,
									  rv.`title`,
									  rv.`description`,
									  rv.`thumb`,
									  0 AS publish_flag,
	 		                          ".addslashes($ptype)."  
									FROM
									  skyg_res.`res_video` AS rv
									WHERE rv.`v_id`=:vid",
	 		                     array( "vid"=>(int)$vid
	 		                     		)); 
	 if($par->exec()){
	 	$par->getPdoInstance();
	 	return $par->lastInsertID();
	 }else{
	 	return 0;
	 }
	}
	
	
	/**
	 *
	 * @param int $userid       用户ID
	 * @param int $vid          影视ID
	 * @return number           插入成功返回生成记录的ID,不成功返回0
	 */
	
	 public static function insertByPlaying($vid,$userid,$stype,$ptype){
		$par=parent::createSQL("INSERT INTO skyg_sns.`sns_self_publish`(
	 		                          `source_id`,
	 		                          `source_type`,
	 		                          `user_id`,
	 		                          `title`,
	 		                          `content`,
	 		                          `logo_s`,
	 		                          `publish_flag`,
				                      `publish_type`)
							   SELECT rv.`v_id`,
									  '".addslashes($stype)."' AS source_type,
									  ".addslashes($userid)." AS user_id,
									  rv.`title`,
									  rv.`description`,
									  rv.`thumb`,
									  0 AS publish_flag,
				                      ".addslashes($ptype)." 
									FROM
									  skyg_res.`res_video` AS rv
									WHERE rv.`v_id`=:vid",
				array( "vid"=>(int)$vid
				));
		if($par->exec()){
			$par->getPdoInstance();
			return $par->lastInsertID();
		}else{
			return 0;
		}
	}
	
	/**
	 * 
	 * @param int $mid          musci表主键ID
	 * @param int $userid       用户ID
	 * @return number           插入成功返回生成记录的ID,不成功返回0
	 */
	
	public static function insertPublishByMusic($vmid,$userid,$stype,$ptype){
		$par=parent::createSQL("INSERT INTO skyg_sns.`sns_self_publish`(
				                       `source_id`,
	 		                           `source_type`,
	 		                           `user_id`,
	 		                           `title`,
	 		                           `logo_s`,
	 		                           `publish_flag`,
				                       `publish_type`)
							    SELECT raso.`audio_song_id`,
									   '".addslashes($stype)."' AS source_type,
									   ".addslashes($userid)." AS user_id,
									   raso.`title` AS title,
									   raa.`thumb` AS logo_s,
									   0 AS publish_flag,
				                       ".addslashes($ptype)."  
								  FROM
									   skyg_res.`res_audio_song` AS raso 
								  LEFT JOIN skyg_res.`res_audio_album` AS raa 
									    ON raso.`album_id` = raa.`album_id` 
								 WHERE raso.`audio_song_id`=:vmid",
				             array( "vmid"=>(int)$vmid
				             		)); 
		if($par->exec()){
			$par->getPdoInstance();
			return $par->lastInsertID();
		}else{
			return 0;
		}
	}
	
	
	/**
	 * 
	 * @param int $mtid         musictop主键ID
	 * @param int $userid       用户ID 
	 * @return number           插入成功返回生成记录的ID,不成功返回0
	 */
	
	public static function insertPublishByMusicTop($mtid,$userid,$stype,$ptype){
		$par=parent::createSQL("INSERT INTO skyg_sns.`sns_self_publish`(
				                      `source_id`,
	 		                          `source_type`,
	 		                          `user_id`,
	 		                          `title`,
	 		                          `logo_s`,
	 		                          `publish_flag`,
				                      `publish_type`)
							   SELECT rmt.`music_top_id`,
									  '".addslashes($stype)."' AS source_type,
									  ".addslashes($userid)." AS user_id,
									  rmt.`title`,
									  rasi.`pic` AS logo_s,
									  0 AS publish_flag,
				                      ".addslashes($ptype)."  
									FROM
									  skyg_res.`res_music_top` AS rmt 
									  LEFT JOIN skyg_res.`res_audio_singer` AS rasi 
									    ON rmt.`singer` = rasi.name 
									WHERE rmt.`music_top_id`=:mtid",
				               array( "mtid"=>(int)$mtid
				               		  )); 
		if($par->exec()){
			$par->getPdoInstance();
			return $par->lastInsertID();
		}else{
			return 0;
		}
	}
	
	/**
	 * 
	 * @param int $nid            新闻ID
	 * @param int $userid         用户ID
	 * @return number             插入成功返回生成记录的ID,不成功返回0
	 * 
	 */
	
	public static function insertPublishByNews($nid,$userid,$stype,$ptype){
	   $par=parent::createSQL("INSERT INTO skyg_sns.`sns_self_publish`(
	   		                          `source_id`,
	 		                          `source_type`,
	 		                          `user_id`,
	 		                          `title`,
	 		                          `content`,
	 		                          `logo_s`,
	 		                          `publish_flag`,
	   		                          `publish_type`)
							   SELECT rn.`news_id`,
									  '".addslashes($stype)."' AS source_type,
									  ".addslashes($userid)." AS user_id,
									  rn.`title` AS title,
									  rn.`brief` AS content,
									  rn.`logo` AS logo_s,
									  0 AS publish_flag,
	   		                          ".addslashes($ptype)." 
									FROM
									  skyg_res.`res_news` AS rn
									WHERE rn.`news_id`=:nid",
	   		                    array( "nid"=>$nid));
	   if($par->exec()){
	   	$par->getPdoInstance();
	   	return $par->lastInsertID();
	   }else{
	   	return 0;
	   }
	                           
	}
	
	/**
	 * 
	 * @param int $userid       用户ID
	 * @param string $vurl      用户上传的文件或图片的路径
	 * @param string $vtitle    标题
	 * @param string $vcontent  内容
	 * @return number           插入成功返回生成记录的ID,不成功返回0
	 */
	public static function insertPublishByUser($sid,$stype,$uid,$vurl,$vtitle,$vcontent,$vlogb,$vlogs,$pflag,$ptype){
		$par=parent::createSQL("INSERT INTO skyg_sns.`sns_self_publish`(
	 		                          `source_id`,
				                      `source_type`,
	 		                          `user_id`,
				                      `url`,
	 		                          `title`,
	 		                          `content`,
				                      `logo_b`,
				                      `logo_s`,
	 		                          `publish_flag`,
				                      `publish_type`)
				               values( ".addslashes($sid).",
				                       '".addslashes($stype)."',
									   '".addslashes($uid)."',
					                   '".addslashes($vurl)."',
									   '".addslashes($vtitle)."',
									   '".addslashes($vcontent)."',
				                       '".addslashes($vlogb)."',
				                       '".addslashes($vlogs)."',
									   '".addslashes($pflag)."',
				                       '".addslashes($ptype)."')");
			
		if($par->exec()){
			$par->getPdoInstance();
			return $par->lastInsertID();
		}else{
			return 0;
		}
	
	}
	
	/**
	 * 用于管理网站编职分享状态
	 * @param int $userid  用户ID
	 */
	public static function showPublishByUid($userid,$ptype){
		return parent::createSQL("select ssp.`publish_id`,
										  ssp.`source_id`,
										  ssp.`source_type`,
										  ssp.`user_id`,
										  ssp.`url`,
										  ssp.`title`,
										  ssp.`content`,
										  ssp.`logo_b`,
										  ssp.`logo_s`,
										  ssp.`publish_flag`,
										  ssp.`created_date`
					                 from `skyg_sns`.`sns_self_publish` AS ssp
					                where ssp.`user_id`=:userid
				                      and ssp.`publish_type`=:ptype
					                  and ssp.`publish_flag`=0",
				              array( "userid"=>(int)$userid,
				              		 "ptype"=>(int)$ptype
				              		 )

				)->toList();
	}
	
	/**
	 * 
	 * @param int $sid                    sourceid
	 * @param int $stype                  sourcetype
	 * @return Ambigous <NULL, unknown>
	 */
	public static function showPublishBySid($sid,$stype,$ptype){
		return parent::createSQL("select ssp.`publish_id`,
										  ssp.`source_id`,
										  ssp.`source_type`,
										  ssp.`user_id`,
										  ssp.`url`,
										  ssp.`title`,
										  ssp.`content`,
										  ssp.`logo_b`,
										  ssp.`logo_s`,
										  ssp.`publish_flag`,
										  ssp.`created_date`,
				                          ss.`share_count` AS sharecount,
				                          scl.`collect_count` AS collectcount,
				                          scm.`comment_count` AS commentcount,
				                          sp.`praise_count` AS praisecount,
				                          sp.`step_count` AS stepcount
					                 from `skyg_sns`.`sns_self_publish` AS ssp
				                     left join `skyg_sns`.`sns_collect` AS scl
				                            on ssp.`publish_id`=scl.`publish_id`
				                     left join `skyg_sns`.`sns_share` AS ss
				                            on ssp.`publish_id`=ss.`publish_id`
				                     left join `skyg_sns`.`sns_comment` AS scm
				                            on ssp.`publish_id`=scm.`publish_id`
				                     left join `skyg_sns`.`sns_praise` AS sp
				                            on ssp.`publish_id`=sp.`publish_id`
					                where ssp.`source_id`=:sid
				                      and ssp.`source_type`=:stype
				                      and ssp.`publish_type`=:ptype
				                      and ssp.`publish_flag`=0",
				array(  "sid"=>(int)$sid,
						"ptype"=>(int)$ptype,
						"stype"=>$stype
				))->toList();
		
	}
	
	/**
	 * 
	 * @param int    $sid          资源ID
	 * @param string $stype        资源分类
	 * @return multitype:
	 */
	public static function getPublishCountBySid($sid,$stype){
		return parent::createSQL("select ssp.`publish_id`,
										  ssp.`source_id`,
										  ssp.`source_type`,
										  ssp.`user_id`,
										  ssp.`url`,
										  ssp.`title`,
										  ssp.`content`,
										  ssp.`logo_b`,
										  ssp.`logo_s`,
										  ssp.`publish_flag`,
										  ssp.`created_date`
				                    from `skyg_sns`.`sns_self_publish` AS ssp
				                   where ssp.`source_id`=:sid
				                     and ssp.`source_type`=:stype",
				                  array("sid"=>(int)$sid,
				                  		"stype"=>$stype
				                  		 ))->toList();
		
										  
	}
	
	/**
	 * 
	 * @param int $pid    publish表主键ID
	 * @return number     修改成功返回值大于0，否则返回0
	 */
	public static function updatePublish($pid,$ptype){
		return parent::createSQL("update `skyg_sns`.`sns_self_publish` AS ssp
				                     set ssp.`publish_type`=:ptype
				                   where ssp.`publish_id`=:pid
				                     and ssp.`publish_flag`=0",
				                array( "pid"=>(int)$pid,
				                	   "ptype"=>(int)$ptype
				                		))->exec();
	}
	
	/**
	 * 按最热分享查询LIST
	 * @param unknown_type $page
	 * @param unknown_type $pagesize
	 * @return multitype:
	 */
	public static function listPublishByHotShare($star,$pagesize,$ptype,$playtype){

		return parent::createSQL("select ssp.`publish_id`,
										  ssp.`source_id`,
										  ssp.`source_type`,
										  ssp.`user_id`,
										  ssp.`url`,
										  ssp.`title`,
										  ssp.`content`,
										  ssp.`logo_b`,
										  ssp.`logo_s`,
										  ssp.`publish_flag`,
										  ssp.`created_date`,
				                          sps.`play_action`,
				                          ss.`share_count` AS sharecount,
				                          scl.`collect_count` AS collectcount,
				                          scm.`comment_count` AS commentcount,
				                          sp.`praise_count` AS praisecount,
				                          sp.`step_count` AS stepcount
					                 from `skyg_sns`.`sns_share` AS ss,
				                      `skyg_sns`.`sns_self_publish` AS ssp 
				                     left join `skyg_sns`.`sns_collect` AS scl 
				                            on ssp.`publish_id`=scl.`publish_id`
				                     left join `skyg_sns`.`sns_comment` AS scm 
				                            on ssp.`publish_id`=scm.`publish_id`
				                     left join `skyg_sns`.`sns_praise` AS sp 
				                            on ssp.`publish_id`=sp.`publish_id`
				                     left join `skyg_sns`.`sns_play_source` as sps
				                            on sps.`publish_id`=ssp.`publish_id`
				                           and (ssp.`source_id` > 0 or sps.`play_type`=:playtype or sps.`play_type`=0)
					                where ss.`publish_id`=ssp.`publish_id`
				                      and ssp.`publish_type`=:ptype
				                      and ssp.`publish_flag`=0 
				                    order by ss.`share_count` desc
				                     limit :star,:pagesize",
				                 array( "star"=>(int)$star,
				                 		"pagesize"=>(int)$pagesize,
				                 		"ptype"=>(int)$ptype,
				                 		"playtype"=>(int)$playtype
				                 		))->toList();
	}
	
	/**
	 * 按类别查询LIST
	 * @param int $vtype               类别
	 * @param unknown_type $page
	 * @param unknown_type $pagesize
	 * @return multitype:
	 */
	public static function listPublishByType($star,$pagesize,$stype,$ptype){

		return parent::createSQL("select ssp.`publish_id`,
										  ssp.`source_id`,
										  ssp.`source_type`,
										  ssp.`user_id`,
										  ssp.`url`,
										  ssp.`title`,
										  ssp.`content`,
										  ssp.`logo_b`,
										  ssp.`logo_s`,
										  ssp.`publish_flag`,
										  ssp.`created_date`,
				                          ss.`share_count` AS sharecount,
				                          scl.`collect_count` AS collectcount,
				                          scm.`comment_count` AS commentcount,
				                          sp.`praise_count` AS praisecount,
				                          sp.`step_count` AS stepcount
					                 from `skyg_sns`.`sns_self_publish` AS ssp
				                     left join `skyg_sns`.`sns_share` AS ss
				                            on ssp.`publish_id`=ss.`publish_id`
				                     left join `skyg_sns`.`sns_collect` AS scl
				                            on ssp.`publish_id`=scl.`publish_id`
				                     left join `skyg_sns`.`sns_comment` AS scm
				                            on ssp.`publish_id`=scm.`publish_id`
				                     left join `skyg_sns`.`sns_praise` AS sp
				                            on ssp.`publish_id`=sp.`publish_id`
					                where ssp.`source_type`=:stype
				                      and ssp.`publish_type`=:ptype
				                      and ssp.`publish_flag`=0
				                    order by ss.`share_count` desc
				                     limit :star,:pagesize",
				array(  "stype"=>$stype,
						"ptype"=>(int)$ptype,
						"star"=>(int)$star,
						"pagesize"=>(int)$pagesize
				))->toList();
	}
	
	/**
	 * 按用户ID查询用户分享的LIST
	 * @param int $vtype               分类
	 * @param unknown_type $page
	 * @param unknown_type $pagesize
	 * @return multitype:
	 */
	public static function listPublishByShareUid($userid,$star,$pagesize,$ptype){
	
		return parent::createSQL("select ssp.`publish_id`,
										  ssp.`source_id`,
										  ssp.`source_type`,
										  ssp.`user_id`,
										  ssp.`url`,
										  ssp.`title`,
										  ssp.`content`,
										  ssp.`logo_b`,
										  ssp.`logo_s`,
										  ssp.`publish_flag`,
										  ssp.`created_date`,
				                          ss.`share_count` AS sharecount,
				                          scl.`collect_count` AS collectcount,
				                          scm.`comment_count` AS commentcount,
				                          sp.`praise_count` AS praisecount,
				                          sp.`step_count` AS stepcount
					                 from `skyg_sns`.`sns_self_publish` AS ssp,
				                          `skyg_sns`.`sns_share` AS ss
				                     left join `skyg_sns`.`sns_collect` AS scl
				                            on ss.`publish_id`=scl.`publish_id`
				                     left join `skyg_sns`.`sns_comment` AS scm
				                            on ss.`publish_id`=scm.`publish_id`
				                     left join `skyg_sns`.`sns_praise` AS sp
				                            on ss.`publish_id`=sp.`publish_id`
					                where ssp.`publish_id`=ss.`publish_id`
				                      and ss.`share_id` in (
				                      select `share_id` from `skyg_sns`.`sns_share_detail`
				                       where `from_user_id`=:userid and share_flag = 0)
				                      and ssp.`publish_type`=:ptype
				                      and ssp.`publish_flag`=0
				                    order by ssp.`created_date` desc
				                     limit :star,:pagesize",
				array(  "userid"=>(int)$userid,
						"ptype"=>(int)$ptype,
						"star"=>(int)$star,
						"pagesize"=>(int)$pagesize
				))->toList();
	}
	
	/**
	 * 按用户ID查询用户评论的LIST
	 * @param int $userid     用户ID
	 * @param int $star       起始行
	 * @param int $pagesize   行数
	 * @param int $ptype      是云平台里的还是正在热播的标识
	 * @return multitype:     
	 */
	public static function listPublishByCommentUid($userid,$star,$pagesize,$ptype){
	
		return parent::createSQL("select ssp.`publish_id`,
										  ssp.`source_id`,
										  ssp.`source_type`,
										  ssp.`user_id`,
										  ssp.`url`,
										  ssp.`title`,
										  ssp.`content`,
										  ssp.`logo_b`,
										  ssp.`logo_s`,
										  ssp.`publish_flag`,
										  ssp.`created_date`,
				                          ss.`share_count` AS sharecount,
				                          scl.`collect_count` AS collectcount,
				                          scm.`comment_count` AS commentcount,
				                          sp.`praise_count` AS praisecount,
				                          sp.`step_count` AS stepcount
					                 from `skyg_sns`.`sns_self_publish` AS ssp,
				                          `skyg_sns`.`sns_comment` AS scm
				                     left join `skyg_sns`.`sns_collect` AS scl
				                            on scm.`publish_id`=scl.`publish_id`
				                     left join `skyg_sns`.`sns_share` AS ss
				                            on scm.`publish_id`=ss.`publish_id`
				                     left join `skyg_sns`.`sns_praise` AS sp
				                            on scm.`publish_id`=sp.`publish_id`
					                where ssp.`publish_id`=scm.`publish_id`
				                      and scm.`comment_id` in (
				                      select `comment_id` from `skyg_sns`.`sns_comment_detail`
				                       where `user_id`=:userid)
				                      and ssp.`publish_type`=:ptype
				                      and ssp.`publish_flag`=0
				                    order by ssp.`created_date` desc
				                     limit :star,:pagesize",
				array(  "userid"=>(int)$userid,
						"ptype"=>(int)$ptype,
						"star"=>(int)$star,
						"pagesize"=>(int)$pagesize
				))->toList();
	}
	
	
	/**
	 * 按用户ID查询用户顶踩的LIST
	 * @param int $userid     用户ID
	 * @param int $star       起始行
	 * @param int $pagesize   行数
	 * @param int $ptype      是云平台里的还是正在热播的标识
	 * @return multitype:
	 */
	public static function listPublishByPraiseUid($userid,$star,$pagesize,$ptype){
	
		return parent::createSQL("select ssp.`publish_id`,
										  ssp.`source_id`,
										  ssp.`source_type`,
										  ssp.`user_id`,
										  ssp.`url`,
										  ssp.`title`,
										  ssp.`content`,
										  ssp.`logo_b`,
										  ssp.`logo_s`,
										  ssp.`publish_flag`,
										  ssp.`created_date`,
				                          ss.`share_count` AS sharecount,
				                          scl.`collect_count` AS collectcount,
				                          scm.`comment_count` AS commentcount,
				                          sp.`praise_count` AS praisecount,
				                          sp.`step_count` AS stepcount
					                 from `skyg_sns`.`sns_self_publish` AS ssp,
				                          `skyg_sns`.`sns_praise` AS sp
				                     left join `skyg_sns`.`sns_collect` AS scl
				                            on sp.`publish_id`=scl.`publish_id`
				                     left join `skyg_sns`.`sns_comment` AS scm
				                            on sp.`publish_id`=scm.`publish_id`
				                     left join `skyg_sns`.`sns_share` AS ss
				                            on sp.`publish_id`=ss.`publish_id`
					                where ssp.`publish_id`=sp.`publish_id`
				                      and sp.`praise_id` in (
				                      select `praise_id` from `skyg_sns`.`sns_praise_detail`
				                       where `user_id`=:userid)
				                      and ssp.`publish_type`=:ptype
				                      and ssp.`publish_flag`=0
				                    order by ssp.`created_date` desc
				                     limit :star,:pagesize",
				array(  "userid"=>(int)$userid,
						"ptype"=>(int)$ptype,
						"star"=>(int)$star,
						"pagesize"=>(int)$pagesize
				))->toList();
	}
	
	/**
	 * 按用户ID查询用户收藏的LIST
	 * @param int $vtype               分类
	 * @param unknown_type $page
	 * @param unknown_type $pagesize
	 * @return multitype:
	 */
	public static function listPublishByCollectUid($userid,$star,$pagesize,$ptype){
	
		return parent::createSQL("select ssp.`publish_id`,
										  ssp.`source_id`,
										  ssp.`source_type`,
										  ssp.`user_id`,
										  ssp.`url`,
										  ssp.`title`,
										  ssp.`content`,
										  ssp.`logo_b`,
										  ssp.`logo_s`,
										  ssp.`publish_flag`,
										  ssp.`created_date`,
				                          ss.`share_count` AS sharecount,
				                          scl.`collect_count` AS collectcount,
				                          scm.`comment_count` AS commentcount,
				                          sp.`praise_count` AS praisecount,
				                          sp.`step_count` AS stepcount
					                 from `skyg_sns`.`sns_self_publish` AS ssp,
				                          `skyg_sns`.`sns_collect` AS scl
				                     left join `skyg_sns`.`sns_share` AS ss
				                            on scl.`publish_id`=ss.`publish_id`
				                     left join `skyg_sns`.`sns_comment` AS scm
				                            on scl.`publish_id`=scm.`publish_id`
				                     left join `skyg_sns`.`sns_praise` AS sp
				                            on scl.`publish_id`=sp.`publish_id`
					                where ssp.`publish_id`=scl.`publish_id`
				                      and scl.`collect_id` in (select `collect_id`
				                      from `skyg_sns`.`sns_collect_detail`
				                      where `user_id`=:userid and collect_flag = 0 )				                    
				                     -- and ssp.`publish_type`=:ptype
				                      and ssp.`publish_flag`=0
				                    order by ssp.`created_date` desc
				                     limit :star,:pagesize",
				array(  "userid"=>(int)$userid,
						"ptype"=>(int)$ptype,
						"star"=>(int)$star,
						"pagesize"=>(int)$pagesize
				))->toList();
	}
	
	
	/**
	 * 
	 * 按分享时间倒序排列LIST，用于广场页面
	 * @param unknown_type $page
	 * @param unknown_type $pagesize
	 * @return multitype:
	 */
	public static function listPublishByDate($star,$pagesize,$ptype,$playtype){

		return parent::createSQL("select ssp.`publish_id`,
										  ssp.`source_id`,
										  ssp.`source_type`,
										  ssp.`user_id`,
										  ssp.`url`,
										  ssp.`title`,
										  ssp.`content`,
										  ssp.`logo_b`,
										  ssp.`logo_s`,
										  ssp.`publish_flag`,
										  ssp.`created_date`,
				                          sps.`play_action`,
				                          ss.`share_count` AS sharecount,
				                          scl.`collect_count` AS collectcount,
				                          scm.`comment_count` AS commentcount,
				                          sp.`praise_count` AS praisecount,
				                          sp.`step_count` AS stepcount
					                 from `skyg_sns`.`sns_self_publish` AS ssp
				                     left join `skyg_sns`.`sns_share` AS ss
				                            on ssp.`publish_id`=ss.`publish_id`
				                     left join `skyg_sns`.`sns_collect` AS scl
				                            on ssp.`publish_id`=scl.`publish_id`
				                     left join `skyg_sns`.`sns_comment` AS scm
				                            on ssp.`publish_id`=scm.`publish_id`
				                     left join `skyg_sns`.`sns_praise` AS sp
				                            on ssp.`publish_id`=sp.`publish_id`
				                     left join `skyg_sns`.`sns_play_source` as sps
				                            on sps.`publish_id`=ssp.`publish_id`
				                           and (ssp.`source_id` > 0 or sps.`play_type`=:playtype or sps.`play_type`=0)
				                     where ssp.`publish_type`=:ptype
				                       and ssp.`publish_flag`=0
				                    order by ss.`last_update_date` desc
				                     limit :star,:pagesize",
				array(  "ptype"=>(int)$ptype,  
						"star"=>(int)$star,
						"pagesize"=>(int)$pagesize,
						"playtype"=>(int)$playtype
				))->toList();
	}
	
	/**
	 * 
	 * @param string $v_name 拼接好的影视名字符串
	 * @return number        返回VID列表
	 */
	public static function getListVideoIdByName($v_name){
		return parent::createSQL(' CALL `skyg_sns`.`proc_sns_queryvid_byname`("'.$v_name.'")')->toList();
	}
	
	/**
	 * 
	 * @param string $stype    资源分类
	 * @param int    $ptype    广场/正在热播标识
	 * @param string $vid      拼接好的vid字符串
	 * @return number          插入成功返回1，失败返回0
	 */
	public static function InsertPublishByVideoId($stype,$ptype,$vid){
		return parent::createSQL(' CALL `skyg_sns`.`proc_sns_insertpublish_byvid`("'.$stype.'","'.$ptype.'","'.$vid.'")')->exec();
	}
	
	public static function getPublishListByVid($star,$pagesize,$stype,$ptype,$sid){
		if($ptype!=''){
			$sql=" and ssp.`publish_type`=".(int)$ptype;
		}else{
		    $sql="";
		}
		
		return parent::createSQL("select ssp.`publish_id`,
										  ssp.`source_id`,
										  ssp.`source_type`,
										  ssp.`user_id`,
										  ssp.`url`,
										  ssp.`title`,
										  ssp.`content`,
										  ssp.`logo_b`,
										  ssp.`logo_s`,
										  ssp.`publish_flag`,
										  ssp.`created_date`,
				                          ss.`share_count` AS sharecount,
				                          scl.`collect_count` AS collectcount,
				                          scm.`comment_count` AS commentcount,
				                          sp.`praise_count` AS praisecount,
				                          sp.`step_count` AS stepcount
					                 from `skyg_sns`.`sns_self_publish` AS ssp
				                     left join `skyg_sns`.`sns_share` AS ss
				                            on ssp.`publish_id`=ss.`publish_id`
				                     left join `skyg_sns`.`sns_collect` AS scl
				                            on ssp.`publish_id`=scl.`publish_id`
				                     left join `skyg_sns`.`sns_comment` AS scm
				                            on ssp.`publish_id`=scm.`publish_id`
				                     left join `skyg_sns`.`sns_praise` AS sp
				                            on ssp.`publish_id`=sp.`publish_id`
				                     where ssp.`source_id` in (".$sid.")
				                       and ssp.`source_type`=:stype
				                       ".$sql."
				                       and ssp.`publish_flag`=0
				                    order by ssp.`created_date` desc
				                     limit :star,:pagesize",
				array(    
						"star"=>(int)$star,
						"stype"=>$stype,
						"pagesize"=>(int)$pagesize
				))->toList();
	}
	
	/**
	 * 
	 * @param string $playaction 第三方播放地址
	 * @param int    $playtype   分类
	 * @return multitype: 
	 */
    public static function getPublishCountByUrl($v_url,$v_stype){
		return parent::createSQL("select ssp.`publish_id`,
										  ssp.`source_id`,
										  ssp.`source_type`,
										  ssp.`user_id`,
										  ssp.`url`,
										  ssp.`title`,
										  ssp.`content`,
										  ssp.`logo_b`,
										  ssp.`logo_s`,
										  ssp.`publish_flag`,
										  ssp.`created_date`
				                    from `skyg_sns`.`sns_self_publish` AS ssp
				                   where ssp.`source_type` =:v_stype
				                     and ssp.`url`=:v_url",
				                  array("v_url"=>$v_url,
				                  		"v_stype"=>$v_stype
				                  		 ))->toList();
		
										  
	}
}