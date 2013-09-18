<?php
namespace tests\circle\unit;
use circle\models\CircleManageModel;

/**
 * CircleManageModel test case.
 */
class CircleManageModelTest extends \Sky\test\TestCase {
	
	/**
	 *
	 * @var CircleManageModel
	 */
	private $CircleManageModel;
	
	/**
	 * Prepares the environment before running a test.
	 */
	protected function setUp() {
		parent::setUp ();
		
		// TODO Auto-generated CircleManageModelTest::setUp()
		
		//$this->CircleManageModel = new CircleManageModel(/* parameters */);
	}
	
	/**
	 * Cleans up the environment after running a test.
	 */
	protected function tearDown() {
		// TODO Auto-generated CircleManageModelTest::tearDown()
		$this->CircleManageModel = null;
		
		parent::tearDown ();
	}
	
	
	
	/**
	 * Tests CircleManageModel::deleteCircle()
	 */
	public function testDeleteCircle() {
		// TODO Auto-generated CircleManageModelTest::testDeleteCircle()
		//$this->markTestIncomplete ( "deleteCircle test not implemented" );
		
		CircleManageModel::deleteCircle(8831);
	}
	
	/**
	 * Tests CircleManageModel::updateCircle()
	 */
	public function testUpdateCircle() {
		// TODO Auto-generated CircleManageModelTest::testUpdateCircle()
		//$this->markTestIncomplete ( "updateCircle test not implemented" );
		
		var_dump(CircleManageModel::updateCircle(array('u_id'=>11001,
					  'circle_title'=>'u_circle_title',
					  'circle_content'=>'u_circle_content',
					  'circle_pic'=>'u_circle_pic',
					  'circle_state'=>1,
					  'from_res_id'=>999,
					  'from_res_type'=>99,
					  'official'=>0,
					  'cc_id'=>5,
					  'max_user_count'=>155,
		              'circle_id'=>8833)));
	}
	
	/**
	 * Tests CircleManageModel::insertCircle()
	 */
	public function testInsertCircle() {
		// TODO Auto-generated CircleManageModelTest::testInsertCircle()
		//$this->markTestIncomplete ( "insertCircle test not implemented" );
		
		var_dump(CircleManageModel::insertCircle(array('u_id'=>11001,
					  'circle_title'=>'circle_title',
					  'circle_content'=>'circle_content',
					  'circle_pic'=>'circle_pic',
					  'circle_state'=>1,
					  'from_res_id'=>111,
					  'from_res_type'=>12,
					  'official'=>0,
					  'cc_id'=>4,
					  'max_user_count'=>150)));
	}
	
	/**
	 * Tests CircleManageModel::getCircleByID()
	 */
	public function testGetCircleByID() {
		// TODO Auto-generated CircleManageModelTest::testGetCircleByID()
		//$this->markTestIncomplete ( "getCircleByID test not implemented" );
		
		var_dump(CircleManageModel::getCircleByID(8833));
	}
	
	/**
	 * Tests CircleManageModel::getAllCircleCount()
	 */
	public function testGetAllCircleCount() {
		// TODO Auto-generated CircleManageModelTest::testGetAllCircleCount()
		//$this->markTestIncomplete ( "getAllCircleCount test not implemented" );
		
		var_dump(CircleManageModel::getAllCircleCount(1));
	}
	
	/**
	 * Tests CircleManageModel::getAllCircleList()
	 */
	public function testGetAllCircleList() {
		// TODO Auto-generated CircleManageModelTest::testGetAllCircleList()
		//$this->markTestIncomplete ( "getAllCircleList test not implemented" );
		
		var_dump(CircleManageModel::getAllCircleList(0,0,10));
	}
	
	/**
	 * Tests CircleManageModel::searchCircleCount()
	 */
	public function testSearchCircleCount() {
		// TODO Auto-generated CircleManageModelTest::testSearchCircleCount()
		//$this->markTestIncomplete ( "searchCircleCount test not implemented" );
		
		var_dump(CircleManageModel::searchCircleCount(array('cc_name'=>"地区")));
	}
	
	/**
	 * Tests CircleManageModel::searchCircle()
	 */
	public function testSearchCircle() {
		// TODO Auto-generated CircleManageModelTest::testSearchCircle()
		//$this->markTestIncomplete ( "searchCircle test not implemented" );
		
		var_dump(CircleManageModel::searchCircle(array('cc_name'=>"地区"),0,10));
	}
	
	/**
	 * Tests CircleManageModel::insertCircleCategory()
	 */
	public function testInsertCircleCategory() {
		// TODO Auto-generated CircleManageModelTest::testInsertCircleCategory()
		//$this->markTestIncomplete ( "insertCircleCategory test not implemented" );
		
		var_dump(CircleManageModel::insertCircleCategory(array('cc_name'=>'cc_name',
						'logo'=>'logo',
						'cc_order'=>99)));
	}
	
	/**
	 * Tests CircleManageModel::updateCircleCategory()
	 */
	public function testUpdateCircleCategory() {
		// TODO Auto-generated CircleManageModelTest::testUpdateCircleCategory()
		//$this->markTestIncomplete ( "updateCircleCategory test not implemented" );
		
		var_dump(CircleManageModel::updateCircleCategory(array('cc_name'=>'u_cc_name',
						'logo'=>'u_logo',
						'cc_order'=>199,'cc_id'=>6)));
	}
	
	/**
	 * Tests CircleManageModel::deleteCircleCategory()
	 */
	public function testDeleteCircleCategory() {
		// TODO Auto-generated CircleManageModelTest::testDeleteCircleCategory()
		//$this->markTestIncomplete ( "deleteCircleCategory test not implemented" );
		
		CircleManageModel::deleteCircleCategory(5);
	}
	
	/**
	 * Tests CircleManageModel::getCircleCategoryCount()
	 */
	public function testGetCircleCategoryCount() {
		// TODO Auto-generated
		// CircleManageModelTest::testGetCircleCategoryCount()
		//$this->markTestIncomplete ( "getCircleCategoryCount test not implemented" );
		
		var_dump(CircleManageModel::getCircleCategoryCount());
	}
	
	/**
	 * Tests CircleManageModel::getCircleCategoryList()
	 */
	public function testGetCircleCategoryList() {
		// TODO Auto-generated
		// CircleManageModelTest::testGetCircleCategoryList()
		//$this->markTestIncomplete ( "getCircleCategoryList test not implemented" );
		
		var_dump(CircleManageModel::getCircleCategoryList(0,10));
	}
	
	/**
	 * Tests CircleManageModel::searchCircleCategoryCount()
	 */
	public function testSearchCircleCategoryCount() {
		// TODO Auto-generated
		// CircleManageModelTest::testSearchCircleCategoryCount()
		//$this->markTestIncomplete ( "searchCircleCategoryCount test not implemented" );
		
		var_dump(CircleManageModel::searchCircleCategoryCount(array('cc_name'=>"频道")));
	}
	
	/**
	 * Tests CircleManageModel::searchCircleCategoryList()
	 */
	public function testSearchCircleCategoryList() {
		// TODO Auto-generated
		// CircleManageModelTest::testSearchCircleCategoryList()
		//$this->markTestIncomplete ( "searchCircleCategoryList test not implemented" );
		
		var_dump(CircleManageModel::searchCircleCategoryList(array('cc_name'=>"频道"),0,10));
	}
}

