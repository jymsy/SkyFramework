<?php
namespace res\models;
/**
 * @property  int          website_id   自增ID        
 * @property  string       site_name    网站名称    
 * @property  string       site_url     网站地址    
 * @property  string       site_logo    网站ICO       
 * @property  string       category_id  网站分类ID                    
 * 
 * @author xiaokeming
 */

class WebModel extends \Sky\db\ActiveRecord{
	/**
	 *@return WebModel
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}

	protected static $tableName="skyg_res.res_website";
	protected static $primeKey=array("website_id");
	
	/**
	 * 
	 * @param array $wsid           website表主键ID
	 * @param string $syscondition  策略控制条件
	 */
	public static function showweb(array $wsid,$syscondition){
		if ($syscondition!=''){
			$v_sql=' and '.$syscondition;
		}else{
			$v_sql='';
		}
		$wsid="'".implode("','", $wsid)."'";
		return parent::createSQL(
				"SELECT
				      rw.`website_id` AS id,
					  rw.`site_url` AS url,
					  rw.`site_name` AS `title`,
					  rw.`site_logo` AS `thumb`,
				      rw.`site_big_logo`,
					  rw.`category_id` AS category
					FROM
					  `skyg_res`.`res_website` AS rw
					WHERE rw.`website_id` IN (".$wsid.")".$v_sql
		)->toList();
	}
	
	
	/**
	 * 
	 * @param array $v_category_id  分类ID
	 * @param string $syscondition  策略控制条件
	 */
	public static function listwebcount(array $v_category_id,$syscondition){
		if ($syscondition!=''){
			$v_sql=' and '.$syscondition;
		}else{
			$v_sql='';
		}
		$v_category_id="'".implode("','", $v_category_id)."'";
		return parent::createSQL(
				"SELECT
					   COUNT(*)
				   FROM
					   `skyg_res`.`res_website` AS rw
				  WHERE rw.`category_id` in (".$v_category_id.")".$v_sql
		)->toValue();
	}
	
	/**
	 * 
	 * @param array $v_category_id  分类ID
	 * @param string $syscondition  策略控制条件
	 * @param int   $page
	 * @param int   $pagesize
	 */
	public static function listweb(array $v_category_id,$syscondition,$page,$pagesize){
		$start = $page*$pagesize;
		if ($syscondition!=''){
			$v_sql=' and '.$syscondition;
		}else{
			$v_sql='';
		}
		$v_category_id="'".implode("','", $v_category_id)."'";
		return parent::createSQL(
				"SELECT
				rw.`website_id` AS id,
				rw.`site_url` AS url,
				rw.`site_name` AS `title`,
				rw.`site_logo` AS `thumb`,
				rw.`site_big_logo`,
				rw.`category_id` AS category
				FROM
				`skyg_res`.`res_website` AS rw
				WHERE rw.`category_id` IN (".$v_category_id.")".$v_sql."
				ORDER BY rw.`website_id`
				LIMIT :start, :pagesize ",
				array(  'start'=>(int)$start,
						'pagesize'=>(int)$pagesize
				)
		)->toList();
	}
	
}