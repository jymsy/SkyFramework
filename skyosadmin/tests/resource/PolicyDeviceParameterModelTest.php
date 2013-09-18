<?php

namespace tests\resource;

use resource\models\PolicyDeviceParameterModel;
/**
 * PolicyDeviceParameterModel test case.
 */
class PolicyDeviceParameterModelTest extends \Sky\test\TestCase {
	
	/**
	 *
	 * @var PolicyDeviceParameterModel
	 */
	private $PolicyDeviceParameterModel;
	
	/**
	 * Prepares the environment before running a test.
	 */
	protected function setUp() {
		parent::setUp ();
		
		// TODO Auto-generated PolicyDeviceParameterModelTest::setUp()
		
		//$this->PolicyDeviceParameterModel = new PolicyDeviceParameterModel(/* parameters */);
	}
	
	/**
	 * Cleans up the environment after running a test.
	 */
	protected function tearDown() {
		// TODO Auto-generated PolicyDeviceParameterModelTest::tearDown()
		$this->PolicyDeviceParameterModel = null;
		
		parent::tearDown ();
	}
	
	/**
	 * Constructs the test case.
	 */
	public function __construct() {
		// TODO Auto-generated constructor
	}
	
	/**
	 * Tests PolicyDeviceParameterModel::model()
	 */
	public function testModel() {
		// TODO Auto-generated PolicyDeviceParameterModelTest::testModel()
		$this->markTestIncomplete ( "model test not implemented" );
		
		PolicyDeviceParameterModel::model(/* parameters */);
	}
	
	/**
	 * Tests PolicyDeviceParameterModel::GetPolicyModel()
	 */
	public function testGetPolicyModel() {
		// TODO Auto-generated
		// PolicyDeviceParameterModelTest::testGetPolicyModel()
		//$this->markTestIncomplete ( "GetPolicyModel test not implemented" );
		
		PolicyDeviceParameterModel::GetPolicyModel();
	}
	
	/**
	 * Tests PolicyDeviceParameterModel::GetPolicyChip()
	 */
	public function testGetPolicyChip() {
		// TODO Auto-generated
		// PolicyDeviceParameterModelTest::testGetPolicyChip()
		//$this->markTestIncomplete ( "GetPolicyChip test not implemented" );
		
		PolicyDeviceParameterModel::GetPolicyChip('8S07');
	}
	
	/**
	 * Tests PolicyDeviceParameterModel::GetPolicyPlatform()
	 */
	public function testGetPolicyPlatform() {
		// TODO Auto-generated
		// PolicyDeviceParameterModelTest::testGetPolicyPlatform()
		//$this->markTestIncomplete ( "GetPolicyPlatform test not implemented" );
		
		PolicyDeviceParameterModel::GetPolicyPlatform('MST-6A818','8S07');
	}
}

