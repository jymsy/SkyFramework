<?php
namespace tests\base\user\unit;

use base\user\models\BaseDevice;

/**
 * BaseDevice test case.
 * 
 * @author Zhengyun
 */
class BaseDeviceTest extends \Sky\test\TestCase{	
	public function testModel()
	{
		$obj=BaseDevice::model();
		$this->assertInstanceOf('base\user\models\BaseDevice',$obj);
	}
	
	/**
	 * Tests BaseDevice::addDevice()
	 */
	public function testAddDevice()
	{	
		$ret=BaseDevice::addDevice("test_mac_11","chip","model",1,"platform","barcode",40,'1');
		$this->assertEquals(1,$ret);
		return "test_mac_11";
	}
	
	/**
	 * Tests BaseDevice::getDeviceInfoByMac()
	 * @depends testAddDevice
	 */
	public function testGetDeviceInfoByMac($mac)
	{
		$devInfo=BaseDevice::getDeviceInfoByMac($mac);
		var_dump($devInfo);
		$this->assertCount(8,$devInfo);
	}
	
	/**
	 * Tests BaseDevice::updateDevice()
	 * @depends testAddDevice
	 */
	public function testUpdateDevice($mac)
	{
		$ret=BaseDevice::updateDevice($mac,"update_chip","update_model",11,"update_platform","update_barcode",41,'0');
		$this->assertEquals(1,$ret);
	}
	
	/**
	 * Tests BaseDevice::addDevUserMap()
	 * @depends testAddDevice
	 */
	public function testAddDevUserMap($mac)
	{
		$ret=BaseDevice::addDevUserMap($mac,1);
		$this->assertEquals(1,$ret);
	}
	
	/**
	 * Tests BaseDevice::queryAdmin()
	 * @depends testAddDevice
	 */
	public function testQueryAdmin($mac)
	{
		$ret=BaseDevice::queryAdmin($mac);
		var_dump($ret);
		$this->assertEquals($ret, '1');
	}
	
	/**
	 * Tests BaseDevice::delDevUserMap()
	 * @depends testAddDevice
	 */
	public function testDelDevUserMap($mac)
	{
		$ret=BaseDevice::delDevUserMap($mac,1);
		$this->assertEquals(1, $ret);
	}
	
	/**
	 * Tests BaseDevice::delDevice()
	 * @depends testAddDevice
	 */
	public function testDelDevice($mac)
	{
		$ret=BaseDevice::delDevice($mac);
		$this->assertEquals(1, $ret);
	}
}

