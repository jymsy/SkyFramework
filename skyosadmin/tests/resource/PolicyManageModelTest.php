<?php
namespace tests\resource;

use resource\models\PolicyManageModel;
/**
 * PolicyManageModel test case.
 */
class PolicyManageModelTest extends \Sky\test\TestCase {
	
	/**
	 *
	 * @var PolicyManageModel
	 */
	private $PolicyManageModel;
	
	/**
	 * Prepares the environment before running a test.
	 */
	protected function setUp() {
		parent::setUp ();
		
		// TODO Auto-generated PolicyManageModelTest::setUp()
		
		//$this->PolicyManageModel = new PolicyManageModel(/* parameters */);
	}
	
	/**
	 * Cleans up the environment after running a test.
	 */
	protected function tearDown() {
		// TODO Auto-generated PolicyManageModelTest::tearDown()
		$this->PolicyManageModel = null;
		
		parent::tearDown ();
	}
	
	/**
	 * Constructs the test case.
	 */
	public function __construct() {
		// TODO Auto-generated constructor
	}
	
	/**
	 * Tests PolicyManageModel::model()
	 */
	public function testModel() {
		// TODO Auto-generated PolicyManageModelTest::testModel()
		$this->markTestIncomplete ( "model test not implemented" );
		
		PolicyManageModel::model(/* parameters */);
	}
	
	/**
	 * Tests PolicyManageModel::GetPolicyList()
	 */
	public function testGetPolicyList() {
		// TODO Auto-generated PolicyManageModelTest::testGetPolicyList()
		//$this->markTestIncomplete ( "GetPolicyList test not implemented" );
		
		var_dump(PolicyManageModel::GetPolicyList(1,20));
	}
	
	/**
	 * Tests PolicyManageModel::GetPolicyCount()
	 */
	public function testGetPolicyCount() {
		// TODO Auto-generated PolicyManageModelTest::testGetPolicyCount()
		//$this->markTestIncomplete ( "GetPolicyCount test not implemented" );
		
		//var_dump(PolicyManageModel::GetPolicyCount(0,20));
	}
	
	/**
	 * Tests PolicyManageModel::InsertPolicy()
	 */
	public function testInsertPolicy() {
		// TODO Auto-generated PolicyManageModelTest::testInsertPolicy()
		$this->markTestIncomplete ( "InsertPolicy test not implemented" );
		
		PolicyManageModel::InsertPolicy(/* parameters */);
	}
	
	/**
	 * Tests PolicyManageModel::UpdatePolicy()
	 */
	public function testUpdatePolicy() {
		// TODO Auto-generated PolicyManageModelTest::testUpdatePolicy()
		$this->markTestIncomplete ( "UpdatePolicy test not implemented" );
		
		PolicyManageModel::UpdatePolicy(/* parameters */);
	}
	
	public function testGetPolicyInfo(){
		var_dump(PolicyManageModel::GetPolicyInfo(array("function_name"=>"api"),0,1));
	}
}

