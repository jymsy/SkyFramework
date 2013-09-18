<?php

namespace resource\models;

use skyosadmin\components\PublicModel;
use Sky\db\DBCommand;

/**table 
 */
class PolicyManageModel extends \Sky\db\ActiveRecord{
	/**
	 *@return PolicyManageModel
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}	
	
	/**
	 * 
	 * 返回所有策略控制
	 */
	public static function GetPolicyList($star,$pagesize,$orderCondition=array("policy_id"=>"ASC")){
		$orderString=PublicModel::controlArray($orderCondition);
		return parent::createSQL("SELECT 
									  `policy_id`,
				                      `policy_name`,
				                      `function_name`,
									  `chip`,
									  `model`,
									  `platform`,
									  `screen_size`,
				                      `mac_start`,
				                      `mac_end`,
									  `flag`,
									  `policy_value`,
									  `remark`,
									  `priority`,
				                      `version` 
									FROM
									  `skyg_base`.`base_policy_conf`
				                    order by ".$orderString."
				                    limit :star,:pagesize",
				                array("star"=>(int)$star,
				                	  "pagesize"=>(int)$pagesize
				                		))->toList();
	}
	
	public static function GetPolicyCount($condition=''){
		$uniq=" where ";
		if ($condition==''){
			$sql='';
		}else{
			$sql = '';
			foreach($condition as $k=>$v){
				if ($k=='')continue;
				if ($sql){
					$sql .= " and `$k`='$v'";
				}else {
					$sql .= " `$k`='$v'";
				}
			}
		}
		if ($sql) $sql = $uniq.$sql;
		
		return parent::createSQL("select count(1)
				                    from `skyg_base`.`base_policy_conf`".$sql)->toValue();
		
		
		
	}
	
	/**
	 * 
	 * @param string $v_policyname    策略名
	 * @param string $v_fname         接口名
	 * @param string $v_chip          机芯
	 * @param string $v_model         机型
	 * @param string $v_platform      平台
	 * @param string $v_size          尺寸
	 * @param string $v_macstart      起始MAC
	 * @param string $v_macend        结束MAC
	 * @param string $v_flag          硬件分类
	 * @param string $v_value         策略条件
	 * @param string $v_remark        备注
	 * @param int    $v_priority      优先级
	 * @param string $v_version       系统版本
	 * @return number                 插入成功返回新的策略ID，失败返回0
	 

	 */
	public static function InsertPolicy($v_policyname,$v_fname,$v_chip,$v_model,$v_platform,$v_size,
			                            $v_macstart,$v_macend,$v_flag,$v_value,$v_remark,$v_priority,$v_version){
		$par=parent::createSQL("insert into `skyg_base`.`base_policy_conf`(
				                            `policy_name`,
				                            `function_name`,
				                            `chip`,
										    `model`,
										    `platform`,
										    `screen_size`,
				                            `mac_start`,
				                            `mac_end`,
										    `flag`,
										    `policy_value`,
										    `remark`,
										    `priority`,
				                            `version`)
				                     values(:v_policyname,
				                            :v_fname,
				                            :v_chip,
				                            :v_model,
											:v_platform,
											:v_size,
				                            :v_macstart,
				                            :v_macend,
											:v_flag,
											:v_value,
											:v_remark,
											:v_priority,
				                            :v_version)",
				                   array( "v_policyname"=>$v_policyname,
				                   		  "v_fname"=>$v_fname,
				                   		  "v_chip"=>$v_chip,
				                   		  "v_model"=>$v_model,
				                   		  "v_platform"=>$v_platform,
				                   		  "v_size"=>$v_size,
				                   		  "v_macstart"=>$v_macstart,
				                   		  "v_macend"=>$v_macend,
				                   		  "v_flag"=>$v_flag,
				                   		  "v_value"=>$v_value,
				                   		  "v_remark"=>$v_remark,
				                   		  "v_priority"=>(int)$v_priority,
				                   		  "v_version"=>$v_version
				                   		  ));
		
		if($par->exec()){
			$par->getPdoInstance();
			return $par->lastInsertID();
		}else{
			return 0;
		}
	}
	
	/**
	 * 
	 * @param int    $policyid       策略ID
	 * @param string $policyvalue    策略条件
	 * @param int    $vpriority      优先级
	 * @param string $vremark        备注
	 * @return number                修改成功返回大于0的值，反之为0 
	 */
	public static function UpdatePolicy($policyid,$policyvalue,$vpriority,$vremark,$vflag,$vmacstart,$vmacend){
		return parent::createSQL("update `skyg_base`.`base_policy_conf`
				                     set `policy_value`=:policyvalue,
				                         `priority`=:vpriority,
				                         `remark`=:vremark,
				                         `flag`=:vflag,
				                         `mac_start`=:vmacstart,
				                         `mac_end`=:vmacend 
				                   where `policy_id`=:policyid",
				                     array("policyvalue"=>$policyvalue,
				                     	   "policyid"=>(int)$policyid,
				                     	   "vpriority"=>$vpriority,
				                     	   "vremark"=>$vremark,
				                     	   "vflag"=>(int)$vflag,
				                     	   "vmacstart"=>$vmacstart,
				                     	   "vmacend"=>$vmacend
				                     		))->exec();
	}
	
