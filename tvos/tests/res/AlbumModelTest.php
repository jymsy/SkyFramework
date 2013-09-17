<?php

namespace res\models;

/**
 * @author xiaokeming
 */
class AlbumModelTest extends \Sky\test\TestCase {
	
	
	/**
	 * Tests AlbumModel::model()
	 */
	public function testModel() {
		
		
		AlbumModel::model(/* parameters */);
	}
	
	/**
	 * Tests AlbumModel::listsourcescount()
	 */
	public function testListsourcescount() {
	
		
		var_dump(AlbumModel::listsourcescount('梁晓雪',''));
	}
	
	/**
	 * Tests AlbumModel::listsources()
	 */
	public function testListsources() {

		var_dump(AlbumModel::listsources('梁晓雪',0,50,''));
	}
}

