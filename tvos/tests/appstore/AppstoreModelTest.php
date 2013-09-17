<?php

namespace appstore\models;

/**
 * AppstoreModel test case.
 */
class AppstoreModelTest extends \Sky\test\TestCase {
	
	/**
	 *
	 * @var AppstoreModel
	 */
	private $AppstoreModel;
	
	/**
	 * Prepares the environment before running a test.
	 */
	protected function setUp() {
		parent::setUp ();
		
		// TODO Auto-generated AppstoreModelTest::setUp()
		
		//$this->AppstoreModel = new AppstoreModel(/* parameters */);
	}
	
	/**
	 * Cleans up the environment after running a test.
	 */
	protected function tearDown() {
		// TODO Auto-generated AppstoreModelTest::tearDown()
		$this->AppstoreModel = null;
		
		parent::tearDown ();
	}
	
	/**
	 * Constructs the test case.
	 */
	public function __construct() {
		// TODO Auto-generated constructor
	}
	
	/**
	 * Tests AppstoreModel::model()
	 */
	public function testModel() {
		// TODO Auto-generated AppstoreModelTest::testModel()
		$this->markTestIncomplete ( "model test not implemented" );
		
		AppstoreModel::model(/* parameters */);
	}
	
	/**
	 * Tests AppstoreModel::getCategory()
	 */
	public function testGetCategory() {
		// TODO Auto-generated AppstoreModelTest::testGetCategory()
		//$this->markTestIncomplete ( "getCategory test not implemented" );
		
		var_dump(AppstoreModel::getCategory());
	}
	
	/**
	 * Tests AppstoreModel::getApp()
	 */
	public function testGetApp() {
		// TODO Auto-generated AppstoreModelTest::testGetApp()
		//$this->markTestIncomplete ( "getApp test not implemented" );
		
		var_dump(AppstoreModel::getApp('AM7366','182',1000,0));
	}
	
	/**
	 * Tests AppstoreModel::getAppCount()
	 */
	public function testGetAppCount() {
		// TODO Auto-generated AppstoreModelTest::testGetAppCount()
		//$this->markTestIncomplete ( "getAppCount test not implemented" );
		
		var_dump(AppstoreModel::getAppCount('AM7366','182'));
	}
	
	/**
	 * Tests AppstoreModel::getAppDetail()
	 */
	public function testGetAppDetail() {
		// TODO Auto-generated AppstoreModelTest::testGetAppDetail()
		//$this->markTestIncomplete ( "getAppDetail test not implemented" );
		
		var_dump(AppstoreModel::getAppDetail(4111));
	}
	
	/**
	 * Tests AppstoreModel::searchApp()
	 */
	public function testSearchApp() {
		// TODO Auto-generated AppstoreModelTest::testSearchApp()
		//$this->markTestIncomplete ( "searchApp test not implemented" );
		
		var_dump(AppstoreModel::searchApp('AM7366','黄道吉日'));
	}
	
	/**
	 * Tests AppstoreModel::searchAppCount()
	 */
	public function testSearchAppCount() {
		// TODO Auto-generated AppstoreModelTest::testSearchAppCount()
		//$this->markTestIncomplete ( "searchAppCount test not implemented" );
		
		var_dump(AppstoreModel::searchAppCount('AM7366','黄道吉日'));
	}
	
	
}

