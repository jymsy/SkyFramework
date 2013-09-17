<?php

namespace res\models;

/**
 * BroadCastModel test case.
 */
class BroadCastModelTest extends \Sky\test\TestCase {
	
	/**
	 *
	 * @var BroadCastModel
	 */
	private $BroadCastModel;
	
	/**
	 * Prepares the environment before running a test.
	 */
	protected function setUp() {
		parent::setUp ();
		
		// TODO Auto-generated BroadCastModelTest::setUp()
		
		//$this->BroadCastModel = new BroadCastModel(/* parameters */);
	}
	
	/**
	 * Cleans up the environment after running a test.
	 */
	protected function tearDown() {
		// TODO Auto-generated BroadCastModelTest::tearDown()
		$this->BroadCastModel = null;
		
		parent::tearDown ();
	}
	
	/**
	 * Constructs the test case.
	 */
	public function __construct() {
		// TODO Auto-generated constructor
	}
	
	/**
	 * Tests BroadCastModel::model()
	 */
	public function testModel() {
		// TODO Auto-generated BroadCastModelTest::testModel()
		$this->markTestIncomplete ( "model test not implemented" );
		
		BroadCastModel::model(/* parameters */);
	}
	
	/**
	 * Tests BroadCastModel::showbroadcast()
	 */
	public function testShowbroadcast() {
		// TODO Auto-generated BroadCastModelTest::testShowbroadcast()
		
		
		var_dump(BroadCastModel::showbroadcast(array(1,2),''));
	
	}
	
	/**
	 * Tests BroadCastModel::listbroadcastcount()
	 */
	public function testListbroadcastcount() {
		// TODO Auto-generated BroadCastModelTest::testListbroadcastcount()

		
		var_dump(BroadCastModel::listbroadcastcount(902,''));
	}
	
	/**
	 * Tests BroadCastModel::listbroadcast()
	 */
	public function testListbroadcast() {
		// TODO Auto-generated BroadCastModelTest::testListbroadcast()
		
		var_dump(BroadCastModel::listbroadcast(902,'',0,50));
	}
}

