<?php
namespace res\models;
/**
 * @property  int          category_id        分类id                                 
 * @property  string       category_name      名称                                   
 * @property  int          parent             父级分类                             
 * @property  string       path               树路径                                
 * @property  string       small_logo         小图                                   
 * @property  string       big_logo           大图                                   
 * @property  int          valid              是否失效(0为失效)                 
 * @property  int          final_node         是否为最后节点(1为最后节点)  
 * @property  int          sequence           显示排序                             
 * @property  int          childs_num         该分类资源总数                    
 * @property  int          childs_update_num  该分类资源更新数量                  
 * 
 * @author xiaokeming
 */

class CategoryModel extends \Sky\db\ActiveRecord{
	/**
	 *@return CategoryModel
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}

	protected static $tableName="skyg_res.res_category";
	protected static $primeKey=array("category_id");
	
	/**
	 * 
	 * @param string $v_category_name 分类名
	 * @param string $syscondition    策略控制条件
	 */
	public static function showcategorybycname($v_category_name,$syscondition){
		if ($syscondition!=''){
			$v_sql=' and '.$syscondition;
		}else{
			$v_sql='';
		}
		return parent::createSQL(
				"SELECT
					   rca.`category_id` AS id,
					   rca.`category_name` AS name,
					   rca.`parent`,
					   rca.`big_logo` AS logo,
					   rca.`small_logo` AS logo_s
				  FROM
					   `skyg_res`.`res_category` AS rca
				 WHERE rca.`category_name` = :v_category_name
				   AND rca.`parent` = 0 ".$v_sql,
				array(
						"v_category_name"=>$v_category_name
				)
		)->toList();
		
	
	}
	
	
	/**
	 *
	 * @param string $v_category_name 分类名
	 * @param string $syscondition    策略控制条件
	 */
	public static function showcategoryidbycname($v_category_name,$syscondition){
		if ($syscondition!=''){
			$v_sql=' and '.$syscondition;
		}else{
			$v_sql='';
		}
		return parent::createSQL(
				"SELECT
					   rca.`category_id` AS id
				  FROM
					   `skyg_res`.`res_category` AS rca
				 WHERE rca.`category_name` = :v_category_name
				   AND rca.`parent` = 0 ".$v_sql,
				array(
						"v_category_name"=>$v_category_name
				)
		)->toList();
	
	
	}
	
	/**
	 *
	 * @param int $cid              分类ID
	 * @param string $syscondition  策略控制条件
	 */
	public static function queryacategoryname($cid,$syscondition){
		if ($syscondition!=''){
			$v_sql=' and '.$syscondition;
		}else{
			$v_sql='';
		}
		return parent::createSQL(
				" select rca.`category_name` from `skyg_res`.`res_category` AS rca where rca.`category_id`=:cid".$v_sql,
				array(
						"cid"=>(int)$cid
				)
		)->toValue();
			
	}
	
	
	/**
	 * 
	 * @param int $cid              分类ID
	 * @param string $syscondition  策略控制条件
	 */
	public static function showcategorybycid($cid,$syscondition){
		if ($syscondition!=''){
			$v_sql=' and '.$syscondition;
		}else{
			$v_sql='';
		}
		return parent::createSQL(
				"SELECT
					  rca.`category_id` AS id,
					  rca.`category_name` AS name,
					  rca.`parent`,
					  rca.`big_logo` AS logo,
					  rca.`small_logo` AS logo_s
					FROM
					  `skyg_res`.`res_category` AS rca
					WHERE rca.`category_id` = :cid ".$v_sql,
				array(
						"cid"=>(int)$cid
				)
		)->toList();
		
	
	}
	
	/**
	 * 
	 * @param int $id               父类ID
	 * @param string $syscondition  策略控制条件
	 */
	public static function showcategorybyparentid($id,$syscondition){
		if ($syscondition!=''){
			$v_sql=' and '.$syscondition;
		}else{
			$v_sql='';
		}
		return parent::createSQL(
				"SELECT
					   rca.`category_id` AS id,
					   rca.`category_name` AS name,
					   rca.`big_logo` AS logo,
					   rca.`small_logo` AS logo_s
				   FROM
					   `skyg_res`.`res_category` AS rca
				 WHERE rca.`parent` = :id
				   AND rca.`valid` > 0".$v_sql."
				  ORDER BY rca.`category_id` DESC ",
				array(
						"id"=>(int)$id
				)
		)->toList();
		
	
	}
	
	/**
	 * 
	 * @param int $id               父类ID
	 * @param string $syscondition  策略控制条件
	 */
	public static function querycategorycount($id,$syscondition){
		if ($syscondition!=''){
			$v_sql=' and '.$syscondition;
		}else{
			$v_sql='';
		}
		return parent::createSQL(
				"SELECT
					  count(*)
				  FROM
					  `skyg_res`.`res_category` AS rca
				WHERE rca.`parent` = :id
				  AND rca.`valid` > 0 ".$v_sql,
				array(
						"id"=>(int)$id
				)
		)->toValue();
		
		 
	}
	
	/**
	 * 
	 * @param int $pid              父类ID
	 * @param string $syscondition  策略控制条件
	 * @param int $page 
	 * @param int $pagesize
	 */
	public static function querycategorylist($pid,$syscondition,$page,$pagesize){
		if ($syscondition!=''){
			$v_sql=' and '.$syscondition;
		}else{
			$v_sql='';
		}
		
		if($pagesize > 0){
			$start = $page*$pagesize;
		
		}
		return parent::createSQL(
				"SELECT
					  rca.`category_id` AS id,
					  rca.`category_name` AS name,
					  rca.`big_logo` AS logo,
					  rca.final_node AS final,
					  rct.tops AS activelogo,
					  rca.`childs_num` AS childsnum,
					  rca.`childs_update_num` AS updatenum,
				      rca.`action`
					FROM
					  `skyg_res`.`res_category` AS rca
					  LEFT JOIN `skyg_res`.`res_category_tops` AS rct
					    ON rca.category_id = rct.category_id
					WHERE rca.`parent` = :pid
					  AND rca.`valid` > 0".$v_sql."
					ORDER BY rca.`sequence`,rca.`category_id`
					LIMIT :v_start,:v_pagesize",
				array(
						"pid"=>(int)$pid,
						"v_start"=>(int)$start,
						"v_pagesize"=>(int)$pagesize
				)
		)->toList();
		
		 
	}
	
	/**
	 * 
	 * @param int $sid              video表主键ID
	 * @param string $syscondition  策略控制条件
	 * 
	 */
	public static function querycategorypath($sid,$syscondition){
		if ($syscondition!=''){
			$v_sql=' and '.$syscondition;
		}else{
			$v_sql='';
		}
		return parent::createSQL(
				"SELECT
					    rca.`path`
				   FROM
					    `skyg_res`.`res_category` AS rca
				  WHERE rca.`category_id` =
					    (SELECT
					           rv.`category_id`
					       FROM
					           `skyg_res`.`res_video` AS rv
					     WHERE rv.`v_id` = :sid limit 1)".$v_sql,
				array(
						 "sid"=>(int)$sid
				)
		)->toList();
	}
}