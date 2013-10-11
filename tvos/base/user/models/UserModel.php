<?php

namespace base\user\models;

/**
 * @property  int          user_id           用户id                   
 * @property  int          operator_code     运营商标识            
 * @property  string       email             email地址                
 * @property  string       user_password     密码                     
 * @property  int          auto_login        是否自动登录         
 * @property  string       user_nickname     昵称                     
 * @property  string       user_icon         用户头像               
 * @property  string       create_date       创建时间               
 * @property  string       last_update_date  最后更改时间         
 * @property  int          is_admin          1-admin user;0-普通user  
 * @property  int          is_delete         1-deleted;0-not deleted
 *  
 * @author Zhengyun
 */
class UserModel extends \Sky\db\ActiveRecord{
	/**
	 *@return UserModel
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}

	protected static $tableName="skyg_base.base_user";
	protected static $primeKey=array("user_id");
	
	
	/**
	 * 用户注册
	 * @param Int operator_code 区域编码
	 * @param String user_password
	 * @param String user_nickname
	 * @param Int    is_admin admin标识，1-admin，0-普通用户
	 * @return Int  0-注册失败，>0 成功注册的user_id
	 */
	public static function userRegister($operator_code,$user_password,$user_nickname,$is_admin){
		$result=parent::createSQL("Call `skyg_base`.`proc_base_user_register`(:operator_code,:user_password,:user_nickname,:is_admin)",
			array(  
					"operator_code"=>(int)$operator_code,
					"user_password"=>$user_password,
					"user_nickname"=>$user_nickname,
					"is_admin"=>(int)$is_admin
				)
		)->toValue();
		
		return $result;
	}
	
	
	/**
	 * 用户信息更新
	  * @property  int          user_id           用户id                   
 	  * @property  int          operator_code     运营商标识            
      * @property  string       email             email地址                
      * @property  string       user_password     密码                     
      * @property  int          auto_login        是否自动登录         
      * @property  string       user_nickname     昵称                     
      * @property  string       user_icon         用户头像               
      * @property  string       birthday          生日               
      * @property  string       sex               性别         
      * @property  int          user_realname     用户真实姓名   
      * @property  string       address          地址        
      * @property  string       telephone_no      电话号码
	  * @return Int  0-更新失败，1-更新成功
	  */
	public static function userUpdate($user_id,$operator_code,$email,$user_password,$auto_login,$user_nickname,
	$user_icon,$birthday,$sex,$user_realname,$address,$telephone_no,$constellation,$personal_tab,$signature){		
		
		$result=parent::createSQL(
				"Call `skyg_base`.`proc_base_user_update`(:user_id,:operator_code,:email,:user_password,:auto_login,:user_nickname,
			 :user_icon,:birthday,:sex,:user_realname,:address,:telephone_no,:constellation,:personal_tab,:signature)",
		array(  "user_id"=>$user_id,
						"operator_code"=>(int)$operator_code,
						"email"=>$email,
						"user_password"=>$user_password,
						"auto_login"=>(int)$auto_login,
						"user_nickname"=>$user_nickname,
						"user_icon"=>$user_icon,
						"birthday"=>$birthday,
						"sex"=>(int)$sex,
						"user_realname"=>$user_realname,
				        "address"=>$address,
				        "telephone_no"=>$telephone_no,
				        "constellation"=>$constellation,
				        "personal_tab"=>$personal_tab,
				        "signature"=>$signature
		)

		)->toValue();
		return $result;
	}

	
	/**
	 * 获取用户信息
	 * @param   String       id               用户id或者email
	 * @return  int          userId           用户id      
 	 * @return  int          operatorCode     运营商标识  
     * @return  string       email            email地址  
     * @return  int          autoLogin        是否自动登录
     * @return  string       userNickname     昵称        
     * @return  string       userIcon         用户头像    
     * @return  string       birthday         生日        
     * @return  string       sex              性别        
     * @return  int          userRealname     用户真实姓名  
     * @return  string       address          地址        
     * @return  string       telephoneNo      电话号码
     * 
	 */
	public static function getUserInfo($id){
		$result=parent::createSQL(
				"SELECT 
				  a.user_id AS userId,
				  a.operator_code AS operatorCode,
				  a.email,
				  a.auto_login AS autoLogin,
				  a.user_nickname AS userNickName,
				  a.user_icon AS userIcon,
				  b.birthday,
				  b.sex,
				  b.user_realname AS userRealName,
				  b.`address` AS address,
				  b.`telephone_no` AS telephoneNo ,
				  b.`signature` ,
				  b.`personal_tab` AS personalTab,
				  b.`constellation`
				FROM
				  skyg_base.`base_user` a 
				  JOIN skyg_base.`base_user_detail` b 
				WHERE a.`user_id` = b.`user_id` 
				  AND (a.`user_id` = :id 
				    OR a.`email` = :id)",
		array(  
				"id"=>$id
		)

		)->toList();
		return $result;
	}
	
	
	
	/*public static function userLogin($login_id,$password,$ip,$mac){
		$connection=\Sky\Sky::app()->db;
		$result=$connection::createCommand(
				"CALL skyg_base.proc_base_user_login(:login_id, :password, :ip,:mac)",
				array(
						"login_id"=>$login_id,
						"password"=>$password,
						"ip"=>$ip,
						"mac"=>$mac
				)
		)->toValue();
		return $result;
	
	}*/
	
	/**
	 * 用户身份认证
	 * @param String login_id  用户id或者email
	 * @param String user_password
	 * @param Int    $is_admin admin标识，1-admin，0-普通用户
	 * @return Int  0-认证失败，>0 成功认证的user_id
	 */
	public static function userPasswordVerify($login_id,$password,$is_admin){
		$result=parent::createSQL(
				"SELECT user_id AS userId FROM skyg_base.`base_user` 
					WHERE (`user_id`=:login_id OR `email`=:login_id ) 
				   AND user_password=UNHEX(MD5(:password)) AND is_deleted=0 AND is_admin=:is_admin",
				array(
						"login_id"=>$login_id,
						"password"=>$password,
						"is_admin"=>(int)$is_admin
				)
		)->toValue();
		return $result;
	
	}
	
	/**
	 * 创建用户session
	 * @param Int userId
	 * @param String ip
	 * @param String mac
	 * @return String  ""(空字符)-创建session失败，否则返回成功创建的session
	 */
	public static function addSession($userId,$ip,$mac){
		$result=parent::createSQL(
				"CALL skyg_base.`proc_base_user_create_session`(:userId,:ip,:mac)",
				array(
						"userId"=>(int)$userId,
						"ip"=>$ip,
						"mac"=>$mac
				)
		)->toValue();		
		return $result;	
	}
	
	/**
	 * 删除普通用户
	 * @param String/Int login_id user_id或者email
	 * @param String  password
	 * @return Int 0-删除失败，1-删除成功
	 * */
	public static function userDel($login_id,$password){
		$result=parent::createSQL(
				"update skyg_base.`base_user` SET is_deleted=1  
					WHERE (`user_id`=:login_id OR `email`=:login_id )
				   AND user_password=UNHEX(MD5(:password)) AND is_admin=0",
				array(
					"login_id"=>$login_id,
					"password"=>$password
				)
		)->exec();
		return $result;
	}
	
	
	 /**通过nickname查找userid
	  * 
	  * @param String $userNickName
	  * @param Int   默认（$del_flag=1）过滤已删除的用户
	  * @return array userIds
	  * 
	  */
	public static function getUserIdByNickName($userNickName,$start=0,$limit=50,$del_flag=1){
		$con='';
		if($del_flag==1)
			$con="AND `is_deleted`=0";
		
		$sql=sprintf("SELECT 
				  `user_id` AS userId,
				  `user_nickname` AS userNickName,
				  `user_icon` AS userIcon 
				FROM
				  `skyg_base`.`base_user` 
				WHERE `user_nickname` like '%%%s%%' %s
				LIMIT %d,%d",$userNickName,$con,$start,$limit);
		$result=parent::createSQL($sql)->toList();
		return $result;
	}
	
	/**通过email查找userid
	 *
	* @param String $email
	* @param Int   默认（$del_flag=1）过滤已删除的用户
	* @return Int userId or NULL
	*
	*/
	public static function getUserIdByEmail($email,$del_flag=1){
		$con='';
		if($del_flag==1)
			$con='AND `is_deleted`=0';
		
		$sql=sprintf("SELECT
				  `user_id` AS userId
				FROM
				  `skyg_base`.`base_user`
				WHERE `email` = '%s' %s",$email,$con
				);
		$result=parent::createSQL($sql)->toValue();
		return $result;
	}
	
	/**注销 admin 账户
	 *
	* @param INT user_id
	* @return Int 0-更新失败，1-更新成功
	*
	*/
	public static function updateAdminType($user_id){		
		$sql=sprintf("UPDATE 
					  `skyg_base`.`base_user` 
					SET
					  is_admin = 2 
					WHERE user_id = %d ",$user_id
		);
		$result=parent::createSQL($sql)->exec();
		return $result;
	}
	
}