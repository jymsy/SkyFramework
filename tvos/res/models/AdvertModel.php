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
class AdvertModel extends \Sky\db\ActiveRecord{
	/**
	 *@return AdvertModel
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}

	protected static $tableName="skyg_res.res_advert";
	protected static $primeKey=array("ad_id");
	
	/**
	 * 
	 * @param String $name
	 * @param Int $type
	 * @param String $scene
	 * @param Int $position
	 * @param Int $page
	 * @param Int $pagesize
	 * @param Int $flag
	 * @return 
	 */
	public static function get_search_ads($name,$type,$scene,$position,$page,$pagesize,$flag=null){
		$sql = "SELECT 
				  ad.ad_id,
				  ad.name,
				  ad.type,
				  ad.url,
				  ad.flag,
				  ad.create_date,
				  pos.position,
				  pos.scene 
				FROM
				  `skyg_res`.`res_advert` AS `ad` 
				  LEFT JOIN `skyg_res`.`res_advert_pos` AS `pos` 
				    ON `ad`.ad_id = `pos`.ad_id 
				WHERE 1 = 1 ";
		if ($flag)
		{
			$sql .= " and `ad`.flag= %d";
			$sql = sprintf($sql,$flag);
		}
		$sqlconnect = "";
		if ($position) {
			$sqlconnect .= " and `advertising`.position=%d ";
			$sqlconnect = sprintf($sqlconnect,$position);
		}
		if ($name) {
			$sqlconnect .= " and `ad`.name like '%%s%'";
			$sqlconnect = sprintf($sqlconnect,$name);
		}
		if ($type) {
			$sqlconnect .= " and `ad`.type=%d";
			$sqlconnect = sprintf($sqlconnect,$type);
		}
		if ($scene) {
			$sqlconnect .= " and `advertising`.scene like '%%s%'";
			$sqlconnect = sprintf($sqlconnect,$scene);
		}
		$sqlconnect .= "group by `ad`.ad_id";
		if ($page && $pagesize) {
			$start = ($page-1)*$pagesize;
			$sqlconnect .= " limit $start,$pagesize ";
		}
		$sql .= $sqlconnect;
		return parent::createSQL($sql)->exec();
	}
	
	/**
	 * 
	 * @param String $name
	 * @param Int $type
	 * @param String $scene
	 * @param Int $position
	 * @param Int $flag
	 * @return Int count
	 */
	public static function get_search_ads_count($name,$type,$scene,$position,$flag=null){
		$sql = "SELECT 
				  COUNT(*) 
				FROM
				  `skyg_res`.`res_adver` AS `ad` 
				  LEFT JOIN `skyg_res`.`res_advert_pos` AS `advertising` 
				    ON `ad`.ad_id = `advertising`.ad_id 
				WHERE 1 = 1 ";
		if ($flag)
		{
			$sql .= " and `ad`.flag= %d";
			$sql = sprintf($sql,$flag);
		}
		$sqlconnect = "";
		if ($position) {
			$sqlconnect .= " and `advertising`.position=%d ";
			$sqlconnect = sprintf($sqlconnect,$position);
		}
		if ($name) {
			$sqlconnect .= " and `ad`.name like '%%s%'";
			$sqlconnect = sprintf($sqlconnect,$name);
		}
		if ($type) {
			$sqlconnect .= " and `ad`.type=%d";
			$sqlconnect = sprintf($sqlconnect,$type);
		}
		if ($scene) {
			$sqlconnect .= " and `advertising`.scene like '%%s%'";
			$sqlconnect = sprintf($sqlconnect,$scene);
		}
		$sqlconnect .= "group by `ad`.ad_id";
		$sql .= $sqlconnect;
		return parent::createSQL($sql)->exec();
	}
	
	/**
	 * 
	 * @return  type
	 */
	public static function get_ads_type(){
		$result=parent::createSQL(
					 "SELECT 
					  `type` 
					FROM
					  `skyg_res`.`res_advert` 
					GROUP BY `type`  "
			)->toList();
		return $result;
	}
	
	/**
	 * 
	 * @param array $adsId
	 * @return Int count
	 */
	public static function get_adsInAdsPosition_count($adsId){
		$num = intval($adsId);
		if ($num<1)return 0;
		$count=parent::createSQL(
				'SELECT 
				  COUNT(*) 
				FROM
				  `skyg_res`.`res_advert_pos` 
				WHERE ad_id = :num',
				array(
						'num'=>$num
						)
				)->exec();
		return $count;
	}
	
