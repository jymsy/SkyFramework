<?php

namespace res\models;

/**table news
 * @property  int          news_id      咨询id                                                               
 * @property  string       title        标题                                                                 
 * @property  string       brief        简介                                                                 
 * @property  int          create_time  创建时间                                                           
 * @property  string       logo         文章缩图                                                           
 * @property  int          outreach_id  外链ID                                                               
 * @property  int          category_id  分类id                                                               
 * @property  int          ispic        是否有图，0：无，1：有                                       
 * @property  string       from         来源                                                                 
 * @property  string       link         外部链接                                                           
 * @property  string       isempty                                                                             
 * @property  int          level        资源等级，0：外网免费，1：外网收费，2：内网免费  
 * @property  int          resmark      资源更新标识  
 *                               
 * @author Zhengyun
 */
class InfoModel extends \Sky\db\ActiveRecord{
	/**
	 *@return InfoModel
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}

	//protected static $tableName="skyg_base.base_user";
	//protected static $primeKey=array("user_id");
	
	
	/**
	 * 
	 * @param String $InfoUrlPrefix
	 * @param String $news_sub_ids
	 * @return multitype:
	 */
   /*
	public static function  showNewsSubInfo($InfoUrlPrefix, $news_sub_ids,$sysCondition='') {
		if($sysCondition!='')
			$sysCondition=' AND '.$sysCondition;
		$result = parent::createSQL(
				"SELECT 
				  `news_sub_id`,
				  `title`,
				  `link`,
				  `logo`,
				  `logo` AS thumb,
				  `brief`,
				  CONCAT('".$InfoUrlPrefix.".sub_', `news_sub_id`) AS `url` 
				FROM
				  `skyg_res`.`res_news_sub` AS rns
				WHERE `news_sub_id` IN (:news_sub_ids)".$sysCondition,
				array(
						
						'news_sub_ids'=>(int)$news_sub_ids
	
				)
		)->toList();	
		return $result;
	}	*/

