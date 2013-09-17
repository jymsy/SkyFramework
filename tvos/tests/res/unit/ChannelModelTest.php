<?php
namespace tests\res\unit;

use res\models\ChannelModel;

/**
 * ChannelModel test case.
 * 
 * @author Zhengyun
 */
class ChannelModelTest extends \Sky\test\TestCase {
	
	//$result =ChannelModel::listepgchannelcount(3);
	//$result =ChannelModel::showepgchannel("2166,2222,2223");
	//$result =ChannelModel::listepgchannelcount(70013);
	//$result =ChannelModel::listepgchannel(0,10);
	
	public function testModel()
	{
		$obj=ChannelModel::model();
		$this->assertInstanceOf('res\models\ChannelModel',$obj);
	}
	
	/**
	 * Tests ChannelModel::showepgchannel()
	 */
	public function testShowepgchannel() 
	{
		$channels=ChannelModel::showepgchannel("2166,2222,2223");
		var_dump($channels);
		$this->assertGreaterThan(0, count($channels));
	}
	
	/**
	 * Tests ChannelModel::listepgchannelcount()
	 */
	public function testListepgchannelcount() 
	{		
		$ret=ChannelModel::listepgchannelcount('',70013);
		var_dump($ret);
		$this->assertEquals(4, $ret);
	}
	
	/**
	 * Tests ChannelModel::listepgchannel()
	 */
	public function testListepgchannel() 
	{	
		$ret=ChannelModel::listepgchannel(0,10,'');
		var_dump($ret);
		$this->assertGreaterThan(0, count($ret));
	}
}

