<?php
namespace tests\base\user\unit;

use base\user\models\UserUserMapModel;

/**
 * UserUserMapModel test case.
 * 
 * @author Zhengyun
 */
class UserUserMapModelTest extends \Sky\test\TestCase{
	public function testModel()
	{
		$obj=UserUserMapModel::model();
		$this->assertInstanceOf('base\user\models\UserUserMapModel', $obj);
	}
	
	/**
	 * Tests UserUserMapModel::addUserUserMap()
	 */
	public function testAddUserUserMap()
	{				
		$ret=UserUserMapModel::addUserUserMap(11001001,11001002,1);
		$this->assertEquals(1,$ret);
	}
	
	/**
	 * Tests UserUserMapModel::queryUserUserMap()
	 */
	public function testQueryUserUserMap()
	{				
		$sub_user_id=UserUserMapModel::queryUserUserMap(11001001,1);
		var_dump($sub_user_id);
		if(!is_array($sub_user_id))
			$this->assertEquals(11001002, $sub_user_id);
		else
		{
			$flag=false;
			foreach ($sub_user_id as $user)
			{
					var_dump($user['userId']);	
					if($user['userId']==11001002){
						$flag=true;
						break;
					}
						
			}
			$this->assertTrue($flag);
		}
			
	}
	
	/**
	 * Tests UserUserMapModel::queryAdminByUser()
	 */
	public function testQueryAdminByUser()
	{		
		$admin_id=UserUserMapModel::queryAdminByUser(11001002,1);
		$this->assertEquals(11001001, $admin_id);
	}
	
	/**
	 * Tests UserUserMapModel::delUserUserMap()
	 */
	public function testDelUserUserMap()
	{	
		$ret=UserUserMapModel::delUserUserMap(11001001,11001002,1);
		$this->assertEquals(1,$ret);
	}
}

