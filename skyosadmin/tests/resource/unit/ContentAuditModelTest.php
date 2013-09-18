<?php
namespace tests\resource\unit;
use resource\models\ContentAuditModel;
use components\PublicModel;

/**
 * ContentAuditModel test case.
 */
class ContentAuditModelTest extends \Sky\test\TestCase {
	
	/**
	 *
	 * @var ContentAuditModel
	 */
	private $ContentAuditModel;
	
	/**
	 * Prepares the environment before running a test.
	 */
	protected function setUp() {
		parent::setUp ();
		
		// TODO Auto-generated ContentAuditModelTest2::setUp()
		
		//$this->ContentAuditModel = new ContentAuditModel(/* parameters */);
	}
	
	/**
	 * Cleans up the environment after running a test.
	 */
	protected function tearDown() {
		// TODO Auto-generated ContentAuditModelTest2::tearDown()
		$this->ContentAuditModel = null;
		
		parent::tearDown ();
	}
	
	
	
	/**
	 * Tests ContentAuditModel::getInvalidSourceCount()
	 */
	public function testGetInvalidSourceCount() {
		// TODO Auto-generated
		// ContentAuditModelTest2::testGetInvalidSourceCount()
		//$this->markTestIncomplete ( "getInvalidSourceCount test not implemented" );
		
		var_dump(ContentAuditModel::getInvalidSourceCount());
	}
	
	/**
	 * Tests ContentAuditModel::getInvalidSource()
	 */
	public function testGetInvalidSource() {
		// TODO Auto-generated ContentAuditModelTest2::testGetInvalidSource()
		//$this->markTestIncomplete ( "getInvalidSource test not implemented" );
		
		var_dump(ContentAuditModel::getInvalidSource(1,10));
	}
	
	/**
	 * Tests ContentAuditModel::getVideoById()
	 */
	public function testGetVideoById() {
		// TODO Auto-generated ContentAuditModelTest2::testGetVideoById()
		//$this->markTestIncomplete ( "getVideoById test not implemented" );
		
		var_dump(ContentAuditModel::getVideoById(8141));
	}
	
	/**
	 * Tests ContentAuditModel::getAppend()
	 */
	public function testGetAppend() {
		// TODO Auto-generated ContentAuditModelTest2::testExpiredVideo()
		//$this->markTestIncomplete ( "expiredVideo test not implemented" );
		
		var_dump(ContentAuditModel::getAppend(1));
	}
	/**
	 * Tests ContentAuditModel::getAppend()
	 */
	public function testExpiredVideo(){
		ContentAuditModel::expiredVideo(8076);
	}
	
	/**
	 * Tests ContentAuditModel::deleteInvalidSource()
	 */
	public function testDeleteInvalidSource() {
		// TODO Auto-generated ContentAuditModelTest2::testDeleteInvalidSource()
		//$this->markTestIncomplete ( "deleteInvalidSource test not implemented" );
		
		var_dump(ContentAuditModel::deleteInvalidSource(5173));
	}
}

