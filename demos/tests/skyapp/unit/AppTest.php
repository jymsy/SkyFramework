<?php
namespace demos\tests\skyapp\unit;

class AppTest extends \Sky\test\TestCase{
	public $appController;
	
	protected function setUp(){
		parent::setUp();
		$this->appController=new \skyapp\controllers\AppController("app");
	}
	
	protected function tearDown(){
		
	}
	
	public function testGetCategory(){
		
		$category=$this->appController->actionGetCategory();
		var_dump($category);
		return 1;
	}
	
	/**
	 * @dataProvider providerApp
	 */
	public function testGetApp($category,$page_size,$page_index){
		$app=$this->appController->actionGetApp($category, $page_size, $page_index);
		var_dump($app);
	}
	
	public function providerApp(){
		return array(
				array(316,2,0),
				array(182,1,1),
		);
	}
	
	/**
	 * @depends testGetCategory
	 */
	public function testHello($int){
		$this->assertEquals(2,$int);
		return "testHello";
	}
	
	/**
	 * @depends testHello
	 */
	public function testGetHello($hello){
		$this->assertEquals("testHello",$hello);
	}
	
	
}