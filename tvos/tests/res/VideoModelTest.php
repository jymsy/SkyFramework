<?php

namespace res\models;

/**
 * VideoModel test case.
 */
class VideoModelTest extends \Sky\test\TestCase {
	
	/**
	 *
	 * @var VideoModel
	 */
	private $VideoModel;
	
	/**
	 * Prepares the environment before running a test.
	 */
	protected function setUp() {
		parent::setUp ();
		
		// TODO Auto-generated VideoModelTest::setUp()
		
		//$this->VideoModel = new VideoModel(/* parameters */);
	}
	
	/**
	 * Cleans up the environment after running a test.
	 */
	protected function tearDown() {
		// TODO Auto-generated VideoModelTest::tearDown()
		$this->VideoModel = null;
		
		parent::tearDown ();
	}
	
	/**
	 * Constructs the test case.
	 */
	public function __construct() {
		// TODO Auto-generated constructor
	}
	
	/**
	 * Tests VideoModel::model()
	 */
	public function testModel() {
		// TODO Auto-generated VideoModelTest::testModel()
		$this->markTestIncomplete ( "model test not implemented" );
		
		VideoModel::model(/* parameters */);
	}
	
	/**
	 * Tests VideoModel::loadsite()
	 */
	public function testLoadsite() {
		
		
		var_dump(VideoModel::loadsite(1,''));
	}
	
	/**
	 * Tests VideoModel::queryvideobyid()
	 */
	public function testQueryvideobyid() {
		
		
		var_dump(VideoModel::queryvideobyid(1,''));
	}
	
	/**
	 * Tests VideoModel::queryvideobytitle()
	 */
	public function testQueryvideobytitle() {
	
		
		var_dump(VideoModel::queryvideobytitle('十二生肖',''));
	}
	
	/**
	 * Tests VideoModel::queryvideocomment()
	 */
	public function testQueryvideocomment() {

		
		var_dump(VideoModel::queryvideocomment(1,''));
	}
	
	/**
	 * Tests VideoModel::queryplaybill()
	 */
	public function testQueryplaybill() {
		
		var_dump(VideoModel::queryplaybill(1,''));
	}
	
	/**
	 * Tests VideoModel::queryvideosite()
	 */
	public function testQueryvideosite() {

		
		var_dump(VideoModel::queryvideosite(1,''));
	}
	
	/**
	 * Tests VideoModel::queryvideourl()
	 */
	public function testQueryvideourl() {

		
		var_dump(VideoModel::queryvideourl(1,'',0,2));
	}
	
	/**
	 * Tests VideoModel::queryvideolist()
	 */
	public function testQueryvideolist() {
		
		
		var_dump(VideoModel::queryvideolist('',0,2));
	}
	
	/**
	 * Tests VideoModel::querytopcount()
	 */
	public function testQuerytopcount() {
		
		
		var_dump(VideoModel::querytopcount('001',''));
	}
	
	/**
	 * Tests VideoModel::querytopvideolist()
	 */
	public function testQuerytopvideolist() {
		
		
		var_dump(VideoModel::querytopvideolist('001','',0,2));
	}
	
	/**
	 * Tests VideoModel::querysitecount()
	 */
	public function testQuerysitecount() {
	
		
		var_dump(VideoModel::querysitecount('',array("category"=>1),0,2));
	}
	
	/**
	 * Tests VideoModel::queryvideodetail()
	 */
	public function testQueryvideodetail() {
		
		
		var_dump(VideoModel::queryvideodetail('',array("category"=>1),array("level","rindex"),0,3,1));
	}
	
	/**
	 * Tests VideoModel::queryvideobylatest()
	 */
	public function testQueryvideobylatest() {
		
		
		var_dump(VideoModel::queryvideobylatest(1,''));
	}
	
	/**
	 * Tests VideoModel::showvideoforrelation()
	 */
	public function testShowvideoforrelation() {
	
		
		var_dump(VideoModel::showvideoforrelation(1,''));
	}
	
	/**
	 * Tests VideoModel::showvideofortop()
	 */
	public function testShowvideofortop() {
	
		
		var_dump(VideoModel::showvideofortop(1,''));
	}
	
	/**
	 * Tests VideoModel::showvideolistbyid()
	 */
	public function testShowvideolistbyid() {
		
		
		var_dump(VideoModel::showvideolistbyid(1,''));
	}
	
	/**
	 * Tests VideoModel::listtopvideo()
	 */
	public function testListtopvideo() {
	
		
		var_dump(VideoModel::listtopvideo(''));
	}
	
	/**
	 * Tests VideoModel::listtopvideobykey()
	 */
	public function testListtopvideobykey() {
		
		
		var_dump(VideoModel::listtopvideobykey('latest',null,"0","10"));
	}
	
	/**
	 * Tests VideoModel::topkeys()
	 */
	public function testTopkeys() {
	
		
		var_dump(VideoModel::topkeys(1,''));
	}
	
	/**
	 * Tests VideoModel::listcasuals()
	 */
	public function testListcasuals() {
		// TODO Auto-generated VideoModelTest::testListcasuals()
	
		
		var_dump(VideoModel::listcasuals(5,''));
	}
	
	/**
	 * Tests VideoModel::queryvidbytitle()
	 */
	public function testQueryvidbytitle() {
		
		
		var_dump(VideoModel::queryvidbytitle('十二生肖',''));
	}
	
	public function testGetVideotThumByCid_New() {
	
	
		var_dump(VideoModel::GetVideotThumByCid_New(array(10090,10091),"rvs.`source`='qiyi'"));
	}
	
	public function testGetVideotThumByCid_Old() {
	
	
		var_dump(VideoModel::GetVideotThumByCid_Old(array(10090,10091),"`site`='qiyi'"));
	}
}

