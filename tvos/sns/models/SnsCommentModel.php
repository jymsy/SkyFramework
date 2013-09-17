<?php
namespace sns\models;
/**
 * @property  int          comment_id        评论id                                                                                                                      
 * @property  int          publish_id        资源ID                                                                                                                      
 * @property  int          comment_count     评论次数                                                                                                                  
 * @property  int          comment_flag      评论内容状态，0为有效，1为失效                                                                                  
 * @property  string       created_date      创建时间                                                                                                                  
 * @property  string       last_update_date  最后评论时间                     
 * 
 * @author xiaokeming
 */

class SnsCommentModel extends \Sky\db\ActiveRecord{
	/**
	 *@return SnsCommentModel
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}

	protected static $tableName="skyg_sns.sns_comment";
	protected static $primeKey=array("comment_id");
	
	/**
	 * 
	 * @param int $publishid     资源id
	 * @param int $commenttype   评论类型
	 * @return number            插入成功返回大于0的数，插入失败返回0
	 */
	public static function insertComment($publishid){
	   $par=parent::createSQL("insert into `skyg_sns`.`sns_comment`(
								   		`publish_id`,
								   		`comment_count`,
								   		`comment_flag`,
								   		`created_date`)
								 values(:v_publishid,
								   		1,
								   		0,
								   		CURRENT_TIMESTAMP
								   		)",
							   		array( "v_publishid"=>(int)$publishid)
	   		);
	   if($par->exec()){
	   	$par->getPdoInstance();
	   	return $par->lastInsertID();
	   }else{
	   	return 0;
	   }
	}
	
	
	/**
	 * 
	 * @param int    $commentid      评论表ID
	 * @param int    $userid         用户ID
	 * @param string $commentcontent 评论具体内容 
	 * @return number                插入成功返回大于0的数，插入失败返回0
	 */
	public static function insertCommentDetail($commentid,$userid,$username,$commentcontent){
		$par=parent::createSQL("insert into `skyg_sns`.`sns_comment_detail`(
								   		`comment_id`,
								   		`user_id`,
				                        `user_name`,
								   		`comment_content`,
								   		`comment_flag`)
								 values(:v_commentid,
								   		:v_userid,
				                        :v_username,
				                        :v_commentcontent,
				                        ''
								   		)",
				array(  "v_commentid"=>(int)$commentid,
						"v_userid"=>(int)$userid,
						"v_username"=>$username,
						"v_commentcontent"=>$commentcontent)
		);
		if($par->exec()){
			$par->getPdoInstance();
			return $par->lastInsertID();
		}else{
			return 0;
		}
	}
	
	/**
	 *
	 * @param int $publishid        资源id
	 * @param int $commenttype      评论类型
	 * @param string $syscondition  策略控制条件
	 * @return multitype:
	 */
	public static function queryComment($publishid,$syscondition){
		if ($syscondition!=''){
			$v_sql=' and '.$syscondition;
		}else{
			$v_sql='';
		}
		return parent::createSQL("select scm.`comment_id`,
				                         scm.`comment_count`
				                    from `skyg_sns`.`sns_comment` AS scm
				                   where scm.`publish_id`=:v_publishid
				                     and scm.`comment_flag`=0".$v_sql,
				array( "v_publishid"=>(int)$publishid
				)
		)->toList();
		

	}
	
	/**
	 * 
	 * @param int $commentid    评论表id
	 * @return number           更新成功返回大于0的数，更新失败返回0
	 */
	public static function updateComment($commentid){
		return parent::createSQL("update `skyg_sns`.`sns_comment` AS scm
				              set scm.`comment_count`=scm.`comment_count`+1
				            where scm.`comment_id`=:v_commentid",
				array(  "v_commentid"=>(int)$commentid)
		)->exec();
		
	}
	
	
	
	/**
	 * 
	 * @param  int $commentid       评论表id
	 * @param string $syscondition  策略控制条件
	 * @return multitype:          
	 */
	public static function queryCommentDetailByCid($commentid,$syscondition){
		if ($syscondition!=''){
			$v_sql=' and '.$syscondition;
		}else{
			$v_sql='';
		}
		return parent::createSQL("select scmd.`comment_detail_id`,
				                         scmd.`comment_id`,
				                         scmd.`user_id`,
				                         scmd.`user_name`,
				                         scmd.`comment_content`,
				                         scmd.`comment_flag`,
				                         scmd.`created_date`
				                    from `skyg_sns`.`sns_comment_detail` AS scmd
				                   where scmd.`comment_id`=:v_commentid
				                     and scmd.`comment_flag`=0".$v_sql." ORDER BY scmd.`created_date` DESC",
				array( "v_commentid"=>(int)$commentid
				)
		)->toList();
	}
	

	/**
	 *
	 * @param  int $userid          用户id
	 * @param string $syscondition  策略控制条件
	 * @return multitype:          
	 */
	public static function queryCommentDetailByUid($userid,$syscondition){
		if ($syscondition!=''){
			$v_sql=' and '.$syscondition;
		}else{
			$v_sql='';
		}
		return parent::createSQL("select scmd.`comment_detail_id`,
				                         scmd.`comment_id`,
				                         scmd.`user_id`,
				                         scmd.`user_name`,
				                         scmd.`comment_content`,
				                         scmd.`comment_flag`,
				                         scmd.`created_date`
				                    from `skyg_sns`.`sns_comment_detail` AS scmd
				                   where scmd.`user_id`=:v_userid
				                     and scmd.`comment_flag`=0".$v_sql." ORDER BY scmd.`created_date` DESC",
				array( "v_userid"=>(int)$userid
				)
		)->toList();
	}
	
	/**
	 * 
	 * @param int $commentdetailid    评论信息表id
	 * @param int $commentflag        评论信息状态
	 * @return number
	 */
	public static function updateCommentDetail($commentdetailid,$commentflag){
		return parent::createSQL("update `skyg_sns`.`sns_comment_detail` AS scmd
				                     set scmd.`comment_flag`=:v_commentflag
				                   where scmd.`comment_detail_id`=:v_commentdetailid",
				array( "v_commentdetailid"=>(int)$commentdetailid,
					   "v_commentflag"=>(int)$commentflag
				      )
		)->exec();
	}
	
	/**
	 *
	 * @param string $uid       拼接好后的UID字符串，用于查询各UID的评论数
	 * @return multitype:
	 */
	public static function getCommentTotalByUid($uid){
		return parent::createSQL("select scmd.`user_id`,
	       		                             count(1) AS `commenttotal`
	       		                       from `skyg_sns`.`sns_comment_detail` AS scmd
	       		                      where scmd.`user_id` in (".$uid.")
	       		                        and scmd.`comment_flag`= 0
	       		                      group by scmd.`user_id`"
		)->toList();
	}
}