	/**
	 * 
	 * @param array $adsId
	 * @return Int >0success =0fail
	 */
	public static function delete_ads($adsId){
		$count = self::get_adsInAdsPosition_count($adsId);
		if ($count>0){
			$result_clean = self::clean_ads_position_adId($adsId);
			if (!$result_clean){
				return $result_clean;
			}
		}
		$conn = self::conn();
		$sql = "delete from `skyg_res`.`res_advert` where ad_id=%d";
		$sql=sprintf($sql,$adsId);
		$result = parent::createSQL($sql)->exec();
		return $result;
	}
	
	/**
	 * 
	 * @param Int $adsId
	 * @param String $name
	 * @param Int $type
	 * @param String $url
	 * @param Int $flag
	 * @return Int >0sucess =0fail
	 */
	public static function alter_ads($adsId,$name,$type,$url,$flag){
		if ($flag == 0){
			$result = self::clean_ads_position_adId($adsId);
			if (!$result)return $result;  
		}
		$sqlconnect = "";
		if ($name){
			if ($sqlconnect)$sqlconnect .= ",name='%s'";
			else $sqlconnect = "name='%s'";
			$sqlconnect = sprintf($sqlconnect,addslashes($name));
		}
		if ($type){
			if ($sqlconnect)$sqlconnect .= ",type='%d'";
			else $sqlconnect = "type='%d'";
			$sqlconnect = sprintf($sqlconnect,addslashes($type));
		}
		if ($url){
			if ($sqlconnect)$sqlconnect .= ",url='%s'";
			else $sqlconnect = "url='%s'";
			$sqlconnect = sprintf($sqlconnect,addslashes($url));
		}
		if ($flag){
			if ($sqlconnect)$sqlconnect .= ",flag=%d";
			else $sqlconnect = "flag=%d";
			$sqlconnect = sprintf($sqlconnect,addslashes($flag));
		}
		$sql = "update `skyg_res`.`res_advert` set $sqlconnect where ad_id=%d";echo $sql;
		$sql=sprintf($sql,$adsId);
		$result = parent::createSQL($sql)->exec();
		return $result;
	}
	
	
	/**
	 * 
	 * @param String $name
	 * @param Int $type
	 * @param String $url
	 * @param Int $flag
	 * @return Int >0sucess =0fail
	 */
	public static function add_ads($name,$type,$url,$flag){
		if ($name && $type && $url){
			if (!$flag)$flag = 1;
			
			$result = parent::createSQL(
					'INSERT INTO `skyg_res`.`res_advert` (`name`, `type`, `url`, `flag`) 
					VALUES (:name, :type, :url, :flag) ',
					array(
							'name'=>$name,
							'type'=>(int)$type,
							'url'=>$url,
							'flag'=>(int)$flag
							
						 )
			)->exec();
			return $result;
		}
		return FALSE;
	}
	
	/**
	 * 
	 * @param Int $adsId
	 * @return multitype:
	 */
	public static function get_ads($adsId){
		$sql = 'SELECT 
				  `ad_id`,`name`,`type`,`url`,`flag`,`created_date` 
				FROM
				  `skyg_res`.`res_advert` 
				WHERE ad_id = %d ';
		$sql=sprintf($sql,$adsId);
		return parent::createSQL($sql)->toList();
	}

	/**
	 * 
	 * @param String $scene
	 * @param Int $position
	 * @param Int $page
	 * @param Int $pagesize
	 * @return multitype:
	 */
	public static function get_search_ads_position($scene,$position,$page,$pagesize){
		$sql = "SELECT 
				  advertising.*,
				  ad.name,
				  ad.type 
				FROM
				  `skyg_res`.`res_advert_pos` AS advertising 
				  LEFT JOIN `skyg_res`.`res_advert` AS ad 
				    ON advertising.ad_id = ad.ad_id 
				WHERE 1 = 1 ";
		$sqlconnect = "";
		if ($position) {
			$sqlconnect .= " and advertising.position=%d ";
			$sqlconnect = sprintf($sqlconnect,$position);
		}
		if ($scene) {
			$sqlconnect .= " and `advertising`.scene like '%%s%' ";
			$sqlconnect = sprintf($sqlconnect,$scene);
		}
		if ($page && $pagesize) {
			$start = ($page-1)*$pagesize;
			$sqlconnect .= " limit $start,$pagesize ";
		}
		$sql .= $sqlconnect;
		return parent::createSQL($sql)->toList();
	}
	
