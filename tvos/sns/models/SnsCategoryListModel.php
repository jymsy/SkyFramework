<?php
namespace sns\models;
/**
 * @property  int          category_id       分类ID                                
 * @property  string       category_name     分类名                               
 * @property  string       type              类型                                  
 * @property  int          sequence          分类排序                            
 * @property  int          category_flag     分类状态，0为有效，1为失效  
 * @property  string       created_date      创建时间                            
 * @property  string       last_update_date  最后修改时间                                                                                                                                                                          
 * 
 * @author xiaokeming
 */

class SnsCategoryListModel extends \Sky\db\ActiveRecord{
	/**
	 *@return SnsCategoryListModel
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}

	protected static $tableName="skyg_sns.sns_categorylist_conf";
	protected static $primeKey=array("category_id");
	
	/**
	 * 返回菜单分类列表
	 * @return multitype:
	 */
	public static function showCategoryList(){
	      return parent::createSQL("select scc.`category_id`,
	      		                           scc.`category_name`,
	      		                           scc.`category_type`,
	      		                           scc.`sequence`
	      		                      from `skyg_sns`.`sns_categorylist_conf` AS scc
	      		                     where scc.`category_flag`=0
	      		                     order by scc.`sequence`")->toList();
	}
	
	
	
	
	/**
	 * 
	 * @param string $cname                 分类名
	 * @return Ambigous <NULL, unknown>
	 */
	public static function showCategoryByName($cname){
	
		return parent::createSQL("select count(1)
	      		                    from `skyg_sns`.`sns_categorylist_conf` AS scc
	      		                   where scc.`category_name`=:cname",
				array( "cname"=>$cname
				))->toValue();
	}
	/**
	 * 
	 * @param int    $cid          分类ID 
	 * @param string $cname        分类名
	 * @param string $ctype        类型 
	 * @param int    $cseq         排序号
	 * @param int    $cflag        是否有效标识
	 * @return number              修改成功返回大于0的数，失败返回0
	 */
	public static function updateCategory($cid,$cname,$ctype,$cseq,$cflag){
         parent::createSQL("update `skyg_sns`.`sns_categorylist_conf` AS scc
         		                    set scc.`category_name`='".addslashes($cname)."',
         		                        scc.`category_type`='".addslashes($ctype)."',
         		                        scc.`sequence`='".addslashes($cseq)."',
         		                        scc.`category_flag`='".addslashes($cflag)."'
         		                  where scc.`category_id`=:cid",
         		              array( "cid"=>(int)$cid
         		              		 )
         		)->exec();
        
	}
	
	/**
	 *
	 * @param string $cname      分类名
	 * @param string $ctype      类型
	 * @param int $seq           排序号
	 * @return number            插入成功返回大于0的数，失败返回0
	 */
	public static function insertCategoryList($cname,$ctype,$seq){
		$par=parent::createSQL("INSERT INTO `skyg_sns`.`sns_categorylist_conf`(
				                            `category_name`,
				                            `category_type`,
				                            `sequence`,
				                            `category_flag`,
				                            `created_date`,
				                            `last_update_date`)
				                     values('".addslashes($cname)."',
				                            '".addslashes($ctype)."',
				                            ".addslashes($seq).",
				                            0,
				                            '0000-00-00 00:00:00',
				                            CURRENT_TIMESTAMP)");
		if($par->exec()){
			$par->getPdoInstance();
			return $par->lastInsertID();
		}else{
			return 0;
		}
	}
	
	/**
	 *
	 * @param int $categoryid              分类ID
	 * @return Ambigous <NULL, unknown>
	 */
	public static function showCategoryById($categoryid){
	
		return parent::createSQL("select scc.`category_type`
	      		                    from `skyg_sns`.`sns_categorylist_conf` AS scc
	      		                   where scc.`category_id`=:categoryid",
				array( "categoryid"=>(int)$categoryid
				))->toValue();
	}
	
}