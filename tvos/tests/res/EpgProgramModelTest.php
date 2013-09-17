<?php

namespace res\models;

/**
 * EpgProgramModel test case.
 */
class EpgProgramModelTest extends \Sky\test\TestCase {
	
	/**
	 *
	 * @var EpgProgramModel
	 */
	private $EpgProgramModel;
	
	/**
	 * Prepares the environment before running a test.
	 */
	protected function setUp() {
		parent::setUp ();
		
		// TODO Auto-generated EpgProgramModelTest::setUp()
		
		//$this->EpgProgramModel = new EpgProgramModel(/* parameters */);
	}
	
	/**
	 * Cleans up the environment after running a test.
	 */
	protected function tearDown() {
		// TODO Auto-generated EpgProgramModelTest::tearDown()
		$this->EpgProgramModel = null;
		
		parent::tearDown ();
	}
	
	/**
	 * Constructs the test case.
	 */
	public function __construct() {
		// TODO Auto-generated constructor
	}
	
	/**
	 * Tests EpgProgramModel::model()
	 */
	public function testModel() {
		// TODO Auto-generated EpgProgramModelTest::testModel()
		$this->markTestIncomplete ( "model test not implemented" );
		
		EpgProgramModel::model(/* parameters */);
	}
	
	/**
	 * Tests EpgProgramModel::showepgprogram()
	 */
	public function testShowepgprogram() {
		
		
		var_dump(EpgProgramModel::showepgprogram('148474,148686,148775,150573',''));
	}
	
	/**
	 * Tests EpgProgramModel::listepgprogramcount()
	 */
	public function testListepgprogramcount() {
			
		var_dump(EpgProgramModel::listepgprogramcount(2300,'','2013-06-26 19:00','2013-08-26 19:00'));
	}
	
	/**
	 * Tests EpgProgramModel::listepgprogram()
	 */
	public function testListepgprogram() {
	
		
		var_dump(EpgProgramModel::listepgprogram(2300,'','2013-06-26 19:00','2013-08-26 19:00',0,50));
	}
}