	/**
	 * 
	 * @param String $InfoUrlPrefix
	 * @param String $news_ids
	 * @return multitype:
	 */
	public static function  showNewsInfo($InfoUrlPrefix, $news_ids,$sysCondition='') {
		if($sysCondition!='')
			$sysCondition=" AND ".$sysCondition;
		
				$sql=sprintf("SELECT 
				  `news_id`,
				  `category_id`,
				  `title`,
				  `link`,
				  `logo`,
				  `logo` AS thumb,
				  `brief`,
				  CONCAT('%s', `news_id`) AS `url` 
				FROM
				  `skyg_res`.`res_news` AS rn
				WHERE `news_id` IN (%s) %s",$InfoUrlPrefix, $news_ids,$sysCondition);
		$result=parent::createSQL($sql)->toList();
	
		return $result;
	}
	
	/**
	 * 
	 * @param String $InfoUrlPrefix
	 * @param String $news_ids
	 * @return multitype:
	 */
	public static function  showNewsDetailInfo($InfoUrlPrefix, $news_ids,$sysCondition='') {
		if($sysCondition!='')
			$sysCondition=' AND '.$sysCondition;
		
		$sql=sprintf("SELECT 
				  rn.`news_id`,
				  rn.`category_id`,
				  rn.`title`,
				  rn.`link`,
				  rn.`logo`,
				  rn.`logo` AS thumb,
				  rn.`brief`,
				  CONCAT( '%s', rn.`news_id`) AS `url`,
				  rnd.`detail` 
				FROM
				  `skyg_res`.`res_news` AS rn 
				  LEFT JOIN `skyg_res`.`res_news_detail` AS rnd 
				    ON rn.`news_id` = rnd.`news_id` 
				WHERE rn.`news_id` IN (%s) %s",$InfoUrlPrefix,$news_ids,$sysCondition);				
		$result=parent::createSQL($sql)->toList();		
		return $result;
	}
	
	/**
	 * 
	 * @param String $what
	 * @return multitype:
	 */
	public static function getPromise($what)
	{
		$result=parent::createSQL(
				'SELECT 
				  `promise_key`,
				  `promise_value`
				FROM
				  `skyg_base`.`base_promise` 
				WHERE `promise_type` = :what ',
				array(
						'what'=>$what
	
				)
		)->toList();	
		return $result;
	}
	
	
	/**
	 * 
	 * @param String $conditionString
	 * @return Int count
	 */
	public static function listInfoCount($arr_promise,$condition,$sysCondition='') {
		$andCondArr=array();		
		foreach ($condition as $k=>$v) {
			if ($k == 'categoryid'){
				$andCondArr[] = "`category_id`=$v";
			} else if ($k == 'createdate') {
				$ctime = explode("#", $v);
				if(count($ctime) > 1){
					$unixtime_s = strtotime($ctime[0]);
					$unixtime_e = strtotime($ctime[1]);
					$andCondArr[] = " `create_time` BETWEEN $unixtime_s and $unixtime_e ";
				}else{
					$unixtime = strtotime($v);
					$andCondArr[] = " `create_time` = $unixtime ";
				}
			} else if($k == 'category') {
				foreach ($arr_promise as $promises){
					if($promises['promise_key'] == $v){
						$andCondArr[] = "`category_id`=".$promises['promise_value'];
						break;
					}
				}
			}
		}
		$con=implode(" and ", $andCondArr);
		$sql = "select count(*) from `skyg_res`.`res_news` AS rn";
		if ($con != ''){
			$sql .= " where $con ";
		}
		if($sysCondition!='')
			$sql .=" AND $sysCondition";
		$result=parent::createSQL($sql)->toValue();	
		return $result;
	}
	
	
	/**
	 * @param String $InfoUrlPrefix
	 * @param String $conditionString
	 * @param String $page
	 * @param String $pagesize
	 * @return multitype:
	 */
	public static function listInfoDetail($InfoUrlPrefix,$arr_promise,$condition,$page,$pagesize,$sysCondition='') {
		$start = $page*$pagesize;
		$andCondArr=array();;
		foreach ($condition as $k=>$v) {
			if ($k == 'categoryid'){
				$andCondArr[] = "`category_id`=$v";
			} else if ($k == 'createdate') {				
				$ctime = explode("#", $v);
				if(count($ctime) > 1){
					$unixtime_s = strtotime($ctime[0]);
					$unixtime_e = strtotime($ctime[1]);
					$andCondArr[] = " `create_time` BETWEEN $unixtime_s and $unixtime_e ";
				}else{
					$unixtime = strtotime($v);
					$andCondArr[] = " `create_time` = $unixtime ";
				}
			} else if($k == 'category') {
				foreach ($arr_promise as $promises){
						if($promises['promise_key'] == $v){
							$andCondArr[] = "`category_id`=".$promises['promise_value'];
							break;
						}
					}
			}
		}
		$con=implode(" and ", $andCondArr);
		
		$sql = 'SELECT 
				  `news_id`,
				  `category_id`,
				  `title`,
				  `link`,
				  `logo`,
				  `logo` AS thumb,
				  `brief`,
				  CONCAT(:InfoUrlPrefix, `news_id`) AS `url`,
				  `level`,
				  IFNULL(r.`sequence`, 0) AS `rindex` 
				FROM
				  `skyg_res`.`res_news` AS rn 
				  LEFT JOIN 
				    (SELECT 
				      `source_id`,
				      `sequence` 
				    FROM
				      `skyg_res`.`res_top` AS rt 
				    WHERE rt.`source_type` = 5 
				    GROUP BY rt.`source_id`) AS r 
				    ON r.`source_id` = rn.`news_id` ';
	
		if ($con != ''){
			$sql .= " where $con ";
		}
		if($sysCondition!='')
			$sql .=" AND $sysCondition ";
		$sql .= ' order by `sequence` desc,`create_time` desc,`news_id` limit :start,:pagesize';
		$result=parent::createSQL($sql,
				array(
						'InfoUrlPrefix'=>$InfoUrlPrefix,
						'start'=>(int)$start,
						'pagesize'=>(int)$pagesize
				)
		)->toList();
		return $result;
	}
	
	/**
	 * 
	 * @param Int $parentId
	 * @param Int $page
	 * @param Int $pagesize
	 * @return 
	 */
	
	/*
	public static function listSegment($parentId,$page,$pagesize,$sysCondition='') {
		$start = $page*$pagesize;
		if($sysCondition!='')
			$sysCondition =' AND '.$sysCondition;
		$sql = "SELECT 
				  `news_sub_id`,
				  `parent`,
				  `index`,
				  `title`,
				  `brief`,
				  `logo`,
				  `ispic`,
				  `createtime`,
				  `link`,
				  `level` 
				FROM
				  `skyg_res`.`res_news_sub`  as rns
				WHERE `parent` =%d ".$sysCondition." LIMIT %d,%d";
		$sql=sprintf($sql,$parentId,$start,$pagesize);
		$result=parent::createSQL($sql)->toList();
		return $result;
	}
	*/
	/**
	 *
	 * @param string $v_url   url地址
	 * @return multitype:
	 */
	public static function listtopinfo($v_url,$start=0,$pageSize=50,$sysCondition=''){
		if($sysCondition!='')
			$sysCondition =' WHERE '.$sysCondition;
		
		$sql="SELECT `news_id`,
					`category_id`,
					`title`,
					`link`,
					`logo`,
					`logo` AS thumb,
					`brief`,
					CONCAT('".$v_url."',`news_id`) AS `url`
					FROM `skyg_res`.`res_news` AS rn ".$sysCondition." LIMIT %d,%d";
		$sql=sprintf($sql,$start,$pageSize);
		return parent::createSQL($sql)->toList();
	}
	
	

}