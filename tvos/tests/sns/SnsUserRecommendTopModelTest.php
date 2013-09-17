<?php

namespace sns\models;
/**
 * SnsUserRecommendTopModel test case.
 */
class SnsUserRecommendTopModelTest extends \Sky\test\TestCase {
	
	/**
	 *
	 * @var SnsUserRecommendTopModel
	 */
	private $SnsUserRecommendTopModel;
	
	/**
	 * Prepares the environment before running a test.
	 */
	protected function setUp() {
		parent::setUp ();
		
		// TODO Auto-generated SnsUserRecommendTopModelTest::setUp()
		
		//$this->SnsUserRecommendTopModel = new SnsUserRecommendTopModel(/* parameters */);
	}
	
	/**
	 * Cleans up the environment after running a test.
	 */
	protected function tearDown() {
		// TODO Auto-generated SnsUserRecommendTopModelTest::tearDown()
		$this->SnsUserRecommendTopModel = null;
		
		parent::tearDown ();
	}
	
	/**
	 * Constructs the test case.
	 */
	public function __construct() {
		// TODO Auto-generated constructor
	}
	
	/**
	 * Tests SnsUserRecommendTopModel::model()
	 */
	public function testModel() {
		// TODO Auto-generated SnsUserRecommendTopModelTest::testModel()
		$this->markTestIncomplete ( "model test not implemented" );
		
		SnsUserRecommendTopModel::model(/* parameters */);
	}
	
	/**
	 * Tests SnsUserRecommendTopModel::insertRecommendTop()
	 */
	public function testInsertRecommendTop() {
		// TODO Auto-generated
		// SnsUserRecommendTopModelTest::testInsertRecommendTop()
		//$this->markTestIncomplete ( "insertRecommendTop test not implemented" );
		
		SnsUserRecommendTopModel::insertRecommendTop(9999,9999,1,0);
	}
	
	/**
	 * Tests SnsUserRecommendTopModel::getUserShareTop()
	 */
	public function testGetUserShareTop() {
		// TODO Auto-generated
		// SnsUserRecommendTopModelTest::testGetUserShareTop()
		//$this->markTestIncomplete ( "getUserShareTop test not implemented" );
		
		SnsUserRecommendTopModel::getUserShareTop(9);
	}
	
	/**
	 * Tests SnsUserRecommendTopModel::updateRecommendTop()
	 */
	public function testUpdateRecommendTop() {
		// TODO Auto-generated
		// SnsUserRecommendTopModelTest::testUpdateRecommendTop()
		//$this->markTestIncomplete ( "updateRecommendTop test not implemented" );
		
		SnsUserRecommendTopModel::updateRecommendTop(0,9999);
	}
	
	/**
	 * Tests SnsUserRecommendTopModel::getUserIdByType()
	 */
	public function testGetUserIdByType() {
		// TODO Auto-generated
		// SnsUserRecommendTopModelTest::testGetUserIdByType()
		//$this->markTestIncomplete ( "getUserIdByType test not implemented" );
		
		SnsUserRecommendTopModel::getUserIdByType(1);
	}
}

