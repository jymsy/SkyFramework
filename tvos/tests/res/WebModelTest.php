<?php

namespace res\models;

/**
 * WebModel test case.
 */
class WebModelTest extends \Sky\test\TestCase {
	
	/**
	 *
	 * @var WebModel
	 */
	private $WebModel;
	
	/**
	 * Prepares the environment before running a test.
	 */
	protected function setUp() {
		parent::setUp ();
		
		// TODO Auto-generated WebModelTest::setUp()
		
		//$this->WebModel = new WebModel(/* parameters */);
	}
	
	/**
	 * Cleans up the environment after running a test.
	 */
	protected function tearDown() {
		// TODO Auto-generated WebModelTest::tearDown()
		$this->WebModel = null;
		
		parent::tearDown ();
	}
	
	/**
	 * Constructs the test case.
	 */
	public function __construct() {
		// TODO Auto-generated constructor
	}
	
	/**
	 * Tests WebModel::model()
	 */
	public function testModel() {
		// TODO Auto-generated WebModelTest::testModel()
		$this->markTestIncomplete ( "model test not implemented" );
		
		WebModel::model(/* parameters */);
	}
	
	/**
	 * Tests WebModel::showweb()
	 */
	public function testShowweb() {
	
		
		var_dump(WebModel::showweb(array(1,2,3),''));
	}
	
	/**
	 * Tests WebModel::listwebcount()
	 */
	public function testListwebcount() {
	
		
		var_dump(WebModel::listwebcount(array(1,2,3),''));
	}
	
	/**
	 * Tests WebModel::listweb()
	 */
	public function testListweb() {
	
		
		var_dump(WebModel::listweb(array(1,2,3),'',0,3));
	}
}

