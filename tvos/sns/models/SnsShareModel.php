<?php
namespace sns\models;
/**
 * @property  int          share_id          分享id                                                                                                                      
 * @property  int          publish_id        资源ID                                                                                                                      
 * @property  int          share_count       分享次数                                                                                                                  
 * @property  int          share_flag        分享内容状态，0为有效，1为失效                                                                                  
 * @property  string       created_date      创建时间                                                                                                                  
 * @property  string       last_update_date  最后分享时间                    
 * 
 * @author xiaokeming
 */

class SnsShareModel extends \Sky\db\ActiveRecord{
	/**
	 *@return SnsShareModel
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}

	protected static $tableName="skyg_sns.sns_share";
	protected static $primeKey=array("share_id");
	
	/**
	 * 
	 * @param int $publishid     资源id
	 * @param int $sharetype     分享类型
	 * @return number            插入成功返回大于0的数，插入失败返回0
	 */
	public static function insertShare($publishid){
	   $par=parent::createSQL("insert into `skyg_sns`.`sns_share`(
								   		`publish_id`,
								   		`share_count`,
								   		`share_flag`,
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
	 * @param int    $shareid        分享表ID
	 * @param int    $userid         用户ID
	 * @param string $sharecontent   分享具体内容 
	 * @return number                插入成功返回大于0的数，插入失败返回0
	 */
	public static function insertShareDetail($shareid,$fuserid,$tuserid){
		$par=parent::createSQL("insert into `skyg_sns`.`sns_share_detail`(
								   		`share_id`,
								   		`from_user_id`,
				                        `to_user_id`,
								   		`share_flag`)
								 values(:v_shareid,
								   		:v_ruserid,
				                        :v_tuserid,
				                        0
								   		)",
				array(  "v_shareid"=>(int)$shareid,
						"v_ruserid"=>(int)$fuserid,
						"v_tuserid"=>(int)$tuserid)
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
	 * @param int $sharetype        分享类型
	 * @param string $syscondition  策略控制条件
	 * @return multitype:
	 */
	public static function queryShare($publishid,$syscondition){
		if ($syscondition!=''){
			$v_sql=' and '.$syscondition;
		}else{
			$v_sql='';
		}
		return parent::createSQL("select ss.`share_id`,
				                         ss.`share_count`
				                    from `skyg_sns`.`sns_share` AS ss
				                   where ss.`publish_id`=:v_publishid
				                     and ss.`share_flag`=0".$v_sql,
				array( "v_publishid"=>(int)$publishid
				)
		)->toList();
	}
	
	/**
	 * 
	 * @param int $shareid    分享表id
	 * @return number         更新成功返回大于0的数，更新失败返回0
	 */
	public static function updateShare($shareid){
		return parent::createSQL("update `skyg_sns`.`sns_share` AS ss
				              set ss.`share_count`=ss.`share_count`+1
				            where ss.`share_id`=:v_shareid",
				array(  "v_shareid"=>(int)$shareid)
		)->exec();
		
	}
	
	
	
	/**
	 * 
	 * @param  int $shareid         分享表id
	 * @param string $syscondition  策略控制条件
	 * @return multitype:          
	 */
	public static function queryShareDetailByCid($shareid,$syscondition){
		if ($syscondition!=''){
			$v_sql=' and '.$syscondition;
		}else{
			$v_sql='';
		}
		return parent::createSQL("select ssd.`share_detail_id`,
				                         ssd.`share_id`,
				                         ssd.`from_user_id`,
				                         ssd.`to_user_id`,
				                         ssd.`share_flag`,
				                         ssd.`created_date`
				                    from `skyg_sns`.`sns_share_detail` AS ssd
				                   where ssd.`share_id`=:v_shareid
				                     and ssd.`share_flag`=0".$v_sql,
				array( "v_shareid"=>(int)$shareid
				)
		)->toList();
	}
	

	/**
	 *
	 * @param  int $userid          用户id
	 * @param string $syscondition  策略控制条件
	 * @return multitype:          
	 */
	public static function queryShareDetailByUid($userid,$syscondition){
		if ($syscondition!=''){
			$v_sql=' and '.$syscondition;
		}else{
			$v_sql='';
		}
		return parent::createSQL("select ssd.`share_detail_id`,
				                         ssd.`share_id`,
				                         ssd.`from_user_id`,
				                         ssd.`to_user_id`,
				                         ssd.`share_flag`,
				                         ssd.`created_date`
				                    from `skyg_sns`.`sns_share_detail` AS ssd
				                   where ssd.`from_user_id`=:v_userid
				                     and ssd.`share_flag`=0".$v_sql,
				array( "v_userid"=>(int)$userid
				)
		)->toList();
	}
	
	/**
	 * 
	 * @param int $sharedetailid    分享信息表id
	 * @param int $shareflag        分享信息状态
	 * @return number
	 */
	public static function updateShareDetail($sharedetailid,$shareflag){
		return parent::createSQL("update `skyg_sns`.`sns_share_detail` AS ssd
				                     set ssd.`share_flag`=:v_shareflag
				                   where ssd.`share_detail_id`=:v_sharedetailid",
				array( "v_sharedetailid"=>(int)$sharedetailid,
					   "v_shareflag"=>(int)$shareflag
				      )
		)->exec();
	}
	
	/**
	 *
	 * @param string $uid       拼接好后的UID字符串，用于查询各UID的分享数
	 * @return multitype:
	 */
	public static function getShareTotalByUid($uid){
		return parent::createSQL("select ssd.`from_user_id` as `user_id`,
	       		                             count(1) AS `sharetotal`
	       		                       from `skyg_sns`.`sns_share_detail` AS ssd
	       		                      where ssd.`from_user_id` in (".$uid.")
	       		                        and ssd.`share_flag`= 0
	       		                      group by ssd.`from_user_id`"
		)->toList();
	}
}