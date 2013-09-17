<?php

namespace res\models;

/**table hotword
 * @property  string       key                          
 * @property  int          type                         
 * @property  int          num                          
 * @author Zhengyun
 */
class SearchModel extends \Sky\db\ActiveRecord{
	/**
	 *@return SearchModel
	 */
		
	public static function model($className=__CLASS__){
		return parent::model($className);
	}

	//protected static $tableName="skyg_base.base_user";
	//protected static $primeKey=array("user_id");
	
	
	/**
	 * 获取搜索次数较多的关键字
	 * @param Int $size
	 * @return array
	 * */
	public static function getHotwords($size){
		$result=parent::createSQL(
				"SELECT 
				  `key`,
				  `type`,
				  `num` 
				FROM
				  `skyg_res`.`res_hotword` 
				ORDER BY `num` DESC 
				LIMIT :size ",
				array(						
						"size"=>(int)$size
				)
		)->toList();
		return $result;
	}
	
	/**
	 * 更新hotword表数据，没有的数据添加，已有的数据，num字段+1
	 * @param String $key
	 * @return Int result  result>0 success， other fail
	 */
	
	public static function updateHotwords($key){
		$ks = explode(" ", $key);
		$sql = '';
		$format = "('%s',%d)";
		foreach ($ks AS $k){
			$k = trim($k);
			if ($k != ""){
				if ($sql != '') {
					$sql .= ",";
				}
				$sql .= sprintf($format,addslashes($k),0);
			}
		}
		$sql = "insert into `skyg_res`.`res_hotword` (`key`,`type`) values".$sql." on duplicate key update `num`=`num`+1";
		
		$result=parent::createSQL($sql)->exec();
		return $result;
	}
	
	/**
	 * 查询符合要求的video记录条数
	 * @param string $key
	 * @param Int $start
	 * @param Int $pagesize
	 * @return Int count 
	 */
	public static function getVideoCount($key,$sysCondition) {
		$ks = explode(" ", $key);
		$conditionString = '';
		//$format = "(a.`title` like '%%%s%%' or a.`firstchars` like '%s%%' or a.`firstchars` like '%%,%s%%')";
		$format = "(rv.`title` like '%%%s%%')";
		if (count($ks) > 0) {
			foreach ($ks as $k) {
				$k = trim($k);
				if ($k != ""){
					if ($conditionString != '') {
						$conditionString .= " and ";
					}
					//$s .= sprintf($format,addslashes($k),addslashes($k),addslashes($k));
					$conditionString .= sprintf($format, addslashes($k));
				}
			}
		}
		
		$sql = "select count(*) from `skyg_res`.`res_video` rv where `expired`=0";
		if ($conditionString != '') {
			$sql .= " and $conditionString";
		}
		if ($sysCondition != '') {
			$sql .= " and $sysCondition";
		}
		//echo($sql);
		
		$count=parent::createSQL($sql)->toValue();		
		return $count;
	}
	
	/**
	 * 查询符合要求的video记录条数
	 * @param string $key
	 * @param Int $start
	 * @param Int $pagesize
	 * @return Int count 
	 */	
	public static function getVideoDetail($key, $start, $pagesize,$sysCondition) {	
		$ks = explode(" ", $key);
		$conditionString = '';
		//$format = "(a.`title` like '%%%s%%' or a.`firstchars` like '%s%%' or a.`firstchars` like '%%,%s%%')";
		$format = "(rv.`title` like '%%%s%%')";
		if (count($ks) > 0) {
			foreach ($ks as $k) {
				$k = trim($k);
				if ($k != ""){
					if ($conditionString != '') {
						$conditionString .= " and ";
					}
					//$s .= sprintf($format,addslashes($k),addslashes($k),addslashes($k));
					$conditionString .= sprintf($format, addslashes($k));
				}
			}
		}	
		$sql = "select * from `skyg_res`.`res_video` rv where `expired`=0";
		if ($conditionString != '') {			
			$sql .= " and $conditionString";
		}
		if ($sysCondition != '') {
			$sql .= " and $sysCondition";
		}
		$sql .= " order by `vip` desc,`release_date` desc limit :start,:pagesize";
		$result =parent::createSQL($sql,
				array(
						"start"=>(int)$start,
						"pagesize"=>(int)$pagesize
				)
		)->toList();
	
		//$result = $video->loadSite($result);
		return $result;
	}
	
	
	/**
	 * 查询符合要求的咨询记录条数
	 * @param String $key
	 * @param Int $start
	 * @param Int $pagesize
	 * @return Int count
	 */
	public static function getInfoCount($key, $sysCondition) {
		$ks = explode(" ", $key);
		$conditionString = '';
		$format = "`title` like '%%%s%%'";
		if (count($ks) > 0) {
			foreach ($ks as $k) {
				$k = trim($k);
				if ($k != ""){
					if ($conditionString != '') {
						$conditionString .= " and ";
					}
					$conditionString .= sprintf($format,addslashes($k));
				}
			}
		}
		$sql = "select count(*) from `skyg_res`.`res_news` as rn";
		if ($conditionString != '') {
			$sql .= " where $conditionString";
		}		
		if ($sysCondition != '') {
			$sql .= " and $sysCondition";
		}
		$count=parent::createSQL($sql)->toValue();
		
		return $count;
	}
	
