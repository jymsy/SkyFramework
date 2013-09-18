<?php

namespace tests\base\terminal\unit;

use base\terminal\models\DeviceManageModel;
/**
 * DeviceManageModel test case.
 */
class DeviceManageModelTest extends \Sky\test\TestCase {
	
	/**
	 *
	 * @var DeviceManageModel
	 */
	private $DeviceManageModel;
	
	/**
	 * Prepares the environment before running a test.
	 */
	protected function setUp() {
		parent::setUp ();
		
		// TODO Auto-generated DeviceManageModelTest::setUp()
		
		//$this->DeviceManageModel = new DeviceManageModel(/* parameters */);
	}
	
	/**
	 * Cleans up the environment after running a test.
	 */
	protected function tearDown() {
		// TODO Auto-generated DeviceManageModelTest::tearDown()
		$this->DeviceManageModel = null;
		
		parent::tearDown ();
	}
	
	
	
	/**
	 * Tests DeviceManageModel::getDeviceCount()
	 */
	public function testGetDeviceCount() {
		// TODO Auto-generated DeviceManageModelTest::testGetDeviceCount()
		//$this->markTestIncomplete ( "getDeviceCount test not implemented" );
		
		var_dump(DeviceManageModel::getDeviceCount());
	}
	
	/**
	 * Tests DeviceManageModel::getDeviceList()
	 */
	public function testGetDeviceList() {
		// TODO Auto-generated DeviceManageModelTest::testGetDeviceList()
		//$this->markTestIncomplete ( "getDeviceList test not implemented" );
		
		var_dump(DeviceManageModel::getDeviceList(0,10));
	}
	
	/**
	 * Tests DeviceManageModel::searchDeviceCount()
	 */
	public function testSearchDeviceCount() {
		// TODO Auto-generated DeviceManageModelTest::testSearchDeviceCount()
		//$this->markTestIncomplete ( "searchDeviceCount test not implemented" );
		
		var_dump(DeviceManageModel::searchDeviceCount(array("dev_mac"=>"bc83a74291e5")));
	}
	
	/**
	 * Tests DeviceManageModel::searchDeviceDetail()
	 */
	public function testSearchDeviceDetail() {
		// TODO Auto-generated DeviceManageModelTest::testSearchDeviceDetail()
		//$this->markTestIncomplete ( "searchDeviceDetail test not implemented" );
		
		var_dump(DeviceManageModel::searchDeviceDetail(array("dev_mac"=>"bc83a74291e5"),0,10));
	}
	
	/**
	 * Tests DeviceManageModel::getDeviceModelAndChip()
	 */
	public function testGetDeviceModelAndChip(){
		var_dump(DeviceManageModel::getDeviceModelAndChip());
	}
}

