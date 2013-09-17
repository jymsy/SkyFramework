<?php

namespace sns\models;

/**
 * SnsSelfPublishModel test case.
 */
class SnsSelfPublishModelTest extends \Sky\test\TestCase {
	
	/**
	 *
	 * @var SnsSelfPublishModel
	 */
	private $SnsSelfPublishModel;
	
	/**
	 * Prepares the environment before running a test.
	 */
	protected function setUp() {
		parent::setUp ();
		
		// TODO Auto-generated SnsSelfPublishModelTest::setUp()
		
		//$this->SnsSelfPublishModel = new SnsSelfPublishModel(/* parameters */);
	}
	
	/**
	 * Cleans up the environment after running a test.
	 */
	protected function tearDown() {
		// TODO Auto-generated SnsSelfPublishModelTest::tearDown()
		$this->SnsSelfPublishModel = null;
		
		parent::tearDown ();
	}
	
	/**
	 * Constructs the test case.
	 */
	public function __construct() {
		// TODO Auto-generated constructor
	}
	
	/**
	 * Tests SnsSelfPublishModel::model()
	 */
	public function testModel() {
		// TODO Auto-generated SnsSelfPublishModelTest::testModel()
		$this->markTestIncomplete ( "model test not implemented" );
		
		SnsSelfPublishModel::model(/* parameters */);
	}
	
	/**
	 * Tests SnsSelfPublishModel::insertByVideo()
	 */
	public function testInsertByVideo() {
		// TODO Auto-generated SnsSelfPublishModelTest::testInsertByVideo()
		$this->markTestIncomplete ( "insertByVideo test not implemented" );
		
		SnsSelfPublishModel::insertByVideo(9999,9999,'00001','1');
	}
	
	/**
	 * Tests SnsSelfPublishModel::insertByPlaying()
	 */
	public function testInsertByPlaying() {
		// TODO Auto-generated SnsSelfPublishModelTest::testInsertByPlaying()
		//$this->markTestIncomplete ( "insertByPlaying test not implemented" );
		
		SnsSelfPublishModel::insertByPlaying(9998,9998,'00002','1');
	}
	
	/**
	 * Tests SnsSelfPublishModel::insertPublishByMusic()
	 */
	public function testInsertPublishByMusic() {
		// TODO Auto-generated
		// SnsSelfPublishModelTest::testInsertPublishByMusic()
		//$this->markTestIncomplete ( "insertPublishByMusic test not implemented" );
		
		SnsSelfPublishModel::insertPublishByMusic(9997,9997,'00003','1');
	}
	
	/**
	 * Tests SnsSelfPublishModel::insertPublishByMusicTop()
	 */
	public function testInsertPublishByMusicTop() {
		// TODO Auto-generated
		// SnsSelfPublishModelTest::testInsertPublishByMusicTop()
		//$this->markTestIncomplete ( "insertPublishByMusicTop test not implemented" );
		
		SnsSelfPublishModel::insertPublishByMusicTop(9996,9996,'00004','1');
	}
	
	/**
	 * Tests SnsSelfPublishModel::insertPublishByNews()
	 */
	public function testInsertPublishByNews() {
		// TODO Auto-generated
		// SnsSelfPublishModelTest::testInsertPublishByNews()
		//$this->markTestIncomplete ( "insertPublishByNews test not implemented" );
		
		SnsSelfPublishModel::insertPublishByNews(9995,9995,'00005','1');
	}
	
	/**
	 * Tests SnsSelfPublishModel::insertPublishByUser()
	 */
	public function testInsertPublishByUser() {
		// TODO Auto-generated
		// SnsSelfPublishModelTest::testInsertPublishByUser()
		//$this->markTestIncomplete ( "insertPublishByUser test not implemented" );
		
		SnsSelfPublishModel::insertPublishByUser(9999,'00006',9999,'9999','9999','9999','9999','9999',0,'1');
	}
	
	/**
	 * Tests SnsSelfPublishModel::showPublishByUid()
	 */
	public function testShowPublishByUid() {
		// TODO Auto-generated SnsSelfPublishModelTest::testShowPublishByUid()
		//$this->markTestIncomplete ( "showPublishByUid test not implemented" );
		
		SnsSelfPublishModel::showPublishByUid(9999,'1');
	}
	
	/**
	 * Tests SnsSelfPublishModel::showPublishBySid()
	 */
	public function testShowPublishBySid() {
		// TODO Auto-generated SnsSelfPublishModelTest::testShowPublishBySid()
		//$this->markTestIncomplete ( "showPublishBySid test not implemented" );
		
		SnsSelfPublishModel::showPublishBySid(9999,'00001','1');
	}
	
	/**
	 * Tests SnsSelfPublishModel::getPublishCountBySid()
	 */
	public function testGetPublishCountBySid() {
		// TODO Auto-generated
		// SnsSelfPublishModelTest::testGetPublishCountBySid()
		//$this->markTestIncomplete ( "getPublishCountBySid test not implemented" );
		
		SnsSelfPublishModel::getPublishCountBySid(9999,'00001');
	}
	
