<?php
namespace sns\models;
/**
 * @property  int          praise_id           顶踩id                                                                                                                      
 * @property  int          publish_id          资源ID                                                                                                                      
 * @property  int          praise_count        顶踩次数                                                                                                                  
 * @property  int          step_count          顶踩内容状态，0为有效，1为失效                                                                                  
 * @property  string       created_date        创建时间                                                                                                                  
 * @property  string       last_update_date    最后顶踩时间                    
 * 
 * @author xiaokeming
 */

class SnsPraiseModel extends \Sky\db\ActiveRecord{
	/**
	 *@return SnsPraiseModel
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}

	protected static $tableName="skyg_sns.sns_praise";
	protected static $primeKey=array("praise_id");
	
	/**
	 * 
	 * @param int $publishid     资源id
	 * @param int $praisetype    顶踩类型
	 * @return number            插入成功返回大于0的数，插入失败返回0
	 */
	public static function insertPraise($publishid,$praise=0,$step=0){
	   
		if ($praise!=1){$praisecount = 1;}else{$praisecount = 0;}
		if ($step!=1){$stepcount = 1;}else{$stepcount = 0;}
		
	   $par=parent::createSQL("insert into `skyg_sns`.`sns_praise`(
								   		`publish_id`,
								   		`praise_count`,
								   		`step_count`,
								   		`created_date`)
								 values(:v_publishid,
								   		:v_praisecount,
								   		:v_stepcount,
								   		CURRENT_TIMESTAMP
								   		)",
							   		array( "v_publishid"=>(int)$publishid,
							   			   "v_praisecount"=>(int)$praisecount,
							   			   "v_stepcount"=>(int)$stepcount)
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
	 * @param int    $praiseid        顶踩表ID
	 * @param int    $userid          用户ID
	 * @param string $praisecontent   顶踩具体内容 
	 * @return number                 插入成功返回大于0的数，插入失败返回0
	 */
	public static function insertPraiseDetail($praiseid,$userid,$praise=0){
		if ($praise!=1){$praiseflag = 0;}else{$praiseflag = 1;}
		
		$par=parent::createSQL("insert into `skyg_sns`.`sns_praise_detail`(
								   		`praise_id`,
								   		`user_id`,
								   		`praise_flag`)
								 values(:v_praiseid,
								   		:v_userid,
				                        :v_praiseflag
								   		)",
				array(  "v_praiseid"=>(int)$praiseid,
						"v_userid"=>(int)$userid,
						"v_praiseflag"=>(int)$praiseflag)
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
	 * @param int $praisetype       顶踩类型
	 * @param string $syscondition  策略控制条件
	 * @return multitype:
	 */
	public static function queryPraise($publishid,$syscondition){
		if ($syscondition!=''){
			$v_sql=' and '.$syscondition;
		}else{
			$v_sql='';
		}
		return parent::createSQL("select sp.`praise_id`,
				                         sp.`praise_count`
				                    from `skyg_sns`.`sns_praise` AS sp
				                   where sp.`publish_id`=:v_publishid".$v_sql,
				array( "v_publishid"=>(int)$publishid
				)
		)->toList();
	}
	
	/**
	 * 
	 * @param int $praiseid    顶踩表id
	 * @return number          更新成功返回大于0的数，更新失败返回0
	 */
	public static function updatePraise($praiseid,$praise=0,$step=0){
		$praisecount=0;
		$stepcount=0;
		if ($praise!=1){$praisecount = 1;}
		if ($step!=1){$stepcount = 1;}
		return parent::createSQL("update `skyg_sns`.`sns_praise` AS sp
				              set sp.`praise_count`=sp.`praise_count`+ :v_praisecount,
				                  sp.`step_count`=sp.`step_count`+ :v_stepcount
				            where sp.`praise_id`=:v_praiseid",
				array(  "v_praiseid"=>(int)$praiseid,
						"v_praisecount"=>(int)$praisecount,
						"v_stepcount"=>(int)$stepcount)
		)->exec();
		
	}
	
	
	
	/**
	 * 
	 * @param  int $praiseid        顶踩表id
	 * @param string $syscondition  策略控制条件
	 * @return multitype:          
	 */
	public static function queryPraiseDetailByCid($praiseid,$syscondition){
		if ($syscondition!=''){
			$v_sql=' and '.$syscondition;
		}else{
			$v_sql='';
		}
		return parent::createSQL("select spd.`praise_detail_id`,
				                         spd.`praise_id`,
				                         spd.`user_id`,
				                         spd.`praise_flag`,
				                         spd.`created_date`
				                    from `skyg_sns`.`sns_praise_detail` AS spd
				                   where spd.`praise_id`=:v_praiseid".$v_sql,
				array( "v_praiseid"=>(int)$praiseid
				)
		)->toList();
	}
	

	/**
	 *
	 * @param  int $userid          用户id
	 * @param string $syscondition  策略控制条件
	 * @return multitype:          
	 */
	public static function queryPraiseDetailByUid($userid,$syscondition){
		if ($syscondition!=''){
			$v_sql=' and '.$syscondition;
		}else{
			$v_sql='';
		}
		return parent::createSQL("select spd.`praise_detail_id`,
				                         spd.`praise_id`,
				                         spd.`user_id`,
				                         spd.`praise_flag`,
				                         spd.`created_date`
				                    from `skyg_sns`.`sns_praise_detail` AS spd
				                   where spd.`user_id`=:v_userid".$v_sql,
				array( "v_userid"=>(int)$userid
				)
		)->toList();
	}
	
	/**
	 * 
	 * @param int $praisedetailid    顶踩信息表id
	 * @param int $praiseflag        顶踩信息状态
	 * @return number
	 */
	public static function updatePraiseDetail($praisedetailid,$praiseflag){
		return parent::createSQL("update `skyg_sns`.`sns_praise_detail` AS spd
				                     set spd.`praise_flag`=:v_praiseflag
				                   where spd.`praise_detail_id`=:v_praisedetailid",
				array( "v_praisedetailid"=>(int)$praisedetailid,
					   "v_praiseflag"=>(int)$praiseflag
				      )
		)->exec();
	}
	
	/**
	 *
	 * @param string $uid       拼接好后的UID字符串，用于查询各UID的踩顶数
	 * @return multitype:       当Praise_flag为0时praisetotal为踩的数量，为1时praisetotal为顶的数量
	 */
	public static function getPraiseTotalByUid($uid){
		return parent::createSQL("select spd.`user_id` as `user_id`,
				                         spd.`praise_flag`,
	       		                         count(1) AS `praisetotal`
	       		                    from `skyg_sns`.`sns_praise_detail` AS spd
	       		                   where spd.`user_id` in (".$uid.")
	       		                   group by spd.`user_id`,spd.`praise_flag`"
		)->toList();
	}
}