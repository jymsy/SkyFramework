<?php

namespace sns\models;

/**
 * SnsCollectModel test case.
 */
class SnsCollectModelTest extends \Sky\test\TestCase {
	
	/**
	 *
	 * @var SnsCollectModel
	 */
	private $SnsCollectModel;
	
	/**
	 * Prepares the environment before running a test.
	 */
	protected function setUp() {
		parent::setUp ();
		
		// TODO Auto-generated SnsCollectModelTest::setUp()
		
		//$this->SnsCollectModel = new SnsCollectModel(/* parameters */);
	}
	
	/**
	 * Cleans up the environment after running a test.
	 */
	protected function tearDown() {
		// TODO Auto-generated SnsCollectModelTest::tearDown()
		$this->SnsCollectModel = null;
		
		parent::tearDown ();
	}
	
	/**
	 * Constructs the test case.
	 */
	public function __construct() {
		// TODO Auto-generated constructor
	}
	
	/**
	 * Tests SnsCollectModel::model()
	 */
	public function testModel() {
		// TODO Auto-generated SnsCollectModelTest::testModel()
		$this->markTestIncomplete ( "model test not implemented" );
		
		SnsCollectModel::model(/* parameters */);
	}
	
	/**
	 * Tests SnsCollectModel::insertCollect()
	 */
	public function testInsertCollect() {
		// TODO Auto-generated SnsCollectModelTest::testInsertCollect()
		//$this->markTestIncomplete ( "insertCollect test not implemented" );
		
		SnsCollectModel::insertCollect(9999);
	}
	
	/**
	 * Tests SnsCollectModel::insertCollectDetail()
	 */
	public function testInsertCollectDetail() {
		// TODO Auto-generated SnsCollectModelTest::testInsertCollectDetail()
		//$this->markTestIncomplete ( "insertCollectDetail test not implemented" );
		
		SnsCollectModel::insertCollectDetail(9999,9999);
	}
	
	/**
	 * Tests SnsCollectModel::queryCollect()
	 */
	public function testQueryCollect() {
		// TODO Auto-generated SnsCollectModelTest::testQueryCollect()
		//$this->markTestIncomplete ( "queryCollect test not implemented" );
		
		SnsCollectModel::queryCollect(9999,'');
	}
	
	/**
	 * Tests SnsCollectModel::updateCollect()
	 */
	public function testUpdateCollect() {
		// TODO Auto-generated SnsCollectModelTest::testUpdateCollect()
		//$this->markTestIncomplete ( "updateCollect test not implemented" );
		
		SnsCollectModel::updateCollect(9999);
	}
	
	/**
	 * Tests SnsCollectModel::queryCollectDetailByCid()
	 */
	public function testQueryCollectDetailByCid() {
		// TODO Auto-generated
		// SnsCollectModelTest::testQueryCollectDetailByCid()
		//$this->markTestIncomplete ( "queryCollectDetailByCid test not implemented" );
		
		SnsCollectModel::queryCollectDetailByCid(9999,'');
	}
	
	/**
	 * Tests SnsCollectModel::queryCollectDetailByUid()
	 */
	public function testQueryCollectDetailByUid() {
		// TODO Auto-generated
		// SnsCollectModelTest::testQueryCollectDetailByUid()
		//$this->markTestIncomplete ( "queryCollectDetailByUid test not implemented" );
		
		SnsCollectModel::queryCollectDetailByUid(9999,'');
	}
	
	/**
	 * Tests SnsCollectModel::updateCollectDetail()
	 */
	public function testUpdateCollectDetail() {
		// TODO Auto-generated SnsCollectModelTest::testUpdateCollectDetail()
		$this->markTestIncomplete ( "updateCollectDetail test not implemented" );
		
		SnsCollectModel::updateCollectDetail(9999,1);
	}
	
	/**
	 * Tests SnsCollectModel::getCollectTotalByUid()
	 */
	public function testGetCollectTotalByUid() {
		// TODO Auto-generated SnsCollectModelTest::testGetCollectTotalByUid()
		$this->markTestIncomplete ( "getCollectTotalByUid test not implemented" );
		
		SnsCollectModel::getCollectTotalByUid(9999);
	}
}

