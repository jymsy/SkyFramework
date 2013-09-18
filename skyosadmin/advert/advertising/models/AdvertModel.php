<?php
namespace advert\Advertising\models;
use skyosadmin\components\PublicModel;
/**
 * 
 * @author xiaokeming
 *
 */

class AdvertModel extends \Sky\db\ActiveRecord{

	/**
	 *@return MergeVideoModel
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}
	/**
	 * 
	 * @param STRING $NAME               广告名
	 * @param INT    $TYPE               1-pic,2-html
	 * @param STRING $scene              广告位名称
	 * @param STRING $POSITION           广告位置
	 * @param INT    $START
	 * @param INT    $pagesize
	 * @param INT    $flag               1-USING,0-STOP
	 */
	public static function searchAds($name,$type,$scene,$position,$start,$pagesize,$flag=null,$orderCondition=array('ad_id'=>'DESC')){
		$orderString=PublicModel::controlArray($orderCondition);
		$sql = " select * from (SELECT ra.`ad_id`,ra.`name`,ra.`type`,ra.`url`,ra.`flag`,ra.`create_date`,rap.`position`,rap.`scene` FROM `skyg_res`.`res_advert` AS `ra` 
			LEFT JOIN `skyg_res`.`res_advert_pos` AS `rap` 
			ON `ra`.`ad_id`=`rap`.`ad_id`
			WHERE 1=1 ";
		if ($flag)$sql .= " and `ra`.`flag`='".addslashes($flag)."' ";
		$sqlconnect = "";
		if ($position) {
			$sqlconnect .= " and `rap`.`position`=%d ";
			$sqlconnect = sprintf($sqlconnect,$position);
		}
		if ($name) {
			$sqlconnect .= " and `ra`.`name` like '".addslashes("%".$name."%")."' ";
		}
		if ($type) {
			$sqlconnect .= " and `ra`.`type`='".addslashes($type)."' ";
		}
		if ($scene) {
			$sqlconnect .= " and `rap`.`scene` like '".addslashes("%".$scene."%")."' ";
		}
		$sqlconnect .= "group by `ra`.`ad_id` ";
		
		$sql .= $sqlconnect.") as a order by ".$orderString." limit ".$start.",".$pagesize;
		return parent::createSQL($sql)->toList();
	}
	
	/**
	 *
	 * @param STRING $NAME               广告名
	 * @param INT    $TYPE               1-pic,2-html
	 * @param STRING $scene              广告位名称
	 * @param STRING $POSITION           广告位置
	 * @param INT    $flag               1-USING,0-STOP
	 */
	public static function searchAdsCount($name,$type,$scene,$position,$flag=null){
		$sql = "select count(1) from (SELECT ra.*,rap.`position`,rap.`scene` FROM `skyg_res`.`res_advert` AS `ra`
			LEFT JOIN `skyg_res`.`res_advert_pos` AS `rap`
			ON `ra`.`ad_id`=`rap`.`ad_id`
			WHERE 1=1 ";
		if ($flag)$sql .= " and `ra`.`flag`='".addslashes($flag)."' ";
		$sqlconnect = "";
		if ($position) {
			$sqlconnect .= " and `rap`.`position`=%d ";
			$sqlconnect = sprintf($sqlconnect,$position);
		}
		if ($name) {
			$sqlconnect .= " and `ra`.`name` like '".addslashes("%".$name."%")."' ";
		}
		if ($type) {
			$sqlconnect .= " and `ra`.`type`='".addslashes($type)."' ";
		}
		if ($scene) {
			$sqlconnect .= " and `rap`.`scene` like '".addslashes("%".$scene."%")."' ";
		}
		$sqlconnect .= "group by `ra`.`ad_id` ";
	
		$sql .= $sqlconnect.") as a";
		return parent::createSQL($sql)->toValue();
	}
	/**
	 * 返回type列值
	 */
	public static function getAdsType(){
		$sql = "select ra.`type` from `skyg_res`.`res_advert` as `ra` group by `ra`.`type` ";
		return parent::createSQL($sql)->toList();
	}
	
	/**
	 * 
	 * @param int $adsId   广告ID
	 * @return number      返回COUNT值 
	 */
	public static function getAdsInAdsPositionCount($adsId){
		$num = intval($adsId);
		if ($num<1)return 0;
		$sql = "select count(*) from `skyg_res`.`res_advert_pos` where `ad_id`=$num";
		return parent::createSQL($sql)->toValue();
	}
	
