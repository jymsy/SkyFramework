<?php

namespace res\models;

/**
 * PromiseModel test case.
 */
class PromiseModelTest extends \Sky\test\TestCase {
	
	/**
	 *
	 * @var PromiseModel
	 */
	private $PromiseModel;
	
	/**
	 * Prepares the environment before running a test.
	 */
	protected function setUp() {
		parent::setUp ();
		
		// TODO Auto-generated PromiseModelTest::setUp()
		
		//$this->PromiseModel = new PromiseModel(/* parameters */);
	}
	
	/**
	 * Cleans up the environment after running a test.
	 */
	protected function tearDown() {
		// TODO Auto-generated PromiseModelTest::tearDown()
		$this->PromiseModel = null;
		
		parent::tearDown ();
	}
	
	/**
	 * Constructs the test case.
	 */
	public function __construct() {
		// TODO Auto-generated constructor
	}
	
	/**
	 * Tests PromiseModel::model()
	 */
	public function testModel() {
		// TODO Auto-generated PromiseModelTest::testModel()
		$this->markTestIncomplete ( "model test not implemented" );
		
		PromiseModel::model(/* parameters */);
	}
	
	/**
	 * Tests PromiseModel::listpromise()
	 */
	public function testListpromise() {
		
		
		var_dump(PromiseModel::listpromise('epg_channel_logo',''));
	}
	
	/**
	 * Tests PromiseModel::insertpromise()
	 */
	public function testInsertpromise() {

		
		var_dump(PromiseModel::insertpromise('1','1','1'));
	}
}

