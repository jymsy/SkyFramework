<?php
namespace tests\res\unit;

use res\models\AudioModel;

/**
 * AudioModel test case.
 * 
 * @author Zhengyun
 *
 */
class AudioModelTest extends \Sky\test\TestCase {
	//$result = AudioModel::showAudio("1,2,3",$sysCondition='rmt.`music_top_id`=1');
	//$result = AudioModel::ListSourcesCount(array('title'=>"Harm In Change"),"ras.sequence=0");
	//$result = AudioModel::ListSourcesDetail(array('title'=>"Harm In Change"),0,10,"ras.sequence=0");
	//$result = AudioModel::listAudioCount(array('categoryid'=>10073),"rmt.page_index=1");
	//$result = AudioModel::listAudioDetail(array('categoryid'=>10073),0,10,0,"rmt.page_index=1");
	public function testModel()
	{
		$obj=AudioModel::model();
		$this->assertInstanceOf('res\models\AudioModel',$obj);
	}

	/**
	 * Tests AudioModel::showAudio()
	 */
	public function testShowAudio() 
	{
		$result=AudioModel::showAudio("1,2,3",'rmt.`music_top_id`=1');
		var_dump($result);
		$this->assertGreaterThan(0, count($result));
	}
	
	/**
	 * Tests AudioModel::ListSourcesCount()
	 */
	public function testListSourcesCount() 
	{
		$result=AudioModel::ListSourcesCount(array('title'=>"Harm In Change"),"ras.sequence=0");
		var_dump($result);
		$this->assertGreaterThan(0, $result);
	}
	
	/**
	 * Tests AudioModel::ListSourcesDetail()
	 */
	public function testListSourcesDetail() 
	{		
		$result=AudioModel::ListSourcesDetail(array('title'=>"Harm In Change"),0,10,"ras.sequence=0");
		var_dump($result);
		$this->assertGreaterThan(0, count($result));
	}
	
	/**
	 * Tests AudioModel::listAudioCount()
	 */
	public function testListAudioCount() 
	{
		$result=AudioModel::listAudioCount(array('categoryid'=>10073),"rmt.page_index=1");
		var_dump($result);
		$this->assertGreaterThan(0, $result);
	}
	
	/**
	 * Tests AudioModel::listAudioDetail()
	 */
	public function testListAudioDetail() 
	{		
		$result=AudioModel::listAudioDetail(array('categoryid'=>10073),0,10,0,"rmt.page_index=1");
		var_dump($result);
		$this->assertGreaterThan(0, count($result));
	}
}

