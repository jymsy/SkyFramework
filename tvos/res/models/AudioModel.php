<?php

namespace res\models;

/**table hotword
 * @property  string       key                          
 * @property  int          type                         
 * @property  int          num                          
 * @author Zhengyun
 */
class AudioModel extends \Sky\db\ActiveRecord{
	/**
	 *@return AudioModel
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}
	

	//protected static $tableName="skyg_base.base_user";
	//protected static $primeKey=array("user_id");
	
	
	/**
	 * 
	 * @param String $audioIds
	 * @return 
	 */
	public static function showAudio($audioIds,$sysCondition='') {
		 if($sysCondition!='')
		 	$sysCondition=' AND '.$sysCondition;
				$sql=sprintf(
					"SELECT 
					  rmt.`category_id`,
					  rmt.`music_top_id` AS `id`,
					  rmt.`title`,
					  rmt.`singer` AS `artist`,
					  IFNULL(rasi.`pic`, '') AS `artist_pic`,
					  IFNULL(rasi.`pic`, '') AS `thumb`,
					  rmt.`url`,
					  rmt.`source`,
					  rmt.`lrc` AS `lyric_url`,
					  rmt.`level` 
					FROM
					  `skyg_res`.`res_music_top` AS rmt
					  LEFT JOIN `skyg_res`.`res_audio_singer` AS rasi 
					    ON rasi.`name` = rmt.`singer` 
					WHERE rmt.`music_top_id` IN (%s) %s",$audioIds,$sysCondition);
		
		$result=parent::createSQL($sql)->toList();
		return $result;
	}
	
	/**
	 * 
	 * @param unknown_type $conditionString
	 * @param unknown_type $page
	 * @param unknown_type $pagesize
	 * @param unknown_type $Union
	 * @return Int count
	 */

	public static function ListSourcesCount($condition,$sysCondition='',$Union=1){
		$attach = $Union==1?"and":"or";
		$con="";
		foreach ($condition as $k=>$v) {
			$k = strtolower($k);
			if ($v != '') {
				if(isset($con) && !empty($con)){
					$con .= " ".$attach." " ;
				}
				if ($k == 'album'){
					$con .= " raa.`title`='".addslashes($v)."' ";
				}
				else{
					$con .= " ras.`$k`='".addslashes($v)."' ";
				}
			}
		}
		$sql = "SELECT 
				  COUNT(*) 
				FROM
				  `skyg_res`.`res_audio_song` AS ras 
				  LEFT JOIN `skyg_res`.`res_audio_singer` AS rasi
				    ON rasi.`name` = ras.`singer` 
				  LEFT JOIN `skyg_res`.`res_audio_album` AS raa 
				    ON raa.`album_id` = ras.`album_id` 
				WHERE ras.`lrc` != '' 
				  AND ras.`expired` = 0 ";
		if ($con != '') $sql .= " and $con";
		if($sysCondition!='') $sql.=" and $sysCondition";
		$count = parent::createSQL($sql)->toValue();		
		return $count;
	}
	
	/**
	 * 
	 * @param unknown_type $conditionString
	 * @param unknown_type $page
	 * @param unknown_type $pagesize
	 * @param unknown_type $Union
	 * @return multitype:
	 */
	public static function ListSourcesDetail($condition,$page,$pagesize,$sysCondition='',$Union=1){		
		$start = $page*$pagesize;
		$attach = $Union==1?"and":"or";	
		$con="";
		foreach ($condition as $k=>$v) {
			$k = strtolower($k);
			if ($v != '') {
				if(isset($con) && !empty($con)){
					$con .= " ".$attach." " ;
				}
				if ($k == 'album'){
					$con .= " raa.`title`='".addslashes($v)."' ";
				}
				else{
					$con .= " ras.`$k`='".addslashes($v)."' ";
				}
			}
		}
		$sql = "SELECT 
				  ras.`category_id`,
				  ras.`audio_song_id` AS `id`,
				  ras.`title`,
				  ras.`singer` AS `artist`,
				  IFNULL(rasi.`pic`, '') AS `artist_pic`,
				  IFNULL(rasi.`pic`, '') AS `thumb`,
				  ras.`url`,
				  ras.`from`,
				  ras.`lrc` AS `lyric_url`,
				  ras.`level`,
				  raa.`title` AS `album` 
				FROM
				  `skyg_res`.`res_audio_song` AS ras 
				  LEFT JOIN `skyg_res`.`res_audio_singer` AS rasi 
				    ON rasi.`name` = ras.`singer` 
				  LEFT JOIN `skyg_res`.`res_audio_album` AS raa 
				    ON raa.`album_id` = ras.`album_id` 
				WHERE ras.`lrc` != '' 
				  AND ras.`expired` = 0 ";
		if ($con != '') $sql .= " and $con ";
		if($sysCondition!='') $sql.=" and $sysCondition";
		$sql .= " order by `sequence`,`popularity` desc ";
		$sql .= " limit :start,:pagesize";
		$result=parent::createSQL($sql,
				array(
						"start"=>(int)$start,
						"pagesize"=>(int)$pagesize
						
				)
				)->toList();
		return $result;
	}	
	
	/**
	 * 
	 * @param Array $condition $k=>$v
	 * @return Int count
	 */
	public static function listAudioCount($condition,$sysCondition) {
		$con = '';
		foreach ($condition as $k=>$v) {
			if ($k == 'categoryid') 
				$con .= "rmt.`category_id`=$v";
		}
		
		$sql = "SELECT 
				  COUNT(*) 
				FROM
				  `skyg_res`.`res_music_top` AS rmt 
				  LEFT JOIN `skyg_res`.`res_audio_singer` AS rasi 
				    ON rasi.`name` = rmt.`singer` 
				WHERE rmt.`lrc` != '' 
				  AND rmt.`expired` = 0 
				  AND rmt.`page_index` != 0";
		if ($con != '') 
			$sql .= " AND $con";
		if($sysCondition!='') 
			$sql.=" AND $sysCondition";
		$count = parent::createSQL($sql) ->toValue();		
		return $count;
	}
	
	/**
	 * 
	 * @param Array $condition
	 * @param Int $page
	 * @param Int $pagesize
	 * @param Int $isFM  1-FM MODE ,0-not FM
	 * @return multitype:
	 */

	public static function listAudioDetail($condition,$page,$pagesize,$isFM,$sysCondition) {
		$start = $page*$pagesize;
		$con = '';
		foreach ($condition as $k=>$v) {
			if ($k == 'categoryid') $con .= "rmt.`category_id`=$v";
		}		
		$sql = "SELECT 
				  rmt.`category_id`,
				  rmt.`music_top_id`,
				  rmt.`title`,
				  rmt.`singer` AS `artist`,
				  IFNULL(rasi.`pic`, '') AS `artist_pic`,
				  IFNULL(rasi.`pic`, '') AS `thumb`,
				  rmt.`url`,
				  rmt.`source`,
				  rmt.`lrc` AS `lyric_url`,
				  rmt.`level` 
				FROM
				  `skyg_res`.`res_music_top` AS rmt 
				  LEFT JOIN `skyg_res`.`res_audio_singer` AS rasi
				    ON rasi.`name` = rmt.`singer` 
				WHERE rmt.`lrc` != '' 
				  AND rmt.`expired` = 0 
				  AND rmt.page_index != 0";
		if ($con != '') 
			$sql .= " AND $con ";
		if($sysCondition!='') 
			$sql.=" AND $sysCondition ";
		if ($isFM == 1){
			$sql .= " order by rand() ";
		} else {
			$sql .= " order by `page_index`,`created_date` desc ";
		}
		$sql .= "limit $start,$pagesize";
		$result = parent::createSQL($sql) ->toList();
		return $result;
	}

}