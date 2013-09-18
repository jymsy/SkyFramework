<?php
namespace tests\upgrade\unit;
use upgrade\models\UpgradeModel;

/**
 * UpgradeModel test case.
 */
class UpgradeModelTest extends \Sky\test\TestCase {
	
	/**
	 *
	 * @var UpgradeModel
	 */
	private $UpgradeModel;
	
	/**
	 * Prepares the environment before running a test.
	 */
	protected function setUp() {
		parent::setUp ();
		
		// TODO Auto-generated UpgradeModelTest::setUp()
		
		//$this->UpgradeModel = new UpgradeModel(/* parameters */);
	}
	
	/**
	 * Cleans up the environment after running a test.
	 */
	protected function tearDown() {
		// TODO Auto-generated UpgradeModelTest::tearDown()
		$this->UpgradeModel = null;
		
		parent::tearDown ();
	}
		
	
	/**
	 * Tests UpgradeModel::insertModuleUpgrade()
	 */
	public function testInsertModuleUpgrade() {
		// TODO Auto-generated UpgradeModelTest::testInsertModuleUpgrade()
		//$this->markTestIncomplete ( "insertModuleUpgrade test not implemented" );
		$result =UpgradeModel::insertModuleUpgrade($arr=array('upgrade_id'=>1,'module_name'=>'apk','download_url'=>'jijkjoijkj.url','module_version'=>998,'module_type'=>1,'is_enforce'=>0,'mac_start'=>'888888888888','mac_end'=>'999999999999','filesize'=>'89k','md5'=>'md5_value','desc'=>'desc','icon'=>'http://icon.url','bag_name'=>'app_bag_name'));
		var_dump($result);
		return $result;
	}
	
	/**
	 * 
	 * Tests UpgradeModel::updateModuleUpgrade()
	 */
	public function testUpdateModuleUpgrade() {
		// TODO Auto-generated UpgradeModelTest::testUpdateModuleUpgrade()
		//$this->markTestIncomplete ( "updateModuleUpgrade test not implemented" );
		
		UpgradeModel::updateModuleUpgrade($arr=array('upgrade_id'=>1,'module_name'=>'apk','download_url'=>'jijkjoijkj.url','module_version'=>998,'module_type'=>1,'is_enforce'=>0,'mac_start'=>'888888888888','mac_end'=>'999999999999','filesize'=>'89k','md5'=>'md5_value','desc'=>'desc','upgrade_module_id'=>27,'icon'=>'http://upgrade.icon.url','bag_name'=>'update_app_bag_name'));
	}
	
	/**
	 * Tests UpgradeModel::searchModuleUpgradeCount()
	 */
	public function testSearchModuleUpgradeCount() {
		// TODO Auto-generated UpgradeModelTest::testSearchModuleUpgradeCount()
		//$this->markTestIncomplete ( "searchModuleUpgradeCount test not implemented" );
	
		$result=UpgradeModel::searchModuleUpgradeCount(1,array('upgrade_module_id'=>27));
		var_dump($result);
	}
	
	/**
	 * Tests UpgradeModel::searchModuleUpgrade()
	 */
	public function testSearchModuleUpgrade() {
		// TODO Auto-generated UpgradeModelTest::testSearchModuleUpgrade()
		//$this->markTestIncomplete ( "searchModuleUpgrade test not implemented" );
		
		$result=UpgradeModel::searchModuleUpgrade(1,array('upgrade_module_id'=>27),0,10);
		var_dump($result);
	}
	
	/**
	 * Tests UpgradeModel::getModuleUpgradeCount()
	 */
	public function testGetModuleUpgradeCount() {
		// TODO Auto-generated UpgradeModelTest::testGetModuleUpgradeCount()
		//$this->markTestIncomplete ( "getModuleUpgradeCount test not implemented" );
	
		$result=UpgradeModel::getModuleUpgradeCount(2);
		var_dump($result);
	}
	
	/**
	 * Tests UpgradeModel::getModuleUpgradeLists()
	 */
	public function testGetModuleUpgradeLists() {
		// TODO Auto-generated UpgradeModelTest::testGetModuleUpgradeLists()
		//$this->markTestIncomplete ( "getModuleUpgradeLists test not implemented" );
		
		$result=UpgradeModel::getModuleUpgradeLists(2,0,10);
		var_dump($result);
	}
	
	
	
	/**
	 * @depends testInsertModuleUpgrade
	 * Tests UpgradeModel::updateUpgradeModDownloadUrl()
	 */
	public function testUpdateUpgradeModDownloadUrl($id) {
		// TODO Auto-generated
		// UpgradeModelTest::testUpdateUpgradeModDownloadUrl()
		//$this->markTestIncomplete ( "updateUpgradeModDownloadUrl test not implemented" );
		
		UpgradeModel::updateUpgradeModDownloadUrl($id,"update_download_url");
	}
	
