<?php

namespace res\models;

/**
 * FilterModel test case.
 */
class FilterModelTest extends \Sky\test\TestCase {
	
	/**
	 *
	 * @var FilterModel
	 */
	private $FilterModel;
	
	/**
	 * Prepares the environment before running a test.
	 */
	protected function setUp() {
		parent::setUp ();
		
		// TODO Auto-generated FilterModelTest::setUp()
		
		//$this->FilterModel = new FilterModel(/* parameters */);
	}
	
	/**
	 * Cleans up the environment after running a test.
	 */
	protected function tearDown() {
		// TODO Auto-generated FilterModelTest::tearDown()
		$this->FilterModel = null;
		
		parent::tearDown ();
	}
	
	/**
	 * Constructs the test case.
	 */
	public function __construct() {
		// TODO Auto-generated constructor
	}
	
	/**
	 * Tests FilterModel::model()
	 */
	public function testModel() {
		// TODO Auto-generated FilterModelTest::testModel()
		$this->markTestIncomplete ( "model test not implemented" );
		
		FilterModel::model(/* parameters */);
	}
	
	/**
	 * Tests FilterModel::queryenumsbycid()
	 */
	public function testQueryenumsbycid() {
	
		
		var_dump(FilterModel::queryenumsbycid(1,''));
	}
	
	/**
	 * Tests FilterModel::queryenumsbypid()
	 */
	public function testQueryenumsbypid() {
	
		
		var_dump(FilterModel::queryenumsbypid(1,''));
	}
}

