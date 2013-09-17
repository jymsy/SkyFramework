<?php

namespace sns\models;
/**
 * SnsOnlineTotalModel test case.
 */
class SnsOnlineTotalModelTest extends \Sky\test\TestCase {
	
	/**
	 *
	 * @var SnsOnlineTotalModel
	 */
	private $SnsOnlineTotalModel;
	
	/**
	 * Prepares the environment before running a test.
	 */
	protected function setUp() {
		parent::setUp ();
		
		// TODO Auto-generated SnsOnlineTotalModelTest::setUp()
		
		//$this->SnsOnlineTotalModel = new SnsOnlineTotalModel(/* parameters */);
	}
	
	/**
	 * Cleans up the environment after running a test.
	 */
	protected function tearDown() {
		// TODO Auto-generated SnsOnlineTotalModelTest::tearDown()
		$this->SnsOnlineTotalModel = null;
		
		parent::tearDown ();
	}
	
	/**
	 * Constructs the test case.
	 */
	public function __construct() {
		// TODO Auto-generated constructor
	}
	
	/**
	 * Tests SnsOnlineTotalModel::model()
	 */
	public function testModel() {
		// TODO Auto-generated SnsOnlineTotalModelTest::testModel()
		$this->markTestIncomplete ( "model test not implemented" );
		
		SnsOnlineTotalModel::model(/* parameters */);
	}
	
	/**
	 * Tests SnsOnlineTotalModel::insertOnlineTotal()
	 */
	public function testInsertOnlineTotal() {
		// TODO Auto-generated SnsOnlineTotalModelTest::testInsertOnlineTotal()
		//$this->markTestIncomplete ( "insertOnlineTotal test not implemented" );
		
		SnsOnlineTotalModel::insertOnlineTotal('9999','9999','9999','9999','9999');
	}
	
	/**
	 * Tests SnsOnlineTotalModel::getOnlineTotalList()
	 */
	public function testGetOnlineTotalList() {
		// TODO Auto-generated SnsOnlineTotalModelTest::testGetOnlineTotalList()
		//$this->markTestIncomplete ( "getOnlineTotalList test not implemented" );
		
		SnsOnlineTotalModel::getOnlineTotalList('9999','9999');
	}
}

