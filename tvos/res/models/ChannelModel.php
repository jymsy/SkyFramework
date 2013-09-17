<?php

namespace res\models;

/**table res_channel
 * @property  int          ch_id                         
 * @property  int          category_id                   
 * @property  string       ch_name                       
 * @property  string       ch_url                        
 * @property  string       ch_img                        
 * @property  string       created_date  
 *                                 
 * @author Zhengyun
 */
class ChannelModel extends \Sky\db\ActiveRecord{
	/**
	 *@return ChannelModel
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}

	protected static $tableName="skyg_res.res_channel";
	protected static $primeKey=array("ch_id");
	
	/**
	 * 
	 * @param String $ecids  需要展示的channel_id，以逗号分开
	 * @return array
	 */
	public static function showepgchannel($channelIds,$sysCondition='') {	
		if($sysCondition!='')
			$sysCondition=" AND $sysCondition ";
		$sql=sprintf(
				  "SELECT 
					  rch.`channel_id`,
					  rch.`category_id`,
					  rch.`channel_img`,
					  rch.`channel_name`,
					  rch.`channel_url`,
					  rch.`created_date`,
					  IFNULL(rpg.program_id, 0) as program_id,
					  IFNULL(rpg.program_name, '') as program_name,
					  IFNULL(rpg.begintime, '') as  begintime
					FROM
					   `skyg_res`.`res_channel` AS rch
					  LEFT JOIN  `skyg_res`.`res_program` AS rpg
					    ON rch.`channel_id` = rpg.`channel_id` 
					WHERE  rch.`channel_id` IN (%s) %s",$channelIds,$sysCondition);
		$result = parent::createSQL($sql)->toList();
		return $result;
	}
	
	/**
	 * 
	 * @param Int/array $categoryid
	 * @return 
	 */
	public static function listepgchannelcount($sysCondition,$categoryid=FALSE) {
		$con = "";
		if ($categoryid) 
			$con = "AND rch.`category_id`=$categoryid";
		if($sysCondition!='')
			$con.=" AND $sysCondition";
		$count = parent::createSQL(
				 "select count(*) from `skyg_res`.`res_channel` as rch WHERE 1=1 ". $con)->toValue();
		return $count;
	}
	
	/**
	 * 
	 * @param Int $page
	 * @param Int $pagesize
	 * @param Int $cid
	 * @return multitype:
	 */
	public static function listepgchannel($page, $pagesize,$sysCondition, $categoryid=FALSE) {		
		$start = $page*$pagesize;
		$con = "";
		if ($categoryid) 
			$con = sprintf("AND rch.`category_id`=%d",$categoryid);
		if($sysCondition!='')
			$con.=" AND $sysCondition";
		$result = parent::createSQL(
				 "SELECT 
				  rch.`channel_id`,
				  rch.`category_id`,
				  rch.`channel_img`,
				  rch.`channel_name`,
				  rch.`channel_url`,
				  rch.`created_date`,
				  IFNULL(rpg.program_id, 0) as program_id,
				  IFNULL(rpg.program_name, '') as program_name,
				  IFNULL(rpg.begintime, '') as  begintime 
				FROM
				   `skyg_res`.`res_channel` AS rch 
				  LEFT JOIN `skyg_res`.`res_program` AS rpg
				    ON rch.`channel_id` = rpg.`channel_id` 
				WHERE 1=1 
				".$con." limit :start,:pagesize",
		array(
				"start"=>(int)$start,
				"pagesize"=>(int)$pagesize		
			  )
		)->toList();		
		return $result;
	}

}