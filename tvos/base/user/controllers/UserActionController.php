<?php
namespace base\user\controllers;

use Sky\base\Controller;
use base\user\models\UserModel;
use base\user\models\BaseDevice;
use base\user\models\UserUserMapModel;
use Sky\db\DBCommand;
use Sky\Sky;
use base\models\DeviceConfigModel;

define("GROUP_TYPE", "1");
define("IS_ADMIN", "1");
define("IS_NOT_ADMIN", "0");

class UserActionController extends Controller {

	public function actions(){
		return array(
				"Wsdl"=>array(
						"class"=>"Sky\\base\\WebServiceAction"
				),
		);
	}

	/**
	 * 获取adminUserId
	 * @param unknown $mac
	 * @return Ambigous <multitype:, NULL, mixed>
	 */
	public function actionGetAdminUserId($mac){

		$adminUserId = BaseDevice::queryAdmin($mac);
		
		if(empty($adminUserId) || $adminUserId == null){
			$userName = "admin";
			$userId = UserModel::userRegister(OPERATOR_CODE, "", $userName, IS_ADMIN);
			if($userId != 0){
				BaseDevice::addDevUserMap($mac, $userId);
			}
			return $userId;
		}

		return $adminUserId;
	}

	/**
	 * 获取用户列表
	 * @param unknown $adminUserId
	 * @return Ambigous <multitype:, NULL, mixed>
	 */

	public function actionGetUserList($adminUserId){

		$userList = array();
		$userList = UserUserMapModel::queryUserUserMap($adminUserId, GROUP_TYPE);

		if(!empty($userList)){
			return $userList;
		}

		return array();
	}

	/**
	 * 使用admin用户创建一个普通用户
	 * @param unknown $adminUserId
	 */
	public function actionCreateUser($userName, $passWord, $adminUserId){

		$userId = UserModel::userRegister(OPERATOR_CODE, $passWord, $userName, IS_NOT_ADMIN);

		$result = UserUserMapModel::addUserUserMap($adminUserId, $userId, GROUP_TYPE);

		if($result == 1){
			return $userId;
		}else{
			return 0;
		}

	}

	/**
	 * 用户注册
	 * @param string $userName
	 * @param string $passWord
	 * @return object_user
	 */
	public function actionRegister($userName, $passWord){

		$userId = UserModel::userRegister(OPERATOR_CODE, $passWord, $userName, IS_NOT_ADMIN);

		return $userId;
	}

	/**
	 * 更新用户信息
	 * @param int $userId
	 * @param string $email
	 * @param string $password
	 * @param number $autoLogin
	 * @param string $icon
	 * @param string $birthday
	 * @param number $sex
	 * @param string $realName
	 * @param string $nickName
	 * @return int 描述
	 */
	public function actionUpdateUser($userId, $email, $password, $autoLogin = 0, $icon = "", $birthday = "", $sex = 0, $realName = "", $nickName = "", $address = "" , $telephoneNo = "", $constellation = "", $personalTab = "", $signature = "") {

		$flag = UserModel::userUpdate($userId, OPERATOR_CODE, $email, $password, $autoLogin, $nickName, $icon, $birthday, $sex, $realName,$address, $telephoneNo,$constellation,$personalTab,$signature);
		return $flag;
	}

	/**
	 * 普通用户登陆
	 * @param int $loginId
	 * @param string $password
	 * @return object_user:
	 */
	public function actionUserLogin($loginId, $password){

		$ip = \Sky\Sky::app()->request->getUserHostAddress();

		$userId = UserModel::userPasswordVerify($loginId, $password, IS_NOT_ADMIN);

		if(empty($userId)){
			return -1;
		}

		if($userId > 0){
			$session = UserModel::addSession($userId, $ip, "");
			return $session;
		}else{
			return 0;
		}
	}

	/**
	 * ADMIN用户登陆
	 * @param int $loginId
	 * @param string $password
	 * @return object_user:
	 */
	public function actionAdminLogin($loginId, $password, $mac){

		$ip = \Sky\Sky::app()->request->getUserHostAddress();
		$adminUserId = BaseDevice::queryAdmin($mac);
		$userId = UserModel::userPasswordVerify($loginId, $password, IS_ADMIN);

		if(empty($userId)){
			return -1;
		}

		if($userId == $adminUserId){
			$session = UserModel::addSession($userId, $ip, $mac);
			return $session;
		}else{
			return 0;
		}
	}

