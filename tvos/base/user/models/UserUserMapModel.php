<?php

namespace base\user\models;

/**
 * @property  int          main_user_id  group_type=1时，admin user  
 * @property  int          sub_user_id   group_type=1时，普通user  
 * @property  int          group_type    1-家庭分组                
 * @property  string       create_date   创建时间   
 * @author Zhengyun
 */
class UserUserMapModel extends \Sky\db\ActiveRecord{
	/**
	 *@return UserUserMapModel
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}

	protected static $tableName="skyg_base.base_user_user_map";
	protected static $primeKey=array("main_user_id","sub_user_id","group_type");	
	
	/**
	 * 添加user_user_map关系数据
	 * @param Int mainUserId 主用户id 当group_type=1时，为admin userid
	 * @param Int groupType  groupType=1为家庭分组
	 * @return Int 1-添加成功，0-添加失败
	 */
	public static function addUserUserMap($main_user_id,$sub_user_id,$group_type){
		$result=parent::createSQL(
				"insert into  skyg_base.base_user_user_map(main_user_id,sub_user_id,group_type)  values  ( :main_user_id,:sub_user_id,:group_type )",
				array(
						"main_user_id"=>(int)$main_user_id,
						"sub_user_id"=>(int)$sub_user_id,
						"group_type"=>(int)$group_type
				)
		)->exec();
		return $result;
		 
	}
	
	/**
	 * 删除user_user_map关系数据
	 * @param Int mainUserId 主用户id 当group_type=1时，为admin userid
	 * @param Int groupType  groupType=1为家庭分组
	 * @return Int 1-删除成功，0-删除失败
	 */
	public static function delUserUserMap($main_user_id,$sub_user_id,$group_type){
		$result=parent::createSQL(
				"DELETE FROM skyg_base.`base_user_user_map` WHERE  `main_user_id`=:main_user_id
				AND `sub_user_id`=:sub_user_id AND `group_type`=:group_type",
				array(
						"main_user_id"=>(int)$main_user_id,
						"sub_user_id"=>(int)$sub_user_id,
						"group_type"=>(int)$group_type
				)
		)->exec();
		return $result;
	
	}
	
	/**
	 * 用户列表查询
	 * @param Int mainUserId 主用户id 当group_type=1时，为admin userid
	 * @param Int groupType  groupType=1为家庭分组
	 * @return array  userid 
	 */
	
	public static function queryUserUserMap($main_user_id,$group_type){
		$result=parent::createSQL(
				"SELECT sub_user_id AS userId FROM skyg_base.`base_user_user_map` WHERE main_user_id=:u_id AND group_type=:group_type",
				array(
						"u_id"=>(int)$main_user_id,
						"group_type"=>(int)$group_type
				)
	
		)->toList();
		return $result;
	}
	
	
	/**
	 * 通过user_id查询admin id
	 * @param Int userId 主用户id 当group_type=1时，为admin userid
	 * @param Int groupType  groupType=1为家庭分组
	 * @return Int  adminId 
	 * */
	public static function queryAdminByUser($user_id,$group_type){
		$result=parent::createSQL(
				"SELECT main_user_id FROM `skyg_base`.`base_user_user_map` WHERE sub_user_id=:user_id AND group_type=:group_type",
				array(
						"user_id"=>(int)$user_id,
						"group_type"=>(int)$group_type
				)
		)->toValue();
		return $result;
	}
	
	
	
	

}