<?php

namespace tests\resource\unit;
use resource\models\WebSiteModel;
/**
 * WebSiteModel test case.
 */
class WebSiteModelTest extends \Sky\test\TestCase {
	
	
	/**
	 *
	 * @var WebSiteModel
	 */
	private $WebSiteModel;
	
	/**
	 * Prepares the environment before running a test.
	 */
	protected function setUp() {
		parent::setUp ();
	
		// TODO Auto-generated WebSiteModelTest::setUp()
	
		//$this->WebSiteModel = new WebSiteModel(/* parameters */);
	}
	
	/**
	 * Cleans up the environment after running a test.
	 */
	protected function tearDown() {
		// TODO Auto-generated WebSiteModelTest::tearDown()
		$this->WebSiteModel = null;
	
		parent::tearDown ();
	}
	
	
	
	/**
	 * Tests WebSiteModel::getWebSiteList()
	 */
	public function testGetWebSiteList() {
		// TODO Auto-generated WebSiteModelTest::testGetWebSiteList()
		//$this->markTestIncomplete ( "getWebSiteList test not implemented" );
	
		var_dump(WebSiteModel::getWebSiteList(0,10,10001,array('category_id'=>'desc')));
	}
	/**
	 * Tests WebSiteModel::getWebSiteCount()
	 */
	public function testGetWebSiteCount() {
		// TODO Auto-generated WebSiteModelTest::testGetWebSiteList()
		//$this->markTestIncomplete ( "getWebSiteList test not implemented" );
	
		var_dump(WebSiteModel::getWebSiteCount());
	}
	/**
	 * Tests WebSiteModel::getOneWebSiteById()
	 */
	public function testGetOneWebSiteById() {
		// TODO Auto-generated WebSiteModelTest::testGetWebSiteOne()
		//$this->markTestIncomplete ( "getWebSiteOne test not implemented" );
	
		var_dump(WebSiteModel::getOneWebSiteById(1));
	}
	
	/**
	 * Tests WebSiteModel::insertWebSite()
	 */
	public function testAddWebSite() {
		// TODO Auto-generated WebSiteModelTest::testAddWebSite()
		//$this->markTestIncomplete ( "addWebSite test not implemented" );
	
		WebSiteModel::insertWebSite(array('site_name'=>'site_name','site_url'=>'urlurl','site_logo'=>'logo','site_big_logo'=>'big','category_id'=>1));
	}
	
	/**
	 * Tests WebSiteModel::updateWebSite()
	 */
	public function testAlterWebSite() {
		// TODO Auto-generated WebSiteModelTest::testAlterWebSite()
		//$this->markTestIncomplete ( "alterWebSite test not implemented" );
	
		WebSiteModel::updateWebSite(array('site_name'=>'site_name_u','site_url'=>'urlurl_u','site_logo'=>'logo_u','site_big_logo'=>'big_u','category_id'=>2),4);
	}
	
	/**
	 * Tests WebSiteModel::deleteWebSite()
	 */
	public function testDeleteWebSite() {
		// TODO Auto-generated WebSiteModelTest::testDeleteWebSite()
		//$this->markTestIncomplete ( "deleteWebSite test not implemented" );
	
		WebSiteModel::deleteWebSite(4);
	}
	
	/**
	 * Tests WebSiteModel::getCategoryList()
	 */
	public function testGetCategoryList() {
		// TODO Auto-generated WebSiteModelTest::testGetCategoryList()
		//$this->markTestIncomplete ( "getCategoryList test not implemented" );
	
		var_dump(WebSiteModel::getCategoryList());
	}
	
	
	/**
	 * Tests WebSiteModel::searchWebSiteList()
	 */
	public function testSearchWebSiteList() {
		// TODO Auto-generated WebSiteModelTest::testSearchWebSiteList()
		//$this->markTestIncomplete ( "searchWebSiteList test not implemented" );
	
		var_dump(WebSiteModel::searchWebSiteList(array('category_id'=>"10001"),0,10));
	}
	
	/**
	 * Tests WebSiteModel::searchWebSiteCount()
	 */
	public function testSearchWebSiteCount() {
		// TODO Auto-generated WebSiteModelTest::testSearchWebSiteCount()
		//$this->markTestIncomplete ( "searchWebSiteCount test not implemented" );
	
		var_dump(WebSiteModel::searchWebSiteCount(array('category_id'=>"10001")));
	}
}

