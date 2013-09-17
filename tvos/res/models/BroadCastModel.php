<?php
namespace res\models;
/**
 * @property  int          broadcast_id                  
 * @property  string       title                         
 * @property  string       url                           
 * @property  int          category_id                   
 * @property  string       thumb                         
 * @property  string       created_date                           
 *
 * @author xiaokeming
 */

class BroadCastModel extends \Sky\db\ActiveRecord{
	/**
	 *@return BroadCastModel
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}

	protected static $tableName="skyg_res.res_broadcast";
	protected static $primeKey=array("broadcast_id");
	
	/**
	 * @param arry $bids            广播表主键ID
	 * @param string $syscondition  策略控制条件
	 */
	public static function showbroadcast($bids,$syscondition){
		if ($syscondition!=''){
			$v_sql=' and '.$syscondition;
		}else{
			$v_sql='';
		}
		
		$bids="'".implode("','", $bids)."'";
		return parent::createSQL(
				"SELECT
					  rb.`broadcast_id` AS id,
					  rb.`title`,
					  rb.`url`,
					  rb.`category_id`,
					  rb.`thumb`
					FROM
					  `skyg_res`.`res_broadcast` AS rb
					WHERE rb.`broadcast_id` IN (".$bids.")".$v_sql
				
		)->toList();
		
	
	}
	
	/**
	 * 
	 * @param int $cid              分类ID
	 * @param string $syscondition  策略控制条件
	 */
	public static function listbroadcastcount($cid,$syscondition){
		if ($syscondition!=''){
			$v_sql=' and '.$syscondition;
		}else{
			$v_sql='';
		}
		return parent::createSQL(
				" SELECT
					  COUNT(*)
					FROM
					  `skyg_res`.`res_broadcast` AS rb
					WHERE rb.`category_id` = :cid ".$v_sql,
				array(
						"cid"=>(int)$cid
				)
		)->toValue();
		
		 
	}
	
	/**
	 * 
	 * @param int $cid              分类ID
	 * @param string $syscondition  策略控制条件
	 * @param int $page       
	 * @param int $pagesize
	 */
	public static function listbroadcast($cid,$syscondition,$page,$pagesize){
		
		if ($syscondition!=''){
			$v_sql=' and '.$syscondition;
		}else{
			$v_sql='';
		}
		$start = $page*$pagesize;
		return parent::createSQL(
				"SELECT
					  rb.`broadcast_id` AS id,
					  rb.`title`,
					  rb.`url`,
					  rb.`category_id`,
					  rb.`thumb`
					FROM
					  `skyg_res`.`res_broadcast` AS rb
					WHERE rb.`category_id`=:cid ".$v_sql."
				    LIMIT :start,:pagesize",
				array(
						"cid"=>(int)$cid,
						"start"=>(int)$start,
						"pagesize"=>(int)$pagesize
				)
		)->toList();
		
	
	}
}