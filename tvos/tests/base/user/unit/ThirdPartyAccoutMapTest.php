<?php
namespace tests\base\user\unit;

use base\user\models\ThirdPartyAccoutMap;

/**
 * ThirdPartyAccoutMap test case.
 * 
 * @author Zhengyun
 */
class ThirdPartyAccoutMapTest extends \Sky\test\TestCase{
	public function testModel()
	{
		$obj=ThirdPartyAccoutMap::model();
		$this->assertInstanceOf('base\user\models\ThirdPartyAccoutMap', $obj);
	}

	/**
	 * Tests ThirdPartyAccoutMap::addThirdParty()
	 */
	public function testAddThirdParty()
	{
		$ret=ThirdPartyAccoutMap::addThirdParty("test@qq.com", 1, 1);
		$this->assertEquals(1, $ret);
	}
	
	/**
	 * Tests ThirdPartyAccoutMap::queryThirdParty()
	 */
	public function testQueryThirdParty()
	{
		$result=ThirdPartyAccoutMap::queryThirdParty("test@qq.com", 1);
		$this->assertEquals(1, $result);
	}
	
	
	
	/**
	 * Tests ThirdPartyAccoutMap::delThirdParty()
	 */
	public function testDelThirdParty()
	{
		// TODO Auto-generated ThirdPartyAccoutMapTest::testDelThirdParty()
		//$this->markTestIncomplete("delThirdParty test not implemented");
		
		$ret=ThirdPartyAccoutMap::delThirdParty("test@qq.com", 1, 1);
		$this->assertEquals(1,$ret);
		$result=ThirdPartyAccoutMap::queryThirdParty("test@qq.com", 1);
		$this->assertNull($result);
		
	}
	
}