	/**
	 * 
	 * @param int $adsId   广告 ID
	 * @return unknown
	 */
	public static function deleteAds($adsId){
		$count = self::getAdsInAdsPositionCount($adsId);
		if ($count>0){
			$result_clean = self::cleanAdsPositionAdId($adsId);
			if (!$result_clean){
				return $result_clean;
			}
		}
		$sql = "delete from `skyg_res`.`res_advert` where `ad_id`=%d";
		$sql = sprintf($sql,$adsId);
		return parent::createSQL($sql)->exec();
	}
	
	/**
	 * 
	 * @param int    $adsId   广告ID
	 * @param string $name    广告名
	 * @param int    $type    1-pic,2-html
	 * @param string $url     广告链接
	 * @param int    $flag    1-using,0-stop
	 * @return unknown
	 */
	public static function alterAds($adsId,$name,$type,$url,$flag){
		if ($flag == 'stop'){
			$result = self::cleanAdsPositionAdId($adsId);
			if (!$result)return $result;
		}
		$sqlconnect = "";
		if ($name){
			if ($sqlconnect)$sqlconnect .= ",`name`='%s'";
			else $sqlconnect = "`name`='%s'";
			$sqlconnect = sprintf($sqlconnect,addslashes($name));
		}
		if ($type){
			if ($sqlconnect)$sqlconnect .= ",`type`='%s'";
			else $sqlconnect = "`type`='%s'";
			$sqlconnect = sprintf($sqlconnect,addslashes($type));
		}
		if ($url){
			if ($sqlconnect)$sqlconnect .= ",`url`='%s'";
			else $sqlconnect = "`url`='%s'";
			$sqlconnect = sprintf($sqlconnect,addslashes($url));
		}
		if ($flag){
			if ($sqlconnect)$sqlconnect .= ",`flag`='%s'";
			else $sqlconnect = "`flag`='%s'";
			$sqlconnect = sprintf($sqlconnect,addslashes($flag));
		}
		$sql = "update `skyg_res`.`res_advert` set $sqlconnect where `ad_id`=%d";
		$sql = sprintf($sql,$adsId);
		return parent::createSQL($sql)->exec();
	}
	
	/**
	 * @param string $name    广告名
	 * @param int    $type    1-pic,2-html
	 * @param string $url     广告链接
	 * @param int    $flag    1-using,0-stop
	 */
	public static function addAds($name,$type,$url,$flag){
		if ($name && $type && $url){
			if (!$flag)$flag = "using";
			$sql = "insert into `skyg_res`.`res_advert` (`name`,`type`,`url`,`flag`) values ('%s','%s','%s','%s') ";
			$sql = sprintf($sql,addslashes($name),addslashes($type),addslashes($url),addslashes($flag));
			return parent::createSQL($sql)->exec();
		}
		return 0;
	}
	
	/**
	 * 
	 * @param int $adsId   广告ID
	 */
	public static function getAds($adsId){
		$sql = "select ra.`ad_id`,ra.`name`,ra.`type`,ra.`url`,ra.`flag`,ra.`create_date` from `skyg_res`.`res_advert` as ra where ra.`ad_id`=%d";
		return parent::createSQL(sprintf($sql,$adsId))->toList();
	}
	/**
	 * 
	 * @param string $scene     广告位名称
	 * @param ing    $position  广告位置
	 * @param unknown_type $start
	 * @param unknown_type $pagesize
	 */
	public static function getSearchAdsPosition($scene,$position,$start,$pagesize,$orderCondition=array('ad_pos_id'=>'DESC')){
		$orderString=PublicModel::controlArray($orderCondition);
		$sql = "select * from (select rap.`ad_pos_id`,rap.`position`,rap.`scene`,rap.`ad_id`,rap.`create_date`,ra.`name`,ra.`type` from `skyg_res`.`res_advert_pos` as `rap` 
		left join `skyg_res`.`res_advert` as `ra` 
		on `rap`.`ad_id`=`ra`.`ad_id`
		where 1=1 ";
		$sqlconnect = "";
		if ($position) {
			$sqlconnect .= " and `rap`.`position`=%d ";
			$sqlconnect = sprintf($sqlconnect,$position);
		}
		if ($scene) {
			$sqlconnect .= " and `rap`.`scene` like '".addslashes("%".$scene."%")."' ";
		}
		
		
		$sql .= $sqlconnect.") as a order by ".$orderString." limit ".$start.",".$pagesize ;
		return parent::createSQL($sql)->toList();
	}
	