	/**
	 * Tests SnsSelfPublishModel::updatePublish()
	 */
	public function testUpdatePublish() {
		// TODO Auto-generated SnsSelfPublishModelTest::testUpdatePublish()
		//$this->markTestIncomplete ( "updatePublish test not implemented" );
		
		SnsSelfPublishModel::updatePublish(9999,2);
	}
	
	/**
	 * Tests SnsSelfPublishModel::listPublishByHotShare()
	 */
	public function testListPublishByHotShare() {
		// TODO Auto-generated
		// SnsSelfPublishModelTest::testListPublishByHotShare()
		$this->markTestIncomplete ( "listPublishByHotShare test not implemented" );
		
		SnsSelfPublishModel::listPublishByHotShare(0,10,1,0);
	}
	
	/**
	 * Tests SnsSelfPublishModel::listPublishByType()
	 */
	public function testListPublishByType() {
		// TODO Auto-generated SnsSelfPublishModelTest::testListPublishByType()
		//$this->markTestIncomplete ( "listPublishByType test not implemented" );
		
		SnsSelfPublishModel::listPublishByType(0,10,'00001',1);
	}
	
	/**
	 * Tests SnsSelfPublishModel::listPublishByShareUid()
	 */
	public function testListPublishByShareUid() {
		// TODO Auto-generated
		// SnsSelfPublishModelTest::testListPublishByShareUid()
		//$this->markTestIncomplete ( "listPublishByShareUid test not implemented" );
		
		SnsSelfPublishModel::listPublishByShareUid(9999,0,10,1);
	}
	
	/**
	 * Tests SnsSelfPublishModel::listPublishByCommentUid()
	 */
	public function testListPublishByCommentUid() {
		// TODO Auto-generated
		// SnsSelfPublishModelTest::testListPublishByCommentUid()
		//$this->markTestIncomplete ( "listPublishByCommentUid test not implemented" );
		
		SnsSelfPublishModel::listPublishByCommentUid(9999,0,10,1);
	}
	
	/**
	 * Tests SnsSelfPublishModel::listPublishByPraiseUid()
	 */
	public function testListPublishByPraiseUid() {
		// TODO Auto-generated
		// SnsSelfPublishModelTest::testListPublishByPraiseUid()
		//$this->markTestIncomplete ( "listPublishByPraiseUid test not implemented" );
		
		SnsSelfPublishModel::listPublishByPraiseUid(9999,0,10,1);
	}
	
	/**
	 * Tests SnsSelfPublishModel::listPublishByCollectUid()
	 */
	public function testListPublishByCollectUid() {
		// TODO Auto-generated
		// SnsSelfPublishModelTest::testListPublishByCollectUid()
		//$this->markTestIncomplete ( "listPublishByCollectUid test not implemented" );
		
		SnsSelfPublishModel::listPublishByCollectUid(9999,0,10,1);
	}
	
	/**
	 * Tests SnsSelfPublishModel::listPublishByDate()
	 */
	public function testListPublishByDate() {
		// TODO Auto-generated SnsSelfPublishModelTest::testListPublishByDate()
		//$this->markTestIncomplete ( "listPublishByDate test not implemented" );
		
		SnsSelfPublishModel::listPublishByDate(0,10,1,0);
	}
	
	/**
	 * Tests SnsSelfPublishModel::getListVideoIdByName()
	 */
	public function testGetListVideoIdByName() {
		// TODO Auto-generated
		// SnsSelfPublishModelTest::testGetListVideoIdByName()
		//$this->markTestIncomplete ( "getListVideoIdByName test not implemented" );
		
		SnsSelfPublishModel::getListVideoIdByName('十二生肖');
	}
	
	/**
	 * Tests SnsSelfPublishModel::InsertPublishByVideoId()
	 */
	public function testInsertPublishByVideoId() {
		// TODO Auto-generated
		// SnsSelfPublishModelTest::testInsertPublishByVideoId()
		//$this->markTestIncomplete ( "InsertPublishByVideoId test not implemented" );
		
		SnsSelfPublishModel::InsertPublishByVideoId('00001',1,9990);
	}
	
	/**
	 * Tests SnsSelfPublishModel::getPublishListByVid()
	 */
	public function testGetPublishListByVid() {
		// TODO Auto-generated
		// SnsSelfPublishModelTest::testGetPublishListByVid()
		//$this->markTestIncomplete ( "getPublishListByVid test not implemented" );
		
		SnsSelfPublishModel::getPublishListByVid(0,10,'00001',1,9999);
	}
	
	/**
	 * Tests SnsSelfPublishModel::getPublishCountByUrl()
	 */
	public function testGetPublishCountByUrl() {
		// TODO Auto-generated
		// SnsSelfPublishModelTest::testGetPublishCountByUrl()
		//$this->markTestIncomplete ( "getPublishCountByUrl test not implemented" );
		
		SnsSelfPublishModel::getPublishCountByUrl('9999','00006');
	}
}

