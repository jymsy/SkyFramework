<?php
namespace demos\tests\skyapp\unit;

use skyapp\models\SkyCategory;

class AppModelTest extends \Sky\test\TestCase{
	
	public function testGetCategory(){
		$category=SkyCategory::getCategory();
		$this->assertTrue(is_array($category));
		var_dump($category);
		$this->assertEquals(2, count($category));
	}
}

// class CacheProxy{
// 	private $obj;
// 	private $cache=array();
	
// 	function __construct($object,$time=60){
// 		$this->obj=$object;
// 	}

// public static function newInstance($object,$time){
// 	return new self($object,$time);
// }
	
// 	function __call($name,$arguments){
// 		$key=$name.":".serialize($arguments);
// 		if ($ret=Sky::$app->cache->get($key)) {
// 			return $ret;
// 		}else {
// 			$result=call_user_method($name, $this->obj,$arguments);
// 			Sky::$app->cache->set($key,$result);
// 			return $result;
// 		}
		
// 	}
	
// 	function setTime($time){
		
// 	}
// }

// class SkyCategory{
// 	function getCategory($a){
// 		return rand($a, 2*$a)."@".microtime(true);
// 	}
// }

// $cls1 = new Cls1();

// echo $cls1->fun1(23),PHP_EOL;

// echo $cls1->fun1(23),PHP_EOL;
// CacheProxy::newInstance(SkyCategory::model())->getCategory();
// $cls1 = new CacheProxy(SkyCategory::model());
// $cls1->getCategory();
// echo $cls1->fun1(23),PHP_EOL;

// echo $cls1->fun1(23),PHP_EOL;

