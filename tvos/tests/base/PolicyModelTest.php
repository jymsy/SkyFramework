<?php

namespace base\models;

/**
 * PolicyModel test case.
 */
class PolicyModelTest extends \Sky\test\TestCase {
	
	/**
	 *
	 * @var PolicyModel
	 */
	private $PolicyModel;
	
	/**
	 * Prepares the environment before running a test.
	 */
	protected function setUp() {
		parent::setUp ();
		
		// TODO Auto-generated PolicyModelTest::setUp()
		
		//$this->PolicyModel = new PolicyModel(/* parameters */);
	}
	
	/**
	 * Cleans up the environment after running a test.
	 */
	protected function tearDown() {
		// TODO Auto-generated PolicyModelTest::tearDown()
		$this->PolicyModel = null;
		
		parent::tearDown ();
	}
	
	/**
	 * Constructs the test case.
	 */
	public function __construct() {
		// TODO Auto-generated constructor
	}
	
	/**
	 * Tests PolicyModel::model()
	 */
	public function testModel() {
		// TODO Auto-generated PolicyModelTest::testModel()
		$this->markTestIncomplete ( "model test not implemented" );
		
		PolicyModel::model(/* parameters */);
	}
	
	/**
	 * Tests PolicyModel::queryPolicy()
	 */
	public function testQueryPolicy() {
		// TODO Auto-generated PolicyModelTest::testQueryPolicy()
		//$this->markTestIncomplete ( "queryPolicy test not implemented" );
		
		var_dump(PolicyModel::queryPolicy('api/ListSources','8R98','E790U','','','000000000001','0001',''));
	}
}

