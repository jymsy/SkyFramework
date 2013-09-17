<?php
namespace res\models;
/**
* @author xiaokeming
*/

class FilterModel extends \Sky\db\ActiveRecord{
	/**
	 *@return FilterModel
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}
    
	/**
	 * 
	 * @param int $cid              分类ID
	 * @return multitype:
	 * @param string $syscondition  策略控制条件
	 */
	public static function queryenumsbycid($cid,$syscondition){
		if ($syscondition!=''){
			$v_sql=' and '.$syscondition;
		}else{
			$v_sql='';
		}
		return parent::createSQL("select rae.`category_id` AS `categoryid`,
				                         rae.`attribute`,
				                         rae.`enums`,
				                         rae.`attributename`,
				                         rae.`sequence` AS `sort` 
				                    from `skyg_res`.`res_attribute_enums` AS rae
				                   where rae.`category_id`=:cid ".$v_sql."
				                   order by rae.`sequence`",
				                array( "cid"=>(int)$cid
				                      )
				)->toList();
	}
	
	/**
	 * 
	 * @param int $cid              分类ID
	 * @param string $syscondition  策略控制条件
	 * @return multitype:
	 */
	public static function queryenumsbypid($cid,$syscondition){
		if ($syscondition!=''){
			$v_sql=' and '.$syscondition;
		}else{
			$v_sql='';
		}
		return parent::createSQL("select rae.`category_id` AS `categoryid`,
				                         rae.`attribute`,
				                         rae.`enums`,
				                         rae.`attributename`,
				                         rae.`sequence` AS `sort`
				                    from `skyg_res`.`res_attribute_enums` AS rae
				                   where rae.`category_id`=(select rca.`parent` 
				                                          from `skyg_res`.`res_category` AS rca
				                                         where rca.`category_id` = :cid)".$v_sql."
				                   order by rae.`sequence`",
				array( "cid"=>(int)$cid
				)
		)->toList();
	}
	
	
}