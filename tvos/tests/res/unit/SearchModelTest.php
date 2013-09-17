<?php
namespace tests\res\unit;

use res\models\SearchModel;

/**
 * SearchModel test case.
 * 
 * @author Zhengyun
 */
class SearchModelTest extends \Sky\test\TestCase {	
	
	public function testModel()
	{
		$obj=SearchModel::model();
		$this->assertInstanceOf('res\models\SearchModel',$obj);
	}
	
	/**
	 * Tests SearchModel::getHotwords()
	 */
	public function testGetHotwords() 
	{
		$ret=SearchModel::getHotwords(10);
// 		var_dump($ret);
		$this->assertCount(10,$ret);
	}
	
	/**
	 * Tests SearchModel::updateHotwords()
	 */
	public function testUpdateHotwords() 
	{
		$ret=SearchModel::updateHotwords("test");
		$this->assertGreaterThan(0, $ret);
	}
	
	/**
	 * Tests SearchModel::getVideoCount()
	 */
	public function testGetVideoCount() 
	{		
		$ret=SearchModel::getVideoCount("2013","");
// 		var_dump($ret);
		$this->assertGreaterThan(0, $ret);
	}
	
	/**
	 * Tests SearchModel::getVideoDetail()
	 */
	public function testGetVideoDetail() 
	{
		$ret=SearchModel::getVideoDetail("2013",0,10,"");
// 		var_dump($ret);
		$this->assertGreaterThan(1, count($ret));
	}
	
	/**
	 * Tests SearchModel::getInfoCount()
	 */
	public function testGetInfoCount() 
	{	
		$ret=SearchModel::getInfoCount("奥巴马","");
// 		var_dump($ret);
		$this->assertGreaterThan(0, $ret);
	}
	
	/**
	 * Tests SearchModel::getInfoDetail()
	 */
	public function testGetInfoDetail() 
	{		
		$ret=SearchModel::getInfoDetail("奥巴马",0,10,"aaaa","");
// 		var_dump($ret);
		$this->assertGreaterThan(0, count($ret));
	}
	
	/**
	 * Tests SearchModel::getAudioCount()
	 */
	public function testGetAudioCount() 
	{		
		$ret=SearchModel::getAudioCount("2013","");
// 		var_dump($ret);
		$this->assertGreaterThan(0, $ret);
	}
	
	/**
	 * Tests SearchModel::getAudioDetail()
	 */
	public function testGetAudioDetail() 
	{
		$ret=SearchModel::getAudioDetail("2013",0,10,"");
// 		var_dump($ret);
		$this->assertGreaterThan(0, count($ret));
	}
}