	/**
	 * 
	 * @param String $scene
	 * @param Int $position
	 * @return Int count
	 */
	public static function get_search_ads_position_count($scene,$position){
		$sql = "SELECT 
				  COUNT(*) 
				FROM
				  `skyg_res`.`res_advert_pos` AS advertising 
				  LEFT JOIN `skyg_res`.`res_advert` AS ad 
				    ON advertising.ad_id = ad.ad_id 
				WHERE 1 = 1 ";
		$sqlconnect = "";
		if ($position || $position == 0) {
			$sqlconnect .= " and advertising.position=%d ";
			$sqlconnect = sprintf($sqlconnect,$position);
		}
	if ($scene) {
			$sqlconnect .= " and `advertising`.scene like '%%s%' ";
			$sqlconnect = sprintf($sqlconnect,$scene);
		}
		$sql .= $sqlconnect;
		return parent::createSQL($sql)->toValue();
	}
	
	public static function delete_ads_position($positionId){
		$conn = self::conn();
		$sql = "delete from `skyg_res`.`res_advert_pos` where ad_pos_id=%d";
		$sql = sprintf($sql,$positionId);
		$result = parent::createSQL($sql)->exec();
		return $result;
	}
	
	/**
	 * 
	 * @param String $scene
	 * @param Int $position
	 * @param Int $ad_id
	 * @return 
	 */
	public static function add_ads_position($scene,$position,$ad_id){
		if ($scene && ($position || $position == 0)){			
			$sql = "insert into `skyg_res`.`res_advert_pos` (scene,position,ad_id) values ('%s',%d,%d)";
			$sql=sprintf($sql,addslashes($scene),$position,$ad_id);
			$result = parent::createSQL($sql)->exec();
			return $result;
		}
		return FALSE;
	}
	
	/**
	 * 
	 * @return multitype:
	 */
	public static function get_ads_position_scene(){
		$sql = "select scene from `skyg_res`.`res_advert_pos` group by scene ";
		return parent::createSQL($sql)->toList();
	}
	
	/**
	 * 
	 * @param unknown_type $positionId
	 * @return multitype:
	 */
	public static function get_ads_position($positionId){
		$sql = "SELECT 
				  `ad_pos_id`,
				  `position`,
				  `scene`,
				  `ad_id`,
				  `created_date` 
				FROM
				  `skyg_res`.`res_advert_pos` 
				WHERE ad_pos_id = %d ";
		$sql=sprintf($sql,$positionId);
		return parent::createSQL($sql)->toList();
	}
	
	/**
	 * 
	 * @param unknown_type $positionId
	 * @param unknown_type $scene
	 * @param unknown_type $position
	 * @param unknown_type $ad_id
	 * @return number|boolean
	 */
	public static function alter_ads_position($positionId,$scene, $position, $ad_id){
		if ($scene &&( $position  || $position == 0)){
			$sqlconnect = "scene='%s',position=%d ";
			$sqlconnect = sprintf($sqlconnect,$scene,$position);
			if ($ad_id){
				$sqlconnect .= ",ad_id=%d";
				$sqlconnect = sprintf($sqlconnect,$ad_id);
			}else{
				$sqlconnect .= ",ad_id=null";
			}
			$sql = "update `skyg_res`.`res_advert_pos` set $sqlconnect where id=%d ";
			$sql=sprintf($sql,$positionId);
			//$result = $conn->execute(sprintf($sql,$positionId));
			return parent::createSQL($sql)->exec();
			//return $result;
		}
		return FALSE;
		
	}
	
	/**
	 * 
	 * @param Int $ad_id
	 * @return number
	 */
	public static function clean_ads_position_adId($ad_id){
		$result=parent::createSQL(
				"update `skyg_res`.`res_advert_pos` set ad_id=null where ad_id=:ad_id",
				array(
					 'ad_id'=>$ad_id	
					 )
		)->exec();
		return $result;
	}
	
	/**
	 * 
	 * @param Int $positionId
	 * @param String $scene
	 * @param Int $position
	 * @return Int count
	 */
	public static function check_ads_position_count($positionId,$scene, $position){
		$sql = "SELECT 
				  COUNT(*) 
				FROM
				  `skyg_res`.`res_advert_pos` AS advertising 
				WHERE ad_pos_id != %d ";
		$sql = sprintf($sql,$positionId);
		$sqlconnect = "";
		if ($position || $position == 0) {
			$sqlconnect .= " and advertising.position=%d ";
			$sqlconnect = sprintf($sqlconnect,$position);
		}
		if ($scene) {
			$sqlconnect .= " and `advertising`.scene like '%%s%' ";
			$sqlconnect = sprintf($sqlconnect,$scene);
		}
		$sql .= $sqlconnect;
		return parent::createSQL($sql)->toValue();
	}

}