	/**
	 * Tests UpgradeModel::insertSysUpgrade()
	 */
	public function testInsertSysUpgrade() {
		// TODO Auto-generated UpgradeModelTest::testInsertSysUpgrade()
		//$this->markTestIncomplete ( "insertSysUpgrade test not implemented" );
	
		$upgrade_id=UpgradeModel::insertSysUpgrade(array('thirdparty_info'=>'thirdparty_info','screen_size'=>'screen_size','bag_type'=>'bag_type','platform'=>'platform','core_chip'=>'core_chip','core_style'=>'core_style','init_version'=>'version','final_version'=>'version','mac_start'=>'111111111111','mac_end'=>'666666666666','desc'=>'desc','is_import'=>0,'download_url'=>'deefdfd','md5'=>'mddddd5','package_owner'=>'phoebe','package_type'=>0,'area'=>'111111','filesize'=>987,'desc'=>'desc'));
		return $upgrade_id;
	}
	
	/**
	 * Tests UpgradeModel::getSysUpgrade()
	 */
	public function testGetSysUpgrade() {
		// TODO Auto-generated UpgradeModelTest::testGetSysUpgrade()
		//$this->markTestIncomplete ( "getSysUpgrade test not implemented" );
		
		$result=UpgradeModel::getSysUpgrade(0,10);
		var_dump($result);
	}
	
	/**
	 * Tests UpgradeModel::getSysUpgradeCount()
	 */
	public function testGetSysUpgradeCount() {
		// TODO Auto-generated UpgradeModelTest::testGetSysUpgradeCount()
		//$this->markTestIncomplete ( "getSysUpgradeCount test not implemented" );
		
		$result=UpgradeModel::getSysUpgradeCount();
		var_dump($result);
	}
	
	
	
	/**
	 * @depends testInsertSysUpgrade
	 * Tests UpgradeModel::updateSysUpgradeIntroduce()
	 */
	public function testUpdateSysUpgradeIntroduce($upgrade_id) {
		// TODO Auto-generated UpgradeModelTest::testUpdateSysUpgradeIntroduce()
		//$this->markTestIncomplete ( "updateSysUpgradeIntroduce test not implemented" );
		
		UpgradeModel::updateSysUpgradeIntroduce($upgrade_id,"introduct_page");
		
	}
	
	/**
	 * @depends testInsertSysUpgrade
	 * Tests UpgradeModel::updateSysUpgradeDownloadUrl()
	 */
	public function testUpdateSysUpgradeDownloadUrl($upgrade_id) {
		// TODO Auto-generated
		// UpgradeModelTest::testUpdateSysUpgradeDownloadUrl()
		//$this->markTestIncomplete ( "updateSysUpgradeDownloadUrl test not implemented" );
		
		UpgradeModel::updateSysUpgradeDownloadUrl($upgrade_id,"upgrade_download_url");
	}
	
	/**
	 * @depends testInsertSysUpgrade
	 * Tests UpgradeModel::getSysUpgradeById()
	 */
	public function testGetSysUpgradeById($upgrade_id) {
		// TODO Auto-generated UpgradeModelTest::testGetSysUpgradeById()
		//$this->markTestIncomplete ( "getSysUpgradeById test not implemented" );
		
		$result=UpgradeModel::getSysUpgradeById($upgrade_id);
		var_dump($result);
	}
	
	/**
	 * @depends testInsertSysUpgrade
	 * Tests UpgradeModel::updateSysUpgrade()
	 */
	public function testUpdateSysUpgrade($upgrade_id) {
		// TODO Auto-generated UpgradeModelTest::testUpdateSysUpgrade()
		//$this->markTestIncomplete ( "updateSysUpgrade test not implemented" );
		
		UpgradeModel::updateSysUpgrade(array('introduce_page'=>'introduce_page','desc'=>'desc','thirdparty_info'=>'thirdparty_info','screen_size'=>'screen_size','bag_type'=>'bag_type','platform'=>'platform','core_chip'=>'core_chip','core_style'=>'core_style','final_version'=>'version','init_version'=>'version','mac_start'=>'111111111111','mac_end'=>'666666666666','desc'=>'desc','is_import'=>0,'download_url'=>'deefdfd','md5'=>'mddddd5','package_owner'=>'phoebe','package_type'=>0,'area'=>'111111','filesize'=>987,'desc'=>'desc','upgrade_id'=>$upgrade_id));
	}
	
	
	
	/**
	 * @depends testInsertSysUpgrade
	 * Tests UpgradeModel::importUpdateSys()
	 */
	public function testImportUpdateSys($upgrade_id) {
		// TODO Auto-generated UpgradeModelTest::testImportUpdateSys()
		//$this->markTestIncomplete ( "importUpdateSys test not implemented" );
		
		UpgradeModel::importUpdateSys($upgrade_id,"download_url","md5_value",1);
	}
	
	/**
	 * Tests UpgradeModel::judgeSysUpgrade()
	 */
	public function testJudgeSysUpgrade() {
		// TODO Auto-generated UpgradeModelTest::testJudgeSysUpgrade()
		//$this->markTestIncomplete ( "judgeSysUpgrade test not implemented" );
		
		$cnt=UpgradeModel::judgeSysUpgrade("core_sytle","core_chip","version","version");
		var_dump($cnt);
	}
	
