<?php

namespace sns\models;

/**
 * SnsCommentModel test case.
 */
class SnsCommentModelTest extends \Sky\test\TestCase {
	
	/**
	 *
	 * @var SnsCommentModel
	 */
	private $SnsCommentModel;
	
	/**
	 * Prepares the environment before running a test.
	 */
	protected function setUp() {
		parent::setUp ();
		
		// TODO Auto-generated SnsCommentModelTest::setUp()
		
		//$this->SnsCommentModel = new SnsCommentModel(/* parameters */);
	}
	
	/**
	 * Cleans up the environment after running a test.
	 */
	protected function tearDown() {
		// TODO Auto-generated SnsCommentModelTest::tearDown()
		$this->SnsCommentModel = null;
		
		parent::tearDown ();
	}
	
	/**
	 * Constructs the test case.
	 */
	public function __construct() {
		// TODO Auto-generated constructor
	}
	
	/**
	 * Tests SnsCommentModel::model()
	 */
	public function testModel() {
		// TODO Auto-generated SnsCommentModelTest::testModel()
		$this->markTestIncomplete ( "model test not implemented" );
		
		SnsCommentModel::model(/* parameters */);
	}
	
	/**
	 * Tests SnsCommentModel::insertComment()
	 */
	public function testInsertComment() {
		// TODO Auto-generated SnsCommentModelTest::testInsertComment()
		//$this->markTestIncomplete ( "insertComment test not implemented" );
		
		SnsCommentModel::insertComment(10000);
	}
	
	/**
	 * Tests SnsCommentModel::insertCommentDetail()
	 */
	public function testInsertCommentDetail() {
		// TODO Auto-generated SnsCommentModelTest::testInsertCommentDetail()
		//$this->markTestIncomplete ( "insertCommentDetail test not implemented" );
		
		SnsCommentModel::insertCommentDetail(9999,9999,'9999','9999');
	}
	
	/**
	 * Tests SnsCommentModel::queryComment()
	 */
	public function testQueryComment() {
		// TODO Auto-generated SnsCommentModelTest::testQueryComment()
		//$this->markTestIncomplete ( "queryComment test not implemented" );
		
		SnsCommentModel::queryComment(9999,'');
	}
	
	/**
	 * Tests SnsCommentModel::updateComment()
	 */
	public function testUpdateComment() {
		// TODO Auto-generated SnsCommentModelTest::testUpdateComment()
		//$this->markTestIncomplete ( "updateComment test not implemented" );
		
		SnsCommentModel::updateComment(9999);
	}
	
	/**
	 * Tests SnsCommentModel::queryCommentDetailByCid()
	 */
	public function testQueryCommentDetailByCid() {
		// TODO Auto-generated
		// SnsCommentModelTest::testQueryCommentDetailByCid()
		//$this->markTestIncomplete ( "queryCommentDetailByCid test not implemented" );
		
		SnsCommentModel::queryCommentDetailByCid(9999,'');
	}
	
	/**
	 * Tests SnsCommentModel::queryCommentDetailByUid()
	 */
	public function testQueryCommentDetailByUid() {
		// TODO Auto-generated
		// SnsCommentModelTest::testQueryCommentDetailByUid()
		//$this->markTestIncomplete ( "queryCommentDetailByUid test not implemented" );
		
		SnsCommentModel::queryCommentDetailByUid(9999,'');
	}
	
	/**
	 * Tests SnsCommentModel::updateCommentDetail()
	 */
	public function testUpdateCommentDetail() {
		// TODO Auto-generated SnsCommentModelTest::testUpdateCommentDetail()
		//$this->markTestIncomplete ( "updateCommentDetail test not implemented" );
		
		SnsCommentModel::updateCommentDetail(9999,1);
	}
	
	/**
	 * Tests SnsCommentModel::getCommentTotalByUid()
	 */
	public function testGetCommentTotalByUid() {
		// TODO Auto-generated SnsCommentModelTest::testGetCommentTotalByUid()
		//$this->markTestIncomplete ( "getCommentTotalByUid test not implemented" );
		
		SnsCommentModel::getCommentTotalByUid(9999);
	}
}

