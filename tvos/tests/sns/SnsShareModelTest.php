<?php

namespace sns\models;
/**
 * SnsShareModel test case.
 */
class SnsShareModelTest extends \Sky\test\TestCase {
	
	/**
	 *
	 * @var SnsShareModel
	 */
	private $SnsShareModel;
	
	/**
	 * Prepares the environment before running a test.
	 */
	protected function setUp() {
		parent::setUp ();
		
		// TODO Auto-generated SnsShareModelTest::setUp()
		
		//$this->SnsShareModel = new SnsShareModel(/* parameters */);
	}
	
	/**
	 * Cleans up the environment after running a test.
	 */
	protected function tearDown() {
		// TODO Auto-generated SnsShareModelTest::tearDown()
		$this->SnsShareModel = null;
		
		parent::tearDown ();
	}
	
	/**
	 * Constructs the test case.
	 */
	public function __construct() {
		// TODO Auto-generated constructor
	}
	
	/**
	 * Tests SnsShareModel::model()
	 */
	public function testModel() {
		// TODO Auto-generated SnsShareModelTest::testModel()
		$this->markTestIncomplete ( "model test not implemented" );
		
		SnsShareModel::model(/* parameters */);
	}
	
	/**
	 * Tests SnsShareModel::insertShare()
	 */
	public function testInsertShare() {
		// TODO Auto-generated SnsShareModelTest::testInsertShare()
		//$this->markTestIncomplete ( "insertShare test not implemented" );
		
		SnsShareModel::insertShare(9997);
	}
	
	/**
	 * Tests SnsShareModel::insertShareDetail()
	 */
	public function testInsertShareDetail() {
		// TODO Auto-generated SnsShareModelTest::testInsertShareDetail()
		//$this->markTestIncomplete ( "insertShareDetail test not implemented" );
		
		SnsShareModel::insertShareDetail(9999,9999,9999);
	}
	
	/**
	 * Tests SnsShareModel::queryShare()
	 */
	public function testQueryShare() {
		// TODO Auto-generated SnsShareModelTest::testQueryShare()
		//$this->markTestIncomplete ( "queryShare test not implemented" );
		
		SnsShareModel::queryShare(9999,'');
	}
	
	/**
	 * Tests SnsShareModel::updateShare()
	 */
	public function testUpdateShare() {
		// TODO Auto-generated SnsShareModelTest::testUpdateShare()
		//$this->markTestIncomplete ( "updateShare test not implemented" );
		
		SnsShareModel::updateShare(9999);
	}
	
	/**
	 * Tests SnsShareModel::queryShareDetailByCid()
	 */
	public function testQueryShareDetailByCid() {
		// TODO Auto-generated SnsShareModelTest::testQueryShareDetailByCid()
		//$this->markTestIncomplete ( "queryShareDetailByCid test not implemented" );
		
		SnsShareModel::queryShareDetailByCid(9999,'');
	}
	
	/**
	 * Tests SnsShareModel::queryShareDetailByUid()
	 */
	public function testQueryShareDetailByUid() {
		// TODO Auto-generated SnsShareModelTest::testQueryShareDetailByUid()
		//$this->markTestIncomplete ( "queryShareDetailByUid test not implemented" );
		
		SnsShareModel::queryShareDetailByUid(9999,'');
	}
	
	/**
	 * Tests SnsShareModel::updateShareDetail()
	 */
	public function testUpdateShareDetail() {
		// TODO Auto-generated SnsShareModelTest::testUpdateShareDetail()
		//$this->markTestIncomplete ( "updateShareDetail test not implemented" );
		
		SnsShareModel::updateShareDetail(9999,1);
	}
	
	/**
	 * Tests SnsShareModel::getShareTotalByUid()
	 */
	public function testGetShareTotalByUid() {
		// TODO Auto-generated SnsShareModelTest::testGetShareTotalByUid()
		//$this->markTestIncomplete ( "getShareTotalByUid test not implemented" );
		
		SnsShareModel::getShareTotalByUid(9999);
	}
}

