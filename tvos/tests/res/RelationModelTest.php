<?php

namespace res\models;

/**
 * RelationModel test case.
 */
class RelationModelTest extends \Sky\test\TestCase {
	
	/**
	 *
	 * @var RelationModel
	 */
	private $RelationModel;
	
	/**
	 * Prepares the environment before running a test.
	 */
	protected function setUp() {
		parent::setUp ();
		
		// TODO Auto-generated RelationModelTest::setUp()
		
		//$this->RelationModel = new RelationModel(/* parameters */);
	}
	
	/**
	 * Cleans up the environment after running a test.
	 */
	protected function tearDown() {
		// TODO Auto-generated RelationModelTest::tearDown()
		$this->RelationModel = null;
		
		parent::tearDown ();
	}
	
	/**
	 * Constructs the test case.
	 */
	public function __construct() {
		// TODO Auto-generated constructor
	}
	
	/**
	 * Tests RelationModel::model()
	 */
	public function testModel() {
		// TODO Auto-generated RelationModelTest::testModel()
		$this->markTestIncomplete ( "model test not implemented" );
		
		RelationModel::model(/* parameters */);
	}
	
	/**
	 * Tests RelationModel::showrelationbytarget()
	 */
	public function testShowrelationbytarget() {

		
		var_dump(RelationModel::showrelationbytarget(1,''));
	}
	
	/**
	 * Tests RelationModel::showrelationbytitle()
	 */
	public function testShowrelationbytitle() {
		
		
		var_dump(RelationModel::showrelationbytitle(1,'我',''));
	}
	
	/**
	 * Tests RelationModel::showrelationbytwoid()
	 */
	public function testShowrelationbytwoid() {
	
		
		var_dump(RelationModel::showrelationbytwoid(1,1,''));
	}
	
	/**
	 * Tests RelationModel::queryarelationmark()
	 */
	public function testQueryarelationmark() {
	
		
		var_dump(RelationModel::queryarelationmark(1,1,''));
	}
}

