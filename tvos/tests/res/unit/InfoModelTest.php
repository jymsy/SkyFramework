<?php
namespace tests\res\unit;

use res\models\InfoModel;

/**
 * InfoModel test case.
 */
class InfoModelTest extends \Sky\test\TestCase {	
	
	public function testModel()
	{
		$obj=InfoModel::model();
		$this->assertInstanceOf('res\models\InfoModel',$obj);
	}
	
	/**
	 * Tests InfoModel::showNewsInfo()
	 */
	public function testShowNewsInfo() 
	{
		$info=InfoModel::showNewsInfo("aaa", "95822","category_id=10051");
// 		var_dump($info);
		$this->assertEquals(1,count($info));
		$this->assertEquals('aaa95822',$info[0]['url']);
		
	}
	
	/**
	 * Tests InfoModel::showNewsDetailInfo()
	 */
	public function testShowNewsDetailInfo() 
	{
		$detail=InfoModel::showNewsDetailInfo("aaa", "95822,95821","");
// 		var_dump($detail);
		$this->assertEquals(2,count($detail));
	}
	
	/**
	 * Tests InfoModel::getPromise()
	 */
	public function testGetPromise() 
	{
		$arr_promise=InfoModel::getPromise("info_category");
		var_dump($arr_promise);
		$this->assertEquals(2,count($arr_promise));
		return $arr_promise;
	}
	
	/**
	 * @depends testGetPromise
	 * Tests InfoModel::listInfoCount()
	 */
	public function testListInfoCount($arr_promise) 
	{		
		$ret=InfoModel::listInfoCount($arr_promise,array('category'=>'infos'),"category_id=10051");
		var_dump($ret);
		$this->assertGreaterThan(0, $ret);
	}
	
	/**
	 * @depends testGetPromise
	 * Tests InfoModel::listInfoDetail()
	 */
	public function testListInfoDetail($arr_promise) 
	{
		$detail=InfoModel::listInfoDetail('aaa',$arr_promise,
													array('createdate'=>'2013-01-31 21:00:00#now'),
													0,10,"category_id=10051");
// 		var_dump($detail);
		$this->assertGreaterThan(0, count($detail));
	}
	
	/**
	 * Tests InfoModel::listtopinfo()
	 */
	public function testListtopinfo() 
	{
		$info=InfoModel::listtopinfo("aa");
// 		var_dump($info);
		$this->assertGreaterThan(0, count($info));
	}
}

