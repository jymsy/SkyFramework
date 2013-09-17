<?php
class HelloUnit{
	function add($a,$b){
		return $a+$b;
	}
}

class HelloUnitTest extends PHPUnit_Framework_TestCase{
	function setUp(){
		echo "OnSetUp ";
	}
	function tearDown(){
		echo "OnTearDown ";
	}
	
	function testAdd(){
		$helloUnit = new HelloUnit();
		$this->assertEquals(3, $helloUnit->add(1, 2));
	}
	
	function testFailed(){
		$this->assertTrue(false);
	}
}