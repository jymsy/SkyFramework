<?php

namespace res\models;


/**
 * CategoryModel test case.
 */
class CategoryModelTest extends \Sky\test\TestCase {
	
	/**
	 *
	 * @var CategoryModel
	 */
	private $CategoryModel;
	
	/**
	 * Prepares the environment before running a test.
	 */
	protected function setUp() {
		parent::setUp ();
		
		// TODO Auto-generated CategoryModelTest::setUp()
		
		//$this->CategoryModel = new CategoryModel(/* parameters */);
	}
	
	/**
	 * Cleans up the environment after running a test.
	 */
	protected function tearDown() {
		// TODO Auto-generated CategoryModelTest::tearDown()
		$this->CategoryModel = null;
		
		parent::tearDown ();
	}
	
	/**
	 * Constructs the test case.
	 */
	public function __construct() {
		// TODO Auto-generated constructor
	}
	
	/**
	 * Tests CategoryModel::model()
	 */
	public function testModel() {
		// TODO Auto-generated CategoryModelTest::testModel()
		$this->markTestIncomplete ( "model test not implemented" );
		
		CategoryModel::model(/* parameters */);
	}
	
	/**
	 * Tests CategoryModel::showcategorybycname()
	 */
	public function testShowcategorybycname() {
	
		
		var_dump(CategoryModel::showcategorybycname('影视',''));
	}
	
	/**
	 * Tests CategoryModel::showcategoryidbycname()
	 */
	public function testShowcategoryidbycname() {
		
		
		var_dump(CategoryModel::showcategoryidbycname('影视',''));
	}
	
	/**
	 * Tests CategoryModel::queryacategoryname()
	 */
	public function testQueryacategoryname() {

		
		var_dump(CategoryModel::queryacategoryname(1,''));
	}
	
	/**
	 * Tests CategoryModel::showcategorybycid()
	 */
	public function testShowcategorybycid() {
	
		
		var_dump(CategoryModel::showcategorybycid(1,''));
	}
	
	/**
	 * Tests CategoryModel::showcategorybyparentid()
	 */
	public function testShowcategorybyparentid() {
	
		
		var_dump(CategoryModel::showcategorybyparentid(1,''));
	}
	
	/**
	 * Tests CategoryModel::querycategorycount()
	 */
	public function testQuerycategorycount() {
		// TODO Auto-generated CategoryModelTest::testQuerycategorycount()
		
		
		var_dump(CategoryModel::querycategorycount(1,''));
	}
	
	/**
	 * Tests CategoryModel::querycategorylist()
	 */
	public function testQuerycategorylist() {
	
		
		var_dump(CategoryModel::querycategorylist(1,'',0,50));
	}
	
	/**
	 * Tests CategoryModel::querycategorypath()
	 */
	public function testQuerycategorypath() {
		// TODO Auto-generated CategoryModelTest::testQuerycategorypath()
		
		
		var_dump(CategoryModel::querycategorypath(1,''));
	}
}

