<?php
namespace tests\base\user\unit;

use base\user\models\UserModel;

/**
 * UserModel test case.
 * 
 * @author Zhengyun
 */
class UserModelTest extends \Sky\test\TestCase{
	public function testModel()
	{
		$obj=UserModel::model();
		$this->assertInstanceOf('base\user\models\UserModel',$obj);
	}
		
	/**
	 * Tests UserModel::userRegister()
	 */
	public function testUserRegister()
	{
		
		$id=UserModel::userRegister($operator_code=1100,$user_password="123456",$user_nickname="i_am_test",$is_admin=0);
		$this->assertGreaterThan(0,$id,'register user failed');
		return $id;
	}
	
	/**
	 * @depends testUserRegister
	 */
	public function testUserUpdate($id)
	{
		$ret=UserModel::userUpdate($id,1100,"test_email","654321",0,"i_am_test_update",
	"test_icon","20130712",0,"test_realname","test_address","888888","constellation1","personal_tab1","signature1");
		$this->assertEquals(1,$ret,'update user info failed');
	}
	
	/**
	 * @depends testUserRegister
	 * Tests UserModel::getUserInfo()
	 */
	public function testGetUserInfo($id)
	{
		$userInfo=UserModel::getUserInfo($id);
		$this->assertCount(1,$userInfo);
		$this->assertCount(14,$userInfo[0]);
	}
	
	/**
	 * @depends testUserRegister
	 * Tests UserModel::userPasswordVerify()
	 */
	public function testUserPasswordVerify($id)
	{
		$result=UserModel::userPasswordVerify($login_id=$id,$password="654321",$is_admin=0);
		$this->assertEquals($result, $id);
	}
	
	/**
	 * @depends testUserRegister
	 * Tests UserModel::addSession()
	 */
	public function testAddSession($id)
	{
		$session=UserModel::addSession($userId=$id,$ip="127.0.0.1",$mac="test_mac");
		$this->assertTrue(!empty($session));
	}
	
	/**
	 * Tests UserModel::getUserIdByNickName()
	 * @depends testUserRegister
	 */
	public function testGetUserIdByNickName($id)
	{
		$ret=UserModel::getUserIdByNickName("i_am_test_update");
		var_dump($ret);
// 		$this->assertCount(1,$ret,'there are more then one "i_am_test_update" user');
	}
	
	/**
	 * Tests UserModel::getUserIdByEmail()
	 * @depends testUserRegister
	 */
	public function testGetUserIdByEmail($id)
	{
		$result=UserModel::getUserIdByEmail("test_email");
		var_dump($result);
		
	}
	
	/**
	 * @depends testUserRegister
	 * Tests UserModel::userDel()
	 */
	public function testUserDel($id)
	{
		$ret=UserModel::userDel($id,"654321");
		$this->assertEquals(1,$ret,'delete user failed');
		$result=UserModel::userPasswordVerify($login_id=$id,$password="654321",$is_admin=0);
		$this->assertNULL($result);
	}	
}

