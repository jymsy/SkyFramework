<?php
namespace tests\base\upgrade\unit;

use base\upgrade\models\UpgradeClientModel;
/**
 * UpgradeClientModel test case.
 * 
 * @author Zhengyun
 */
class UpgradeClientModelTest extends \Sky\test\TestCase{
	public function testModel()
	{
		$obj=UpgradeClientModel::model();
		$this->assertInstanceOf('base\upgrade\models\UpgradeClientModel',$obj);
	}
	
	/**
	 * Tests UpgradeClientModel::getUpgradeVesionInfo()
	 */
	public function testGetUpgradeVesionInfo() 
	{
		$versionInfo=UpgradeClientModel::getUpgradeVesionInfo('HDP100E',"2S10","111111111111","system",13002039);
		var_dump($versionInfo);
		$this->assertCount(9, $versionInfo);
	}
	
	/**
	 * Tests UpgradeClientModel::getModuleUpgradeLists()
	 */
	public function testGetModuleUpgradeLists() 
	{
		$moduleList=UpgradeClientModel::getModuleUpgradeLists(2,"111111111111");
		var_dump($moduleList);
		$this->assertGreaterThan(0,count($moduleList));
	}
	
	/**
	 * Tests UpgradeClientModel::getUpgradeIdByMac()
	 */
	public function testGetUpgradeIdByMac() 
	{
		$id=UpgradeClientModel::getUpgradeIdByMac("bc83a76ca498");
		var_dump($id);
		$this->assertGreaterThan(0,$id);
	}
	
	/**
	 * Tests UpgradeClientModel::getDtvUpgradeInfo()
	 */
	public function testGetDtvUpgradeInfo() 
	{
		$dtvInfo=UpgradeClientModel::getDtvUpgradeInfo("9","0002");
		var_dump($dtvInfo);
		$this->assertGreaterThan(0,count($dtvInfo));
	}
	
	
	/**
	 * Tests UpgradeClientModel::getIPTVUpgradeInfo()
	 */
	public function testGetIPTVUpgradeInfo() {
		var_dump(UpgradeClientModel::getIPTVUpgradeInfo(1,"北京","E660E","2S20"));
	}
	
}

