<?php

namespace res\models;

/**table res_advert
 * @property  int          ad_id                         
 * @property  string       name                          
 * @property  int          type          1-pic,2-html    
 * @property  string       url                           
 * @property  int          flag          1-using,0-stop  
 * @property  string       created_date                           
 * @author Zhengyun
 */
class ResourceQueryForSnsModel extends \Sky\db\ActiveRecord{
	/**
	 *@return ResourceQueryForSnsModel
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}
	
	/**
	 * 
	 * @param int $vid    Video表主键ID
	 * @return multitype: 返回PUBLISH表需要的影视信息
	 */
	public static function GetVideoInfoForPublish($vid){
		return parent::createSQL("SELECT rv.`v_id`,
									  rv.`title`,
									  rv.`description`,
									  rv.`thumb` 
									FROM
									  `skyg_res`.`res_video` AS rv
									WHERE rv.`v_id`=:vid",
	 		                     array( "vid"=>(int)$vid
	 		                     		))->toList();
	 		                     		
	}
	
	/**
	 * 
	 * @param int $vmid    音乐主表ID
	 * @return multitype:  返回PUBLISH表需要的音乐信息
	 */
	public static function GetMusicInfoForPublish($vmid){
		return parent::createSQL("SELECT raso.`audio_song_id`,
									     raso.`title` AS title,
									     raa.`thumb` AS logo_s 
								    FROM
									     `skyg_res`.`res_audio_song` AS raso 
								    LEFT JOIN skyg_res.`res_audio_album` AS raa 
									  ON raso.`album_id` = raa.`album_id` 
								   WHERE raso.`audio_song_id`=:vmid",
				             array( "vmid"=>(int)$vmid
				             		))->toList(); 
	}
	
	/**
	 * 
	 * @param int $mtid    音乐榜单表主键ID
	 * @return multitype:  返回PUBLISH表需要的音乐信息
	 */
	public static function GetMusicTopInfoForPublish($mtid){
		return parent::createSQL("SELECT rmt.`music_top_id`,
									     rmt.`title`,
									     rasi.`pic` AS logo_s
									FROM
									     skyg_res.`res_music_top` AS rmt 
									LEFT JOIN skyg_res.`res_audio_singer` AS rasi 
									  ON rmt.`singer` = rasi.name 
								   WHERE rmt.`music_top_id`=:mtid",
				               array( "mtid"=>(int)$mtid
				               		  ))->toList(); 
	}
	
	/**
	 * 
	 * @param int $nid    资讯表主键ID
	 * @return multitype: 返回PUBLISH表需要的资讯信息
	 */
	public static function GetNewsForPublish($nid){
		return parent::createSQL("SELECT rn.`news_id`,
									     rn.`title` AS title,
									     rn.`brief` AS content,
									     rn.`logo` AS logo_s
									FROM
									     skyg_res.`res_news` AS rn
								   WHERE rn.`news_id`=:nid",
	   		                    array( "nid"=>$nid))->toList(); 
	}
}