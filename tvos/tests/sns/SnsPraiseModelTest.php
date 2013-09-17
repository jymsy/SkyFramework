<?php

namespace sns\models;
/**
 * SnsPraiseModel test case.
 */
class SnsPraiseModelTest extends \Sky\test\TestCase {
	
	/**
	 *
	 * @var SnsPraiseModel
	 */
	private $SnsPraiseModel;
	
	/**
	 * Prepares the environment before running a test.
	 */
	protected function setUp() {
		parent::setUp ();
		
		// TODO Auto-generated SnsPraiseModelTest::setUp()
		
		//$this->SnsPraiseModel = new SnsPraiseModel(/* parameters */);
	}
	
	/**
	 * Cleans up the environment after running a test.
	 */
	protected function tearDown() {
		// TODO Auto-generated SnsPraiseModelTest::tearDown()
		$this->SnsPraiseModel = null;
		
		parent::tearDown ();
	}
	
	/**
	 * Constructs the test case.
	 */
	public function __construct() {
		// TODO Auto-generated constructor
	}
	
	/**
	 * Tests SnsPraiseModel::model()
	 */
	public function testModel() {
		// TODO Auto-generated SnsPraiseModelTest::testModel()
		$this->markTestIncomplete ( "model test not implemented" );
		
		SnsPraiseModel::model(/* parameters */);
	}
	
	/**
	 * Tests SnsPraiseModel::insertPraise()
	 */
	public function testInsertPraise() {
		// TODO Auto-generated SnsPraiseModelTest::testInsertPraise()
		//$this->markTestIncomplete ( "insertPraise test not implemented" );
		
		SnsPraiseModel::insertPraise(9999);
	}
	
	/**
	 * Tests SnsPraiseModel::insertPraiseDetail()
	 */
	public function testInsertPraiseDetail() {
		// TODO Auto-generated SnsPraiseModelTest::testInsertPraiseDetail()
		//$this->markTestIncomplete ( "insertPraiseDetail test not implemented" );
		
		SnsPraiseModel::insertPraiseDetail(9999,9999);
	}
	
	/**
	 * Tests SnsPraiseModel::queryPraise()
	 */
	public function testQueryPraise() {
		// TODO Auto-generated SnsPraiseModelTest::testQueryPraise()
		//$this->markTestIncomplete ( "queryPraise test not implemented" );
		
		SnsPraiseModel::queryPraise(9999,'');
	}
	
	/**
	 * Tests SnsPraiseModel::updatePraise()
	 */
	public function testUpdatePraise() {
		// TODO Auto-generated SnsPraiseModelTest::testUpdatePraise()
		//$this->markTestIncomplete ( "updatePraise test not implemented" );
		
		SnsPraiseModel::updatePraise(9999);
	}
	
	/**
	 * Tests SnsPraiseModel::queryPraiseDetailByCid()
	 */
	public function testQueryPraiseDetailByCid() {
		// TODO Auto-generated SnsPraiseModelTest::testQueryPraiseDetailByCid()
		//$this->markTestIncomplete ( "queryPraiseDetailByCid test not implemented" );
		
		SnsPraiseModel::queryPraiseDetailByCid(9999,'');
	}
	
	/**
	 * Tests SnsPraiseModel::queryPraiseDetailByUid()
	 */
	public function testQueryPraiseDetailByUid() {
		// TODO Auto-generated SnsPraiseModelTest::testQueryPraiseDetailByUid()
		//$this->markTestIncomplete ( "queryPraiseDetailByUid test not implemented" );
		
		SnsPraiseModel::queryPraiseDetailByUid(9999,'');
	}
	
	/**
	 * Tests SnsPraiseModel::updatePraiseDetail()
	 */
	public function testUpdatePraiseDetail() {
		// TODO Auto-generated SnsPraiseModelTest::testUpdatePraiseDetail()
		//$this->markTestIncomplete ( "updatePraiseDetail test not implemented" );
		
		SnsPraiseModel::updatePraiseDetail(9999,1);
	}
	
	/**
	 * Tests SnsPraiseModel::getPraiseTotalByUid()
	 */
	public function testGetPraiseTotalByUid() {
		// TODO Auto-generated SnsPraiseModelTest::testGetPraiseTotalByUid()
		//$this->markTestIncomplete ( "getPraiseTotalByUid test not implemented" );
		
		SnsPraiseModel::getPraiseTotalByUid(9999);
	}
}

