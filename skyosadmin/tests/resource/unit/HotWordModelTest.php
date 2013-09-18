<?php
namespace tests\resource\unit;
use resource\models\HotWordModel;

/**
 * HotWordModel test case.
 */
class HotWordModelTest extends \Sky\test\TestCase {
	
	/**
	 *
	 * @var HotWordModel
	 */
	private $HotWordModel;
	
	/**
	 * Prepares the environment before running a test.
	 */
	protected function setUp() {
		parent::setUp ();
		
		// TODO Auto-generated HotWordModelTest::setUp()
		
		//$this->HotWordModel = new HotWordModel(/* parameters */);
	}
	
	/**
	 * Cleans up the environment after running a test.
	 */
	protected function tearDown() {
		// TODO Auto-generated HotWordModelTest::tearDown()
		$this->HotWordModel = null;
		
		parent::tearDown ();
	}
	
	
	
	/**
	 * Tests HotWordModel::getNewResourceLists()
	 */
	/*public function testGetNewResourceLists() {
		// TODO Auto-generated HotWordModelTest::testGetNewResourceLists()
		//$this->markTestIncomplete ( "getNewResourceLists test not implemented" );
		
		var_dump(HotWordModel::getNewResourceLists(0,10));
	}*/
	
	/**
	 * Tests HotWordModel::getNewResourceCount()
	 */
	/*public function testGetNewResourceCount() {
		// TODO Auto-generated HotWordModelTest::testGetNewResourceCount()
		//$this->markTestIncomplete ( "getNewResourceCount test not implemented" );
		
		var_dump(HotWordModel::getNewResourceCount());
	}*/
	
	/**
	 * Tests HotWordModel::setExpiredVideo()
	 */
	public function testSetExpiredVideo() {
		// TODO Auto-generated HotWordModelTest::testSetExpiredVideo()
		//$this->markTestIncomplete ( "setExpiredVideo test not implemented" );
		
		HotWordModel::setExpiredVideo(100);
	}
	
	/**
	 * Tests HotWordModel::setOnlineVideo()
	 */
	public function testSetOnlineVideo() {
		// TODO Auto-generated HotWordModelTest::testSetOnlineVideo()
		//$this->markTestIncomplete ( "setOnlineVideo test not implemented" );
		
		HotWordModel::setOnlineVideo(1001);
	}
	
	/**
	 * Tests HotWordModel::get_hot_word_lists()
	 */
	public function testGet_hot_word_lists() {
		// TODO Auto-generated HotWordModelTest::testGet_hot_word_lists()
		//$this->markTestIncomplete ( "get_hot_word_lists test not implemented" );
		
		var_dump(HotWordModel::get_hot_word_lists(1,10));
	}
	
	/**
	 * Tests HotWordModel::getHotWordCount()
	 */
	public function testGetHotWordCount() {
		// TODO Auto-generated HotWordModelTest::testGetHotWordCount()
		//$this->markTestIncomplete ( "getHotWordCount test not implemented" );
		
		var_dump(HotWordModel::getHotWordCount());
	}
	
	/**
	 * Tests HotWordModel::delHotword()
	 */
	public function testDelHotword() {
		// TODO Auto-generated HotWordModelTest::testDelHotword()
		//$this->markTestIncomplete ( "delHotword test not implemented" );
		
		HotWordModel::delHotword("phoebe");
	}
	
	/**
	 * Tests HotWordModel::updateHotword()
	 */
	public function testUpdateHotword() {
		// TODO Auto-generated HotWordModelTest::testUpdateHotword()
		//$this->markTestIncomplete ( "updateHotword test not implemented" );
		
		HotWordModel::updateHotword("phoebe",3);
	}
	
	/**
	 * Tests HotWordModel::searchHotwordLists()
	 */
	public function testSearchHotwordLists() {
		// TODO Auto-generated HotWordModelTest::testSearchHotwordLists()
		//$this->markTestIncomplete ( "searchHotwordLists test not implemented" );
		
		var_dump(HotWordModel::searchHotwordLists("phoebe",1,10));
	}
	
	/**
	 * Tests HotWordModel::searchHotwordCount()
	 */
	public function testSearchHotwordCount() {
		// TODO Auto-generated HotWordModelTest::testSearchHotwordCount()
		//$this->markTestIncomplete ( "searchHotwordCount test not implemented" );
		
		var_dump(HotWordModel::searchHotwordCount("phoebe"));
	}
	
	/**
	 * Tests HotWordModel::addHotword()
	 */
	public function testAddHotword() {
		// TODO Auto-generated HotWordModelTest::testAddHotword()
		//$this->markTestIncomplete ( "addHotword test not implemented" );
		
		HotWordModel::addHotword("phoebe",2);
	}
}

