<?php
namespace res\controllers;

/**
 * DeService TestCase
 * @author Jiangyumeng
 *
 */
class DeServiceTest extends \Sky\test\TestCase {
	/**
	 * @var object DeServiceController
	 */
	public static $desController;
	
	public static function setUpBeforeClass()
	{
		self::$desController = new DeServiceController('deService');
	}
	
	public static function tearDownAfterClass()
	{
		self::$desController->DelDevice('001a001a001a');
	}
	
	public function testRegister()
	{
		$ret=self::$desController->actionRegister("001a001a001a" ,"55E800A-001a001a001a","http","192.168.1.101","wifi:SRT");
// 		var_dump($ret);
		$this->assertEquals($ret, 1);
	}
	
	public function testGetDevices()
	{
		$ret=self::$desController->actionGetDevices('');
// 		var_dump($ret);
		$this->assertGreaterThan(0, count($ret));
	}
}