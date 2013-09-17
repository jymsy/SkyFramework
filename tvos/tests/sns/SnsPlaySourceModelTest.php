<?php

namespace sns\models;

/**
 * SnsPlaySourceModel test case.
 */
class SnsPlaySourceModelTest extends \Sky\test\TestCase {
	
	/**
	 *
	 * @var SnsPlaySourceModel
	 */
	private $SnsPlaySourceModel;
	
	/**
	 * Prepares the environment before running a test.
	 */
	protected function setUp() {
		parent::setUp ();
		
		// TODO Auto-generated SnsPlaySourceModelTest::setUp()
		
		//$this->SnsPlaySourceModel = new SnsPlaySourceModel(/* parameters */);
	}
	
	/**
	 * Cleans up the environment after running a test.
	 */
	protected function tearDown() {
		// TODO Auto-generated SnsPlaySourceModelTest::tearDown()
		$this->SnsPlaySourceModel = null;
		
		parent::tearDown ();
	}
	
	/**
	 * Constructs the test case.
	 */
	public function __construct() {
		// TODO Auto-generated constructor
	}
	
	/**
	 * Tests SnsPlaySourceModel::model()
	 */
	public function testModel() {
		// TODO Auto-generated SnsPlaySourceModelTest::testModel()
		$this->markTestIncomplete ( "model test not implemented" );
		
		SnsPlaySourceModel::model(/* parameters */);
	}
	
	/**
	 * Tests SnsPlaySourceModel::InsertPlaySource()
	 */
	public function testInsertPlaySource() {
		// TODO Auto-generated SnsPlaySourceModelTest::testInsertPlaySource()
		//$this->markTestIncomplete ( "InsertPlaySource test not implemented" );
		
		//var_dump(SnsPlaySourceModel::InsertPlaySource(5,'weqew',1));
	}
	
	/**
	 * Tests SnsPlaySourceModel::InsertPlaySourceByArray()
	 */
	public function testInsertPlaySourceByArray() {
		// TODO Auto-generated
		// SnsPlaySourceModelTest::testGetPlaySourceByPublishId()
		//$this->markTestIncomplete ( "GetPlaySourceByPublishId test not implemented" );
		
		var_dump(SnsPlaySourceModel::InsertPlaySourceByArray("(1,'2',0)"));
	}
}

