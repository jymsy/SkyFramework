<?php

namespace res\models;


/**
 * LogPlayUrlModel test case.
 */
class LogPlayUrlModelTest extends \Sky\test\TestCase {
	
	/**
	 *
	 * @var LogPlayUrlModel
	 */
	private $LogPlayUrlModel;
	
	/**
	 * Prepares the environment before running a test.
	 */
	protected function setUp() {
		parent::setUp ();
		
		// TODO Auto-generated LogPlayUrlModelTest::setUp()
		
		//$this->LogPlayUrlModel = new LogPlayUrlModel(/* parameters */);
	}
	
	/**
	 * Cleans up the environment after running a test.
	 */
	protected function tearDown() {
		// TODO Auto-generated LogPlayUrlModelTest::tearDown()
		$this->LogPlayUrlModel = null;
		
		parent::tearDown ();
	}
	
	/**
	 * Constructs the test case.
	 */
	public function __construct() {
		// TODO Auto-generated constructor
	}
	
	/**
	 * Tests LogPlayUrlModel::model()
	 */
	public function testModel() {
		// TODO Auto-generated LogPlayUrlModelTest::testModel()
		$this->markTestIncomplete ( "model test not implemented" );
		
		LogPlayUrlModel::model(/* parameters */);
	}
	
	/**
	 * Tests LogPlayUrlModel::InsertLogPlayUrl()
	 */
	public function testInsertLogPlayUrl() {
		// TODO Auto-generated LogPlayUrlModelTest::testInsertLogPlayUrl()
		//$this->markTestIncomplete ( "InsertLogPlayUrl test not implemented" );
		
		var_dump(LogPlayUrlModel::InsertLogPlayUrl('1','1','1','1'));
	}
}