	/**
	 * 
	 * @param int $policyid   策略ID
	 * @return number         删除成功返回大于0的值，反之为0 
	 */
	public static function DeletePolicyById($policyid){
		return parent::createSQL("delete from `skyg_base`.`base_policy_conf`
				                   where `policy_id`=:policyid",
				array(
						"policyid"=>(int)$policyid
				))->exec();
	}
	
	
	/**
	 *
	 * @param string $v_policyname    策略名
	 * @param string $v_fname         接口名
	 * @param string $v_chip          机芯
	 * @param string $v_model         机型
	 * @param string $v_platform      平台
	 * @param string $v_size          尺寸
	 * @param string $v_macstart      起始MAC
	 * @param string $v_macend        结束MAC
	 * @param string $v_value         策略条件
	 * @param string $v_remark        备注
	 * @param string $v_version       系统版本
	 */
	public static function searchPolicy($v_policyname,$v_fname,$v_chip,$v_model,$v_platform,$v_size,
			                            $v_macstart,$v_macend,$v_value,$v_remark,$v_version){
	  return parent::createSQL("select count(1) 
				                  from `skyg_base`.`base_policy_conf`
				                 where `policy_name` = :v_policyname
				                   and `function_name` = :v_fname
				                   and `chip` = :v_chip
				                   and `model` = :v_model
				                   and `platform` = :v_platform
				                   and `screen_size` = :v_size
				                   and `mac_start` = :v_macstart
				                   and `mac_end` = :v_macend
				                   and `policy_value` = :v_value
				                   and `remark` = :v_remark
				                   and `version` = :v_version",
				array( "v_policyname"=>$v_policyname,
						"v_fname"=>$v_fname,
						"v_chip"=>$v_chip,
						"v_model"=>$v_model,
						"v_platform"=>$v_platform,
						"v_size"=>$v_size,
						"v_macstart"=>$v_macstart,
						"v_macend"=>$v_macend,
						"v_value"=>$v_value,
						"v_remark"=>$v_remark,
						"v_version"=>$v_version
				))->toValue();
	}
    
	/**
	 * 
	 * @param array  $condition       search条件数组
	 * @param int    $start           起始位置
	 * @param int    $pagesize        取的条数
	 * @param string $orderCondition  排序数组
	 * @return multitype:
	 */
	public static function GetPolicyInfo(array $condition,$start,$pagesize,$orderCondition=array("policy_id"=>"ASC")){
		$orderString=PublicModel::controlArray($orderCondition);
		if ($condition==''){
			$sql='';
		}else{
			$sql = '';
			foreach($condition as $k=>$v){
				if ($k=='')continue;
				if ($sql){
					$sql .= " and `$k`='$v'";
				}else {
					$sql .= " `$k`='$v'";
				}
			}
		}
		
		if ($sql) $sql = " and ".$sql;
		
		return parent::createSQL("select `policy_id`,
				                      `policy_name`,
				                      `function_name`,
									  `chip`,
									  `model`,
									  `platform`,
									  `screen_size`,
				                      `mac_start`,
				                      `mac_end`,
									  `flag`,
									  `policy_value`,
									  `remark`,
									  `priority`,
				                      `version` 
				                 from `skyg_base`.`base_policy_conf`
				                where 1=1 ".$sql."
				                order by ".$orderString."
				                limit :start,:pagesize",
				              array("start"=>(int)$start,
				              		"pagesize"=>(int)$pagesize
				              		))->toList();
	}
	
	/**
	 * 
	 * @param array $condition           search条件数组
	 * @return Ambigous <NULL, unknown>  返回查询数据COUNT值
	 */
	public static function GetPolicyInfoCount(array $condition){
		
		if ($condition==''){
			$sql='';
		}else{
			$sql = '';
			foreach($condition as $k=>$v){
				if ($k=='')continue;
				if ($sql){
					$sql .= " and `$k`='$v'";
				}else {
					$sql .= " `$k`='$v'";
				}
			}
		}
	
		if ($sql) $sql = " and ".$sql;
	
		return parent::createSQL("select count(1)
				                 from `skyg_base`.`base_policy_conf`
				                where 1=1 ".$sql)->toValue();
	}
	
	/**
	 * 
	 * @param int $policyid   策略主键ID
	 * @param int $vflag      停用启用标识
	 * @return number
	 */
	public static function UpdatePolicyFlag($policyid,$vflag){
		return parent::createSQL("update `skyg_base`.`base_policy_conf`
				                     set `flag` = :vflag
				                   where `policy_id`=:policyid",
				               array("policyid"=>(int)$policyid,
				               		 "vflag"=>(int)$vflag
				               		  ))->exec();
		
	}
}