	/**
	 * 查询符合要求的咨询记录
	 * @param String $key
	 * @param Int $start
	 * @param Int $pagesize
	 * @return array
	 */
	public static function getInfoDetail($key,$start, $pagesize,$InfoUrlPrefix,$sysCondition) {
		$ks = explode(" ", $key);
		$conditionString = '';
		$format = "`title` like '%%%s%%'";
		if (count($ks) > 0) {
			foreach ($ks as $k) {
				$k = trim($k);
				if ($k != ""){
					if ($conditionString != '') {
						$conditionString .= " and ";
					}
					$conditionString .= sprintf($format,addslashes($k));
				}
			}
		}
		$sql = "SELECT 
				  `level`,
				  `news_id`,
				  `category_id`,
				  `title`,
				  `link`,
				  `logo`,
				  `logo` AS thumb,
				  `brief`,
				  concat('".$InfoUrlPrefix."',`news_id`) AS `url` 
				FROM
				  `skyg_res`.`res_news` as rn ";
		if ($conditionString != '') {			
			$sql .= " where $conditionString";
		}	
		if ($sysCondition != '') {
			$sql .= " and $sysCondition";
		}	
		$sql .= " order by `create_time` desc limit :start,:pagesize";
		$result =parent::createSQL($sql,
				array(
						"start"=>(int)$start,
						"pagesize"=>(int)$pagesize
				)
		)->toList();
		return $result;
	}
	
	/**
	 * 查询符合要求的audio记录条数
	 * @param String $key
	 * @param Int $start
	 * @param Int $pagesize
	 * @return Int count 
	 */
	public static function getAudioCount($key, $sysCondition) {
		$ks = explode(" ", $key);
		$conditionString = '';
		//$format = "(a.`title` like '%%%s%%' or a.`firstchars` like '%s%%' or a.`firstchars` like '%%,%s%%')";
		$format = "(rmt.`title` like '%%%s%%')";
		if (count($ks) > 0) {
			foreach ($ks as $k) {
				$k = trim($k);
				if ($k != ""){
					if ($conditionString != '') {
						$conditionString .= " and ";
					}
					//$s .= sprintf($format,addslashes($k),addslashes($k),addslashes($k));
					$conditionString .= sprintf($format, addslashes($k));
				}
			}
		}
		$sql = "select count(*) from (select rmt.`music_top_id` from  `skyg_res`.`res_music_top` as rmt left join `skyg_res`.`res_audio_singer` as rasi 
				on rasi.`name`=rmt.`singer` where rmt.`lrc`!='' and rmt.`expired`=0";
		if ($conditionString != '') {
			$sql .= " and $conditionString";
		}
		if ($sysCondition != '') {
			$sql .= " and $sysCondition";
		}
		$sql .= " group by `title`,`singer`) as m";		
		$count=parent::createSQL($sql)->toValue();		
		
		return $count;
	}
	
	/**
	 * 查询符合要求的Audio记录
	 * @param String $key
	 * @param Int $start
	 * @param Int $pagesize
	 * @return array
	 */
	public static function getAudioDetail($key, $start, $pagesize,$sysCondition) {
		$ks = explode(" ", $key);
		$conditionString = '';
		//$format = "(a.`title` like '%%%s%%' or a.`firstchars` like '%s%%' or a.`firstchars` like '%%,%s%%')";
		$format = "(rmt.`title` like '%%%s%%')";
		if (count($ks) > 0) {
			foreach ($ks as $k) {
				$k = trim($k);
				if ($k != ""){
					if ($conditionString != '') {
						$conditionString .= " and ";
					}
					//$s .= sprintf($format,addslashes($k),addslashes($k),addslashes($k));
					$conditionString .= sprintf($format, addslashes($k));
				}
			}
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
				  AND rmt.`expired` = 0 ";
		if ($conditionString != '') {
			$sql .= " and $conditionString";
		}
		if ($sysCondition != '') {
			$sql .= " and $sysCondition";
		}
		
		$sql .= " group by `title`,`artist` limit :start,:pagesize";
		$result =parent::createSQL($sql,
				array(
						"start"=>(int)$start,
						"pagesize"=>(int)$pagesize
				)
		)->toList();
	
	
		return $result;
	}

}