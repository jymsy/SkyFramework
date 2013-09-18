<?php
namespace resource\models;

use Sky\db\DBCommand;
use skyosadmin\components\PublicModel;

/**table skyg_res.res_hotword
 * @property  string       key          热词          
 * @property  int          type         类型          
 * @property  int          num          搜索次数 
 * 
 * @author Zhengyun
 */
class HotWordModel extends \Sky\db\ActiveRecord{
	/**
	 *@return HotWordModel
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}	
	
	
	/*
	public static function getNewResourceLists($pageindex,$pagesize) {
		$start = $pagesize*$pageindex;
		$list= parent::createSQL(
				'SELECT 
				  rv.`v_id`,
				  rv.`title`,
				  rvs.`source` AS `from`,
				  rvu.`url` AS `url` 
				FROM
				  `skyg_res`.`res_video` AS rv,
				  `skyg_res`.`res_video_site` AS rvs,
				  `skyg_res`.`res_video_url` AS rvu 
				WHERE rv.`v_id` = rvs.`v_id` 
					  AND rvu.`vs_id` = rvs.`vs_id` 
					  AND rvs.`source` IN ("sohu", "qiyi", "qq", "youku") 
					  AND rvu.`collection` = 1 
					  AND rv.`expired` = 2 
				ORDER BY rv.`v_id` DESC 
				LIMIT :start, :pagesize',
				array(
						'start'=>(int)$start,
						'pagesize'=>(int)$pagesize
						)
				)->toList();
		return $list;
	}
	
	public static function getNewResourceCount(){
		$count = parent::createSQL(
				'SELECT 
					  COUNT(*) 
					FROM
					  `skyg_res`.`res_video` AS rv,
					  `skyg_res`.`res_video_site` AS rvs,
					  `skyg_res`.`res_video_url` AS rvu 
					WHERE rv.`v_id` = rvs.`v_id` 
					  AND rvu.`vs_id` = rvs.`vs_id` 
					  AND rvs.`source` IN ("sohu", "qiyi", "qq", "youku") 
					  AND rvu.`collection` = 1 
					  AND rv.`expired` = 2 '
				)->toValue();
		return $count;
	}*/
	
	/**将video设置为失效
	 * 
	 * @param Int $vid
	 * @return number
	 */	
	public static function setExpiredVideo($vid){
		return parent::createSQL(
				"UPDATE 
				  `skyg_res`.`res_video` 
				SET
				  `expired` = 1 
				WHERE `v_id` = :vid ",
				array('vid'=>(int)$vid)
				)->exec();
	}
	
	/**video上线
	 * 
	 * @param Int $vid
	 * @return number
	 */
	public static function setOnlineVideo($vid){
		return parent::createSQL(
				"UPDATE 
				  `skyg_res`.`res_video` 
				SET
				  `expired` = 0 
				WHERE `v_id` = :vid ",
				array('vid'=>(int)$vid)
				)->exec();
	}
	
	/**获取热词列表
	 * 
	 * @param Int $pageindex
	 * @param Int $pagesize
	 * @param array $orderCondition
	 * @return multitype:
	 */
	public static function get_hot_word_lists($pageindex,$pagesize,$orderCondition=array('num'=>'DESC')) {
		$start = $pagesize*($pageindex-1);
		$orderString=PublicModel::controlArray($orderCondition);
		$sql=sprintf("SELECT
						`key`,
						`type`,
						`num`
					FROM
						`skyg_res`.`res_hotword`
					ORDER BY %s 
					LIMIT %d, %d",
				$orderString,$start,$pagesize);
		
		$list = parent::createSQL($sql)->toList();				
		return $list;
	}
	
	/**获取热词数量
	 * 
	 * @return Ambigous <NULL, unknown>
	 */
	public static function getHotWordCount() {	
		$count = parent::createSQL('SELECT count(*) FROM `skyg_res`.`res_hotword`')->toValue();
		return $count;
	}
	
	/**删除热词
	 * 
	 * @param unknown_type $key
	 * @return number
	 */
	public static function delHotword($key){
		return parent::createSQL(
				"DELETE 
				FROM
				  `skyg_res`.`res_hotword` 
				WHERE `key` = :key ",
				array('key'=>$key)
				)->exec();
	}
	
	/**更新热词
	 * 
	 * @param String $key
	 * @param Int $num
	 * @return number
	 */
	public static function updateHotword($key,$num){	
		return parent::createSQL(
				"UPDATE 
				  `skyg_res`.`res_hotword` 
				SET
				  `num` = :num 
				WHERE `key` =:key",
				array(
						'num'=>$num,
						'key'=>$key)
				)->exec();
	}
	
	/**搜索热词
	 * 
	 * @param String $key
	 * @param Int $pageindex
	 * @param Int $pagesize
	 * @param array $orderCondition
	 * @return multitype:
	 */
	public static function searchHotwordLists($key,$pageindex,$pagesize,$orderCondition=array('num'=>'DESC')){
		$start = $pagesize*($pageindex-1);
		$orderString=PublicModel::controlArray($orderCondition);
		$sql="SELECT 
				  `key`,`type`,`num` 
				FROM
				  `skyg_res`.`res_hotword` 
				WHERE `key` LIKE '%".addslashes($key)."%' 
				ORDER BY $orderString  
				LIMIT $start, $pagesize ";
		
		$list = parent::createSQL($sql)->toList();	
		return $list;
	}
	
	/**搜索热词数量
	 * 
	 * @param String $key
	 * @return Ambigous <NULL, unknown>
	 */
	public static function searchHotwordCount($key){
		$sql='SELECT count(*) FROM `skyg_res`.`res_hotword` where `key` like "%'.addslashes($key).'%"';
		$count = parent::createSQL($sql)->toValue();		
		return $count;
	}
	
	/**添加热词
	 * 
	 * @param String $key
	 * @param Int $num
	 * @return number
	 */
	public static function addHotword($key,$num){
		$sqlFormat = "INSERT INTO `skyg_res`.`res_hotword` (`key`, `type`, `num`) VALUES ('%s', 0, %d)";
		$sql=sprintf($sqlFormat,addslashes($key),$num);
		return parent::createSQL($sql)->exec();
	}    
      
	
	
}