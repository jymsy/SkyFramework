<?php
namespace skyapp\models;
use \Sky\Sky;
/**
 * @property string platform
 * @property int ProductTypeID
 * @property string ProductTypeName
 * @property string ProductTypeImgUrl
 * @property int ProductCount
 * @property int ProductCoocaaCount
 * @author Jiangyumeng
 */
class SkyCategory extends \Sky\db\ActiveRecord{
	/**
	 * Returns the static model of the specified AR class.
	 * @return ActiveRecord the static model class
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}
	
	protected static $tableName="sky_appstore.sky_category";
	
	public static function getCategory(){
		$platform=chr(0xbf) . chr(0x27) . " OR 1=1 /*";
		// 		$comment=SkyCategory::model()->fetchData(array());
// 		$category=parent::findAll(
// 				array("platform"=>$platfrom),
// 				array("order"=>"FIELD(ProductTypeID,316,182,184,314)")
// 		);
// 		$category=self::model()->find(
// 					array("platform"=>$platfrom,"ProductTypeID"=>316),
// 					array()
// 				);
// 		$connection=\Sky\Sky::app()->db;
		
// 		$category=$connection::createCommand(
		$category=parent::createSQL(
				"select * from sky_appstore.sky_category where platform=:platform",
				array("platform"=>$platform)
				)->toList();
		return $category;
	}
}