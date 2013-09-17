<?php
namespace sns\models;

/**
 * SnsCategoryListModel test case.
 */
class SnsCategoryListModelTest extends \Sky\test\TestCase {
	
	/**
	 *
	 * @var SnsCategoryListModel
	 */
	private $SnsCategoryListModel;
	
	/**
	 * Prepares the environment before running a test.
	 */
	protected function setUp() {
		parent::setUp ();
		
		// TODO Auto-generated SnsCategoryListModelTest::setUp()
		
		//$this->SnsCategoryListModel = new SnsCategoryListModel(/* parameters */);
	}
	
	/**
	 * Cleans up the environment after running a test.
	 */
	protected function tearDown() {
		// TODO Auto-generated SnsCategoryListModelTest::tearDown()
		$this->SnsCategoryListModel = null;
		
		parent::tearDown ();
	}
	
	/**
	 * Constructs the test case.
	 */
	public function __construct() {
		// TODO Auto-generated constructor
	}
	
	/**
	 * Tests SnsCategoryListModel::showcategorylist()
	 */
	public function testShowcategorylist() {
		var_dump(SnsCategoryListModel::showcategorylist());
	}
	
	/**
	 * Tests SnsCategoryListModel::showcategorybyname()
	 */
	public function testShowcategorybyname() {
		// TODO Auto-generated
		// SnsCategoryListModelTest::testShowcategorybyname()
		//$this->markTestIncomplete ( "showcategorybyname test not implemented" );
		
		var_dump(SnsCategoryListModel::showcategorybyname('最新分享'));
	}
	
	/**
	 * Tests SnsCategoryListModel::updatecategory()
	 */
	public function testUpdatecategory() {
		// TODO Auto-generated SnsCategoryListModelTest::testUpdatecategory()
		//$this->markTestIncomplete ( "updatecategory test not implemented" );
		
		SnsCategoryListModel::updatecategory('8','test1','test1','9998','1');
	}
	
	/**
	 * Tests SnsCategoryListModel::insertcategorylist()
	 */
	public function testInsertcategorylist() {
		// TODO Auto-generated
		// SnsCategoryListModelTest::testInsertcategorylist()
		//$this->markTestIncomplete ( "insertcategorylist test not implemented" );
		
		SnsCategoryListModel::insertcategorylist('test','test',9999);
	}
	
	/**
	 * Tests SnsCategoryListModel::showcategorybyid()
	 */
	public function testShowcategorybyid() {
		// TODO Auto-generated SnsCategoryListModelTest::testShowcategorybyid()
		//$this->markTestIncomplete ( "showcategorybyid test not implemented" );
		
		var_dump(SnsCategoryListModel::showcategorybyid('1'));
	}
}

