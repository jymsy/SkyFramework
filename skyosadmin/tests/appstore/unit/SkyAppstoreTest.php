<?php
namespace tests\appstore\unit;

use appstore\models\SkyAppstoreModel;


class SkyAppstoreTest extends \Sky\test\TestCase{
	public $appController;	
	
	/////test type////
	public function testInsertType(){
		$id=SkyAppstoreModel::insertType(array('product_type_name'=>'test1'));
		$cnt=SkyAppstoreModel::searchTypeCount(array('product_type_name'=>'test1','product_type_id'=>(int)$id));
		$this->assertEquals($cnt,"1");
		return $id;
	}
	
	/**
	 * @depends testInsertType
	 */
	public function testUpdateType($id){
		SkyAppstoreModel::UpdateType(array('product_type_name'=>'test1','product_type_id'=>$id));
		$cnt=SkyAppstoreModel::searchTypeCount(array('product_type_name'=>'test1','product_type_id'=>(int)$id));
		$this->assertEquals($cnt,"1");
	}
	
	public function testUpdateType2(){
		SkyAppstoreModel::UpdateType(array('product_type_name'=>'test2','product_type_id'=>317));
		//$cnt=SkyAppstoreModel::searchTypeCount(array('product_type_name'=>'test1','product_type_id'=>(int)$id));
		//$this->assertEquals($cnt,"1");
	}
	
	public function testgetAllTypeCount(){
		$cnt=SkyAppstoreModel::getAllTypeCount();
		$this->assertGreaterThanOrEqual("1",$cnt);
	}
	
	public function testgetAllType(){
		$arr=SkyAppstoreModel::getAllType(0,10,array("product_type_id"=>"desc"));
		var_dump($arr);
		//$this->assertTrue(is_array($arr));
	}
	
	/**
	 * @depends testInsertType
	 */
	public function testsearchTypeDetail($id){
		$arr=SkyAppstoreModel::searchTypeDetail(array('product_type_name'=>'test1','product_type_id'=>(int)$id),0,10);
		var_dump($arr);
		$this->assertTrue(is_array($arr));
	}
	
	
	/**
	 * @depends testInsertType
	 */
	public function testdelType($id){
		SkyAppstoreModel::deleteType($id);
		$cnt=SkyAppstoreModel::searchTypeCount(array('product_type_name'=>'test1','product_type_id'=>(int)$id));
		//var_dump($cnt);
	
		$this->assertEquals($cnt,"0");
	}
	
	public function testdelType2(){
		SkyAppstoreModel::deleteType(182);
		$cnt=SkyAppstoreModel::searchTypeCount(array('product_type_name'=>'test1','product_type_id'=>(int)317));
		//var_dump($cnt);
	
		$this->assertEquals($cnt,"0");
	}
	/////test flatform/////
	public function testinsertPlatform(){
		$id=SkyAppstoreModel::insertPlatform("test_flatform2");
		$cnt=SkyAppstoreModel::searchPlatformCount(array('platform_info'=>'test_flatform2'));
		$this->assertEquals($cnt,"1");
		return $id;
		
	}
	
	/**
	 * @depends testinsertPlatform
	 */
	public function testUpdatePlatform($id){
		$id=SkyAppstoreModel::updatePlatform(array('platform_info'=>"test_2",'platform_id'=>$id));
		$cnt=SkyAppstoreModel::searchPlatformCount(array('platform_info'=>'test_2'));
		$this->assertEquals($cnt,"1");
	
	}
	
	public function testUpdatePlatform2(){
		$id=SkyAppstoreModel::updatePlatform(array('platform_info'=>"AM7366",'platform_id'=>1));
		//$cnt=SkyAppstoreModel::searchPlatformCount(array('platform_info'=>'test_2'));
		//$this->assertEquals($cnt,"1");
	
	}
	
	
	public function testgetAllPlatform(){
		$arr=SkyAppstoreModel::getAllPlatform();
		//var_dump($arr);
		$this->assertTrue(is_array($arr));
	
	}
	
	public function testgetAllPlatformCount(){
		$cnt=SkyAppstoreModel::getAllPlatformCount();
		$this->assertGreaterThanOrEqual("1",$cnt);
	
	}
	
	/**
	 * @depends testinsertPlatform
	 */
	public function testgetAllPlatformDetail($id){
		$arr=SkyAppstoreModel::searchPlatformDetail(array('platform_info'=>'test_2'),0,10);
		$this->assertTrue(is_array($arr));	
	}
	
	
	
	
	/**
	 * @depends testinsertPlatform
	 */
	public function testDelPlatform($id){
		SkyAppstoreModel::deletePlatform($id);
		$cnt=SkyAppstoreModel::searchPlatformCount(array('platform_info'=>'test_2'));
		$this->assertEquals($cnt,"0");
	
	}
	
	public function testDelPlatform2(){
		SkyAppstoreModel::deletePlatform(1);
		$cnt=SkyAppstoreModel::searchPlatformCount(array('platform_info'=>'test_2'));
		$this->assertEquals($cnt,"0");
	
	}
	
	
}