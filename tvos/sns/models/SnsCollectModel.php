<?php
namespace sns\models;
/**
 * @property  int          collect_id          分享id                                                                                                                      
 * @property  int          publish_id           资源ID                                                                                                                      
 * @property  int          collect_count       分享次数                                                                                                                  
 * @property  int          collect_flag        分享内容状态，0为有效，1为失效                                                                                  
 * @property  string       created_date        创建时间                                                                                                                  
 * @property  string       last_update_date    最后分享时间                    
 * 
 * @author xiaokeming
 */

class SnsCollectModel extends \Sky\db\ActiveRecord{
	/**
	 *@return SnsCollectModel
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}

	protected static $tableName="skyg_sns.sns_collect";
	protected static $primeKey=array("collect_id");
	
	/**
	 * 
	 * @param int $publishid      资源id
	 * @param int $collecttype    分享类型
	 * @return number             插入成功返回大于0的数，插入失败返回0
	 */
	public static function insertCollect($publishid){
	   $par=parent::createSQL("insert into `skyg_sns`.`sns_collect`(
								   		`publish_id`,
								   		`collect_count`,
								   		`collect_flag`,
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
	 * @param int    $collectid        分享表ID
	 * @param int    $userid           用户ID
	 * @param string $collectcontent   分享具体内容 
	 * @return number                  插入成功返回大于0的数，插入失败返回0
	 */
	public static function insertCollectDetail($collectid,$userid){
		$par=parent::createSQL("insert into `skyg_sns`.`sns_collect_detail`(
								   		`collect_id`,
								   		`user_id`,
								   		`collect_flag`)
								 values(:v_collectid,
								   		:v_userid,
				                        0
								   		)",
				array(  "v_collectid"=>(int)$collectid,
						"v_userid"=>(int)$userid,)
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
	 * @param int $collecttype      分享类型
	 * @param string $syscondition  策略控制条件
	 * @return multitype:
	 */
	public static function queryCollect($publishid,$syscondition){
		if ($syscondition!=''){
			$v_sql=' and '.$syscondition;
		}else{
			$v_sql='';
		}
		return parent::createSQL("select scl.`collect_id`,
				                         scl.`collect_count`
				                    from `skyg_sns`.`sns_collect` AS scl
				                   where scl.`publish_id`=:v_publishid
				                     and scl.`collect_flag`=0".$v_sql,
				array( "v_publishid"=>(int)$publishid
				)
		)->toList();
	}
	
	/**
	 * 
	 * @param int $collectid    分享表id
	 * @return number           更新成功返回大于0的数，更新失败返回0
	 */
	public static function updateCollect($collectid){
		return parent::createSQL("update `skyg_sns`.`sns_collect` AS scl
				              set scl.`collect_count`=scl.`collect_count`+1
				            where scl.`collect_id`=:v_collectid",
				array(  "v_collectid"=>(int)$collectid)
		)->exec();
		
	}
	
	
	
	/**
	 * 
	 * @param  int $collectid       分享表id
	 * @param string $syscondition  策略控制条件
	 * @return multitype:          
	 */
	public static function queryCollectDetailByCid($collectid,$syscondition){
		if ($syscondition!=''){
			$v_sql=' and '.$syscondition;
		}else{
			$v_sql='';
		}
		return parent::createSQL("select scld.`collect_detail_id`,
				                         scld.`collect_id`,
				                         scld.`user_id`,
				                         scld.`collect_flag`,
				                         scld.`created_date`
				                    from `skyg_sns`.`sns_collect_detail` AS scld
				                   where scld.`collect_id`=:v_collectid
				                     and scld.`collect_flag`=0".$v_sql,
				array( "v_collectid"=>(int)$collectid
				)
		)->toList();
	}
	

	/**
	 *
	 * @param  int $userid          用户id
	 * @param string $syscondition  策略控制条件
	 * @return multitype:          
	 */
	public static function queryCollectDetailByUid($userid,$syscondition){
		if ($syscondition!=''){
			$v_sql=' and '.$syscondition;
		}else{
			$v_sql='';
		}
		return parent::createSQL("select scld.`collect_detail_id`,
				                         scld.`collect_id`,
				                         scld.`user_id`,
				                         scld.`collect_flag`,
				                         scld.`created_date`
				                    from `skyg_sns`.`sns_collect_detail` AS scld
				                   where scld.`user_id`=:v_userid
				                     and scld.`collect_flag`=0".$v_sql,
				array( "v_userid"=>(int)$userid
				)
		)->toList();
	}
	
	/**
	 * 
	 * @param int $collectdetailid    分享信息表id
	 * @param int $collectflag        分享信息状态
	 * @return number
	 */
	public static function updateCollectDetail($collectdetailid,$collectflag){
		return parent::createSQL("update `skyg_sns`.`sns_collect_detail` AS scld
				                     set scld.`collect_flag`=:v_collectflag
				                   where scld.`collect_detail_id`=:v_collectdetailid",
				array( "v_collectdetailid"=>(int)$collectdetailid,
					   "v_collectflag"=>(int)$collectflag
				      )
		)->exec();
	}
	
	/**
	 * 
	 * @param string $uid       拼接好后的UID字符串，用于查询各UID的收藏数
	 * @return multitype:
	 */
	public static function getCollectTotalByUid($uid){
	       return parent::createSQL("select scld.`user_id`,
	       		                             count(1) AS `collecttotal`
	       		                       from `skyg_sns`.`sns_collect_detail` AS scld
	       		                      where scld.`user_id` in (".$uid.")
	       		                        and scld.`collect_flag`= 0
	       		                      group by scld.`user_id`"
	       		                    )->toList();
	}
}