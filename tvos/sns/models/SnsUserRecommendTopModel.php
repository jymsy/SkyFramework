<?php
namespace sns\models;
/**
 * @property  int          recommend_id    推荐ID                                
 * @property  int          user_id         用户ID                                
 * @property  int          sequence        排序号                               
 * @property  int          recommend_type  推荐类型：1为分享，2为评论，3为收集，4为顶踩                            
 * @property  int          recommend_flag  推荐状态，0为有效，1为失效  
 * @property  string       created_date    创建时间                                                                                                                                                            
 * 
 * @author xiaokeming
 */

class SnsUserRecommendTopModel extends \Sky\db\ActiveRecord{
	/**
	 *@return SnsUserRecommendTopModel
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}
	
	protected static $tableName="skyg_sns.sns_user_recommend_top";
	protected static $primeKey=array("recommend_id");
	
	
	/**
	 * 
	 * @param int $v_uid       用户ID
	 * @param int $v_seq       排序号
	 * @param int $v_type      推荐分类
	 * @param int $v_flag      推荐状态
	 * @return number          插入成功返回大于0，反之等于0
	 */
	public static function insertRecommendTop($v_uid,$v_seq,$v_type,$v_flag){
	   return parent::createSQL("insert into `skyg_sns`.`sns_user_recommend_top`(
	   		                                 `user_id`,
	   		                                 `sequence`,
	   		                                 `recommend_type`,
	   		                                 `recommend_flag`)
	   		                          values(:v_uid,
	   		                                 :v_seq,
	   		                                 :v_type,
	   		                                 :v_flag
	   		                                )",
	   		                     array("v_uid"=>(int)$v_uid,
	   		                     	   "v_seq"=>(int)$v_seq,
	   		                     	   "v_type"=>(int)$v_type,
	   		                     	   "v_flag"=>(int)$v_flag
	   		                     		))->exec();
	
	}
	
	/**
	 * 
	 * @param int $v_topcount   需要返回的TOP行数
	 * @return multitype:
	 */
	public static function getUserShareTop($v_topcount){
		return parent::createSQL("SELECT 
									  ssd.`from_user_id`,
									  COUNT(1) 
									FROM
									  `skyg_sns`.`sns_share_detail` AS ssd
									WHERE ssd.`share_flag` = 0 
									GROUP BY ssd.`from_user_id` 
									ORDER BY COUNT(1) DESC 
									LIMIT :v_topcount ",
				             array( "v_topcount"=>(int)$v_topcount
				                   ))->toList();
	}
	
	/**
	 * 
	 * @param int $v_type         推荐分类
	 * @param int $v_uid          用户ID
	 * @return number             更新成功返回大于值0，反之等于0
	 */
	public static function updateRecommendTop($v_type,$v_uid=""){
		if ($v_uid!=""){
			$u_sql=" and surt.`user_id`='".$v_uid."'";
		}else{
			$u_sql="";
		}
		
		return parent::createSQL("update `skyg_sns`.`sns_user_recommend_top` AS surt
				                     set surt.`recommend_flag`=1
				                   where surt.`recommend_type`=:v_type
				                     and surt.`recommend_flag`=0".$u_sql,
				                 array("v_type"=>(int)$v_type
				                 		))->exec();
			
	}
	
	/**
	 * 
	 * @param int $v_type       推荐分类
	 * @return multitype:       
	 */
	public static function getUserIdByType($v_type){
		return parent::createSQL("select surt.`user_id`
				                    from `skyg_sns`.`sns_user_recommend_top` AS surt
				                   where surt.`recommend_type`=:v_type
				                     and surt.`recommend_flag`=0",
				                 array("v_type"=>(int)$v_type
				                 		))->toList();
	}
}