	/**
	 * 获取用户信息
	 * @param unknown $userId
	 * @return multitype:
	 */
	public function actionGetUserInfo($userId){

		$user = UserModel::getUserInfo($userId);

		return $user;

	}

	/**
	 * 提交电视信息
	 * @param unknown $dev_mac
	 * @param string $chip
	 * @param string $model
	 * @param string $system_version
	 * @param string $platform
	 * @param string $barcode
	 * @param string $screen_size
	 * @param string $screen_type
	 * @param unknown $user_id
	 * @return Ambigous <NULL, mixed>
	 */
	public function actionSubmitTVInfo($dev_mac, $chip="", $model="", $system_version="",
			$platform="", $barcode="", $screen_size="", $resolution="", $user_id){

		$result = 1;

		if(strlen($dev_mac) < 1){
			return -1;
		}

		if(strlen($chip) < 1){
			return -2;
		}

		if(strlen($model) < 1){
			return -3;
		}

		if(strlen($system_version) < 1){
			return -4;
		}

		$deviceId = BaseDevice::getDeviceInfoByMac($dev_mac);

		if(empty($deviceId)){
			$result = BaseDevice::addDevice($dev_mac, $chip, $model, $system_version, $platform, $barcode, $screen_size, $resolution);

			if($user_id != 0){
				$result = BaseDevice::addDevUserMap($dev_mac, $user_id);
			}

		}else{
			$result = BaseDevice::updateDevice($dev_mac, $chip, $model, $system_version, $platform, $barcode, $screen_size, $resolution);
		}

		return $result;
	}

	/**
	 * Admin账户删除普通用户， Admin用户不允许被删除
	 * @param unknown $userId
	 */
	public function actionDelUser($adminUserId, $loginId, $passWord){

		$userId = UserModel::userPasswordVerify($loginId, $passWord, 0);
		if($userId > 0){
			$userDel = UserModel::userDel($loginId, $passWord);
			$userUserDel = UserUserMapModel::delUserUserMap($adminUserId, $userId, GROUP_TYPE);
			if($userDel == 1 && $userUserDel == 1){
				return 1;
			}
		}

		return 0;
	}

	/**
	 * 获取普通用户的adminId
	 * @param unknown $userId
	 */
	public function actionGetUserAdminId($userId){

		$result = UserUserMapModel::queryAdminByUser($userId, GROUP_TYPE);

		if(empty($result)){
			return 0;
		}
		return $result;
	}

	/**
	 * 检查邮箱重复
	 * @param unknown $emailc
	 *
	 * ..
	 * @return number|Ambigous <number, NULL, mixed>
	 */
	public function actionCheckUserEmail($email){

		$result = UserModel::getUserIdByEmail($email);

		if(empty($result)){
			return 0;
		}else{
			return $result;
		}
	}

	/**
	 * 删除用户设备绑定表
	 * @param unknown $mac
	 * @param unknown $userId
	 * @return number
	 */
	public function actionDelUserDevMap($mac, $userId){

		$result = 0;
		if(strlen($mac) > 1 && strlen($userId) > 1){
			$result = BaseDevice::delDevUserMap($mac,$userId);
		}

		return $result;
	}

	/**
	 * 根据session获取机器信息
	 */
	public function actionGetTVInfoBySession(){
		$session=Sky::$app->session;
		if (!$session->illegalSession()) {
			$tvinfo=$session->getTVInfo();
			return $tvinfo;
		}else{
			return "-1";
		}
	}
	
	/**
	 * 获取全部机型机芯列表 
	 * @return Ambigous <\base\models\multitype:, multitype:>
	 */
	public function actionAllDeviceConfig(){
		return DeviceConfigModel::getDeviceInfo();
	}
	
	/**
	 * 更新机芯机芯表数据
	 * @return Ambigous <NULL, mixed>
	 */
	public function actionUpdateDeviceInfo(){
		return DeviceConfigModel::insertDeviceInfo();
	}
	
	
}