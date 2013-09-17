<?php
namespace sns\models;
/**
 * @property  int          play_source_id  播放源id              
 * @property  int          publish_id      self_publish表主键ID  
 * @property  string       play_action     播放地址             
 * @property  int          play_type       播放分类：0为内部资源，1为奇艺，2为优鹏             
 * @property  string       created_date    创建时间                                                                                                                                                                 
 * 
 * @author xiaokeming
 */

class SnsPlaySourceModel extends \Sky\db\ActiveRecord{
	/**
	 *@return SnsPlaySourceModel
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}

	protected static $tableName="skyg_sns.sns_play_source";
	protected static $primeKey=array("play_source_id");
	
	
	/**
	 * 
	 * @param int    $publishid      发布主表ID 
	 * @param string $playaction     播放地址
	 * @param int    $playtype       播放分类
	 * @return number                插入成功返回大于0的数，失败为0
	 */
	public static function insertPlaySource($publishid,$playaction,$playtype){
		return parent::createSQL("insert into `skyg_sns`.`sns_play_source`(
				                              `publish_id`,
				                              `play_action`,
				                              `play_type`)
				                       values(:publishid,
				                              :playaction,
				                              :playtype)",
				                        array("publishid"=>(int)$publishid,
				                        	  "playaction"=>$playaction,
				                        	  "playtype"=>(int)$playtype
				                        		))->exec();
		
	}
	
	/**
	 * 
	 * @param int $publishid 发布主表ID 
	 * @return multitype:
	 */
	public static function getPlaySourceByPublishId($publishid,$playtype){
		return parent::createSQL("select `publish_id`,
										 `play_action`,
										 `play_type`
								    from `skyg_sns`.`sns_play_source`
								   where `publish_id`=:publishid
				                     and `play_type`=:playtype",
				             array( "publishid"=>(int)$publishid,
				             		"playtype"=>(int)$playtype
				             		)
										)->toList();
	}
	
	/**
	 * 
	 * @param array $condition    publishid和action组成的数组
	 * @param int   $playtype     播放分类
	 * @return number             插入成功返回大于0数，失败返回0;
	 */
	public static function insertPlaySourceByArray($condition){
		
		if($condition){
			return parent::createSQL("replace into `skyg_sns`.`sns_play_source`(
					                              `publish_id`,
					                              `play_action`,
					                              `play_type`) values".$condition)->exec();
		}else{
			return 0;
		}
	}
}
