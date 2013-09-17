<?php
namespace sns\models;
/**
 * @property  int          source_id         来源ID            
 * @property  string       source_type       来源分类        
 * @property  string       source_name       标题名称        
 * @property  int          played_count      已经播放数     
 * @property  int          playing_count     正在播放数     
 * @property  string       last_update_date  最后更新时间                   
 * 
 * @author xiaokeming
 */

class SnsOnlineTotalModel extends \Sky\db\ActiveRecord{
	/**
	 *@return SnsOnlineTotalModel
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}


	
	/**
	 * 
	 * @param int    $sid           来源ID
	 * @param string $stype         来源分类
	 * @param string $sname         来源标题
	 * @param int    $playedcount   已播放数
	 * @param int    $playingcount  正在播放数
	 * @return number
	 */
	public static function insertOnlineTotal($sid,$stype,$sname,$playedcount,$playingcount){
		return parent::createSQL("REPLACE INTO `skyg_sns`.`sns_online_total` (
												  `source_id`,
												  `source_type`,
												  `source_name`,
												  `played_count`,
												  `playing_count`
												) 
												VALUES
												  ( :sid,
												    :stype,
												    :sname,
												    :playedcount,
												    :playingcount
												  )",
				                             array( "sid"=>(int)$sid,
				                             	    "stype"=>$stype,
				                             		"sname"=>$sname,
				                             		"playedcount"=>(int)$playedcount,
				                             		"playingcount"=>(int)$playingcount
				                             		))->exec();
	}
	
	/**
	 * 
	 * @param int    $sid       来源ID
	 * @param string $stype     来源分类
	 * @return multitype:       返回正在播放数和已播放数
	 */
	public static function getOnlineTotalList($sid,$stype){
		return parent::createSQL("select sot.`source_id`,
				                         sot.`played_count`,
				                         sot.`playing_count`
				                    from `skyg_sns`.`sns_online_total` AS sot
				                   where sot.`source_id`=:sid
				                     and sot.`source_type`=:stype",
				                 array( "sid"=>(int)$sid,
				                 		"stype"=>$stype
				                 		))->toList();
	}
}