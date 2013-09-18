<?php

namespace tests\resource;

use resource\models\MergeVideoModel;
/**
 * @author xiaokeming.
 */
class MergeVideoModelTest extends \Sky\test\TestCase {
	
	
	
	/**
	 * Tests MergeVideoModel::model()
	 */
	public function testModel() {
		
		$obj=MergeVideoModel::model();
		$this->assertInstanceOf('resource\models\MergeVideoModel',$obj);
	}
	
	/**
	 * Tests MergeVideoModel::getVideoList()
	 */
	public function testGetVideoList() {
		// TODO Auto-generated MergeVideoModelTest::testGetVideoList()
		//$this->markTestIncomplete ( "getVideoList test not implemented" );
		
		MergeVideoModel::getVideoList(0,10);
	}
	
	/**
	 * Tests MergeVideoModel::getVideoListCount()
	 */
	public function testGetVideoListCount() {
		// TODO Auto-generated MergeVideoModelTest::testGetVideoListCount()
		//$this->markTestIncomplete ( "getVideoListCount test not implemented" );
		
		MergeVideoModel::getVideoListCount();
	}
	
	/**
	 * Tests MergeVideoModel::getVideoByPid()
	 */
	public function testGetVideoByPid() {
		// TODO Auto-generated MergeVideoModelTest::testGetVideoByPid()
		//$this->markTestIncomplete ( "getVideoByPid test not implemented" );
		
		MergeVideoModel::getVideoByPid(array('status'=>'0'),0,10);
	}
	
	/**
	 * Tests MergeVideoModel::getVideoByPidCount()
	 */
	public function testGetVideoByPidCount() {
		// TODO Auto-generated MergeVideoModelTest::testGetVideoByPidCount()
		//$this->markTestIncomplete ( "getVideoByPidCount test not implemented" );
		
		MergeVideoModel::getVideoByPidCount(0);
	}
	
	/**
	 * Tests MergeVideoModel::getVideoByVid()
	 */
	public function testGetVideoByVid() {
		// TODO Auto-generated MergeVideoModelTest::testGetVideoByVid()
		//$this->markTestIncomplete ( "getVideoByVid test not implemented" );
		
		MergeVideoModel::getVideoByVid(2);
	}
	
	/**
	 * Tests MergeVideoModel::getVideoTitleRelation()
	 */
	public function testGetVideoTitleRelation() {
		// TODO Auto-generated MergeVideoModelTest::testGetVideoTitleRelation()
		//$this->markTestIncomplete ( "getVideoTitleRelation test not implemented" );
		
		MergeVideoModel::getVideoTitleRelation("rv.`parent_id` is null ",0,10);
	}
	
	/**
	 * Tests MergeVideoModel::getVideoTitleCount()
	 */
	public function testGetVideoTitleCount() {
		// TODO Auto-generated MergeVideoModelTest::testGetVideoTitleCount()
		//$this->markTestIncomplete ( "getVideoTitleCount test not implemented" );
		
		MergeVideoModel::getVideoTitleCount("rv.`parent_id` is null ");
	}
	
	/**
	 * Tests MergeVideoModel::updateVideoAndSite()
	 */
	public function testUpdateVideoAndSite() {
		// TODO Auto-generated MergeVideoModelTest::testUpdateVideoAndSite()
		//$this->markTestIncomplete ( "updateVideoAndSite test not implemented" );
		
		MergeVideoModel::updateVideoAndSite(array(1),2);
	}
}