	/**
	 *
	 * @param string $scene     广告位名称
	 * @param ing    $position  广告位置
	 */
	public static function getSearchAdsPositionCount($scene,$position){
		$sql = "select count(*) from `skyg_res`.`res_advert_pos` as rap
		left join `skyg_res`.`res_advert` as ra
		on rap.`ad_id`=ra.`ad_id`
		where 1=1 ";
		$sqlconnect = "";
		if ($position) {
			$sqlconnect .= " and rap.`position`=%d ";
			$sqlconnect = sprintf($sqlconnect,$position);
		}
		if ($scene) {
			$sqlconnect .= " and rap.`scene` like '".addslashes("%".$scene."%")."' ";
		}
		$sql .= $sqlconnect;
		return parent::createSQL($sql)->toValue();
	}
	
	/**
	 * 
	 * @param int $positionId  广告位ID
	 */
	public static function deleteAdsPosition($positionId){
		$sql = "delete from `skyg_res`.`res_advert_pos` where `ad_pos_id`=%d";
		return parent::createSQL(sprintf($sql,$positionId))->exec();
	}
	
	/**
	 * 
	 * @param string $scene      广告位名称
	 * @param int    $position   广告位置
	 * @param int    $ad_id      广告ID
	 */
	public static function addAdsPosition($scene,$position,$ad_id){
		if ($scene && ($position || $position == 0)){
			$sql = "insert into `skyg_res`.`res_advert_pos` (`scene`,`position`,`ad_id`) values ('%s',%d,%d)";
			return parent::createSQL(sprintf($sql,addslashes($scene),$position,$ad_id))->exec();
			
		}
		return 0;
	}
	
	/**
	 * 返回广告位名称列值
	 */
	public static function getAdsPositionScene(){
		$sql = "select `scene` from `skyg_res`.`res_advert_pos` group by `scene` ";
		return parent::createSQL($sql)->toList();
	}
	
	/**
	 * 
	 * @param int $positionId   广告位ID
	 */
	public static function getAdsPosition($positionId){
		$sql = "select rap.`ad_pos_id`,rap.`position`,rap.`scene`,rap.`ad_id`,rap.`create_date` from `skyg_res`.`res_advert_pos` as rap where rap.`ad_pos_id`=%d";
		return parent::createSQL(sprintf($sql,$positionId))->toList();
	}
	
	/**
	 * 
	 * @param int    $positionId   广告位ID
	 * @param string $scene        广告位名称
	 * @param int    $position     广告 位置
	 * @param int    $ad_id        广告ID
	 * @return number
	 */
	public static function alterAdsPosition($positionId,$scene, $position, $ad_id){
		if ($scene &&( $position  || $position == 0)){
			$sqlconnect = "`scene`='%s',`position`=%d ";
			$sqlconnect = sprintf($sqlconnect,addslashes($scene),$position);
			if ($ad_id){
				$sqlconnect .= ",`ad_id`=%d";
				$sqlconnect = sprintf($sqlconnect,$ad_id);
			}else{
				$sqlconnect .= ",`ad_id` is null ";
			}
			$sql = "update `skyg_res`.`res_advert_pos` set $sqlconnect where `ad_pos_id`=%d ";
			return parent::createSQL(sprintf($sql,$positionId))->exec();
		}
		return 0;
	
	}
	
	/**
	 * 
	 * @param int $ad_id  广告 ID 
	 */
	public static function cleanAdsPositionAdId($ad_id){
		$sql = "update `skyg_res`.`res_advert_pos` set `ad_id`=null where `ad_id`=%d";
		return parent::createSQL(sprintf($sql,$ad_id))->exec();
	}
	
	/**
	 * 
	 * @param int    $positionId   广告位ID
	 * @param string $scene        广告位名称
	 * @param int    $position     广告 位置
	 */
	public static function checkAdsPositionCount($positionId,$scene, $position){
		$sql = "select count(*) from `skyg_res`.`res_advert_pos` as rap 
		where rap.`ad_pos_id`!=%d ";
		$sql = sprintf($sql,$positionId);
		$sqlconnect = "";
		if ($position || $position == 0) {
			$sqlconnect .= " and rap.`position`=%d ";
			$sqlconnect = sprintf($sqlconnect,$position);
		}
		if ($scene) {
			$sqlconnect .= " and rap.`scene` like '".addslashes("%".$scene."%")."' ";
		}
		$sql .= $sqlconnect;
		return parent::createSQL($sql)->toValue();
	}
}