	/**
	 * Tests UpgradeModel::searchUpgradeInfo()
	 */
	public function testSearchUpgradeInfo() {
		// TODO Auto-generated UpgradeModelTest::testSearchUpgradeInfo()
		//$this->markTestIncomplete ( "searchUpgradeInfo test not implemented" );
		
		$result=UpgradeModel::searchUpgradeInfo(array('upgrade_id'=>1),0,10);
		var_dump($result);
	}
	
	/**
	 * Tests UpgradeModel::searchUpgradeCount()
	 */
	public function testSearchUpgradeCount() {
		// TODO Auto-generated UpgradeModelTest::testSearchUpgradeCount()
		//$this->markTestIncomplete ( "searchUpgradeCount test not implemented" );
		
		$result=UpgradeModel::searchUpgradeCount(array('upgrade_id'=>1));
		var_dump($result);
	}
	
	/**
	 * Tests UpgradeModel::checkPackageExistByMD5()
	 */
	public function testCheckPackageExistByMD5() {
		// TODO Auto-generated UpgradeModelTest::testCheckPackageExistByMD5()
		//$this->markTestIncomplete ( "checkPackageExistByMD5 test not implemented" );
		
		$result=UpgradeModel::checkPackageExistByMD5("md5_value");
		var_dump($result);
		
	}
	
	/**
	 * Tests UpgradeModel::checkModuleExistByMD5()
	 */
	public function testCheckModuleExistByMD5() {
		// TODO Auto-generated UpgradeModelTest::testCheckModuleExistByMD5()
		//$this->markTestIncomplete ( "checkModuleExistByMD5 test not implemented" );
		
		$result=UpgradeModel::checkModuleExistByMD5("md5_value");
		var_dump($result);
	}
	
	/**
	 * Tests UpgradeModel::checkpackageExist()
	 */
	public function testCheckpackageExist() {
		// TODO Auto-generated UpgradeModelTest::testCheckpackageExist()
		//$this->markTestIncomplete ( "checkpackageExist test not implemented" );
		
		$cnt=UpgradeModel::checkpackageExist("core_sytle","core_chip","version","version");
		var_dump($cnt);
	}
	
	/**
	 * Tests UpgradeModel::getSysUpgradeByArea()
	 */
	public function testGetSysUpgradeByArea() {
		// TODO Auto-generated UpgradeModelTest::testGetSysUpgradeByArea()
		//$this->markTestIncomplete ( "getSysUpgradeByArea test not implemented" );
		
		$result=UpgradeModel::getSysUpgradeByArea("000000",0,10);
		var_dump($result);
	}
	
	/**
	 * Tests UpgradeModel::getSysUpgradeCountByArea()
	 */
	public function testGetSysUpgradeCountByArea() {
		// TODO Auto-generated UpgradeModelTest::testGetSysUpgradeCountByArea()
		//$this->markTestIncomplete ( "getSysUpgradeCountByArea test not implemented" );
		
		$result=UpgradeModel::getSysUpgradeCountByArea("000000");
		var_dump($result);
	}
	
	/**
	 * Tests UpgradeModel::searchSysUpgradeByArea()
	 */
	public function testSearchSysUpgradeByArea() {
		// TODO Auto-generated UpgradeModelTest::testSearchSysUpgradeByArea()
		//$this->markTestIncomplete ( "searchSysUpgradeByArea test not implemented" );
		
		$result=UpgradeModel::searchSysUpgradeByArea("000000",0,10, array('upgrade_id'=>1));
		var_dump($result);
	}
	
	/**
	 * Tests UpgradeModel::searchSysUpgradeCountByArea()
	 */
	public function testSearchSysUpgradeCountByArea() {
		// TODO Auto-generated
		// UpgradeModelTest::testSearchSysUpgradeCountByArea()
		//$this->markTestIncomplete ( "searchSysUpgradeCountByArea test not implemented" );
		
		$result=UpgradeModel::searchSysUpgradeCountByArea("000000", array('upgrade_id'=>1));
		var_dump($result);
	}
	
	/**
	 * @depends testInsertModuleUpgrade
	 * Tests UpgradeModel::deleteModuleUpgrade()
	 */
	public function testDeleteModuleUpgrade($id) {
		// TODO Auto-generated UpgradeModelTest::testDeleteModuleUpgrade()
		//$this->markTestIncomplete ( "deleteModuleUpgrade test not implemented" );
	
		UpgradeModel::deleteModuleUpgrade($id);
	}
	
	/**
	 * @depends testInsertSysUpgrade
	 * Tests UpgradeModel::deleteSysUpgrade()
	 */
	public function testDeleteSysUpgrade($id) {
		// TODO Auto-generated UpgradeModelTest::testDeleteSysUpgrade()
		//$this->markTestIncomplete ( "deleteSysUpgrade test not implemented" );
	
		UpgradeModel::deleteSysUpgrade($id);
	}
}

