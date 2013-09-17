<?php
namespace demos\tests\skyapp\functional;

class AppTest extends \demos\tests\WebTestCase{
	public function testCategory(){
		$this->open("skyapp/app/getCategory/ws");
		echo $this->getContent()."\n";
		$this->assertEquals(200,$this->getStatusCode());
// 		echo $this->getTotalTime();
		$this->assertEquals(222,222);
	}
	
	public function testHello(){
		$this->open("skyapp/app/hello","post",array("ws"=>""));
// 		$this->assertEquals(2,2);
		echo $this->getContent();
	}
	
	public function testGetApp(){
		$this->open("skyapp/app/getApp","post",
				array(
						"a"=>316,
						"b"=>1,
						"c"=>0,
						"ws"=>"")
						);
// 		$this->open("skyapp/app/getApp/a/316/b/1/c/0/ws");
		echo $this->getContent();
	}
}