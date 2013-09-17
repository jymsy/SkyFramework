<?php
namespace tvos\autorun\models;
/**            
 * 
 * @author xiaokeming
 */

class UpdateCategoryModel extends \Sky\db\ActiveRecord{
	/**
	 *@return UpdateCategoryModel
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}
	
	/**
	 *
	 * @param int $pid              父节点ID
	 * @param string $syscondition  策略控制条件
	 */
	public static function querycategoryinfo($pid,$syscondition){
		
		if ($syscondition!=''){
			$v_sql=' and '.$syscondition;
		}else{
			$v_sql='';
		}
		return parent::createSQL("SELECT
					  rca.`category_id`,
					  rca.`category_name`
					FROM
					  `skyg_res`.`res_category` AS rca
					WHERE rca.`parent` = :pid ".$v_sql,
				array("pid"=>(int)$pid))->toList();
	}
	
	/**
	 *
	 * @param int $v_total        子节点数量
	 * @param int $v_categoryid   分类ID
	 * @return number             修改成功返回值大于0反之等于0
	 */
	public static function modifychildsnum($v_total,$v_categoryid){
		return parent::createSQL("UPDATE `skyg_res`.`res_category` 
									SET
									  `childs_num` = :v_total
									WHERE `category_id` = :v_categoryid ",
				                array( "v_total"=>(int)$v_total,
				                	   "v_categoryid"=>(int)$v_categoryid))->exec();
		
	}
	
	/**
	 *
	 * @param string $v_picUrl      图片地址
	 * @param int    $v_categoryid  分类id
	 * @return number               修改成功返回值大于0反之等于0
	 */
	public static function modifycategorylogo($v_picUrl,$v_categoryid){
		return parent::createSQL("UPDATE `skyg_res`.`res_category`
									SET
									  `big_logo` = '".$v_picUrl."'
									WHERE `category_id` = :v_categoryid ",
				                 array("v_categoryid"=>(int)$v_categoryid))->exec();
	}
	
	/**
	 *
	 * @param string $v_source      拼接后的SOURCE字符串
	 * @param string $v_newcat      分类
	 * @param string $v_now         建创时间
	 * @param string $syscondition  策略控制条件
	 *
	 */
	public static function totalvideosite($syscondition,$v_newcat,$v_now){
		
		if ($syscondition!=''){
			$v_sql=' and '.$syscondition;
		}else{
			$v_sql='';
		}
		return parent::createSQL("SELECT
									    COUNT(*)
									FROM
									  `skyg_res`.`res_video_site` AS rvs
									  LEFT JOIN `skyg_res`.`res_video` AS rv
									    ON rv.`v_id` = rvs.`v_id`
									WHERE rv.`expired` = 0
									  AND rv.`category` = '".$v_newcat."'
									  AND rv.`created_date` > '".$v_now."'".$v_sql."
									GROUP BY rvs.`v_id` ")->toValue();
	}
	
	/**
	 *
	 * @param int      $v_categoryid   分类ID
	 * @param string   $v_now          创建时间
	 * @param string $syscondition     策略控制条件
	 *
	 */
	public static function totalmusictop($v_categoryid,$v_now,$syscondition){
		
		if ($syscondition!=''){
			$v_sql=' and '.$syscondition;
		}else{
			$v_sql='';
		}
		return parent::createSQL("SELECT
									    COUNT(*)
									FROM
									  `skyg_res`.`res_music_top` AS rmt
									WHERE rmt.`expired` = 0
									  AND rmt.`category_id` = :v_categoryid
									  AND rmt.`created_date` > '".$v_now."' ".$v_sql, 
				                array("v_categoryid"=>(int)$v_categoryid))->toValue();
		
	}
	
	/**
	 *
	 * @param int $v_total       修改的子节点数量
	 * @param int $v_categoryid  分类ID
	 * @return number            修改成功返回值大于0反之等于0
	 */
	public static function modifychildsupdatenum($v_total,$v_categoryid){

		return parent::createSQL("UPDATE `skyg_res`.`res_category`
									SET
									  `childs_update_num` = :v_total
									WHERE `category_id` = :v_categoryid ",
							array("v_total"=>(int)$v_total,
								  "v_categoryid"=>(int)$v_categoryid))->exec();
		
	}
}