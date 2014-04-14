<?php
namespace skyapp\models;


use Sky\Sky;

/**
 * @property int Product_ID
 * @property string Product_OwnerID
 * @property string Product_OwnerName
 * @property string Product_Name
 * @property int Product_Sale_CCM
 * @property int version_id
 * @property string Product_BagName
 * @property string Product_InstallationSite
 * @property int Product_score
 * @property int Product_IsAvailable
 * @property int Product_SalesNum
 * @property int Product_DownloadNum
 * @property string REP_DESC
 * 
 * @author Jiangyumeng
 *
 */
class SkyApp extends \Sky\db\ActiveRecord{
	/**
	 * 
	 * @return SkyApp
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}
	
	protected static $tableName="sky_appstore.sky_app";
	
	public static function getApp($category,$page_size,$page_index){
		$platfrom="MS6A801V1";
		$start = $page_size*$page_index;
		
		// 		"select sa.`Product_ID`,sa.`Product_OwnerID`,sa.`Product_OwnerName`,sa.`Product_Sale_CCM`,
		// 				sa.`Product_InstallationSite`,sa.`Product_IsAvailable`,sa.`Product_Name`,sa.`Product_BagName`,
		// 				sa.`Product_score`,sav.`Product_Small_Show`,sav.`Product_Version`,sav.`Product_VersionCode`,sav.`DownloadUrl`,
		// 				sav.`Product_Size` from `sky_appstore`.`sky_app` as sa left join `sky_appstore`.`sky_category_app` as sca
		// 				on sa.`Product_ID`=sca.`Product_ID` left join `sky_appstore`.`sky_app_version` as sav on sa.`version_id`=sav.`v_id`
		// 				where sca.`platformInfo`='%s' and sca.`ProductTypeID`=%d
		// 				order by sa.`Product_score` desc,sa.`Product_ID` desc limit %d,%d";
		
		$apps=parent::command("sa")
				->select("Product_ID,`Product_OwnerID`,`Product_OwnerName`,`Product_Sale_CCM`,
				`Product_InstallationSite`,`Product_IsAvailable`,`Product_Name`,`Product_BagName`,
				`Product_score`","sa")
				->select("`Product_Small_Show`,`Product_Version`,`Product_VersionCode`,`DownloadUrl`,
				`Product_Size`","sav")
				->join("sky_appstore.sky_category_app as sca", "sa.Product_ID=sca.Product_ID")
				->join("sky_appstore.sky_app_version as sav", "sa.version_id=sav.v_id")
				->where("sca.platformInfo=:platform and sca.ProductTypeID=:category")
				->order("sa.Product_score desc,sa.Product_ID desc")
				->limit($page_size)
				->offset($start)
				->bind(array(
						"platform"=>$platfrom,
						"category"=>$category,
						))
				->toList();
		
				return $apps;
	}
	
	public static function getAppDetail($Product_ID){
		// 		"select sa.*,sav.* from sky_appstore.sky_app as sa left join sky_appstore.sky_app_version as sav
		// 				on sa.version_id=sav.v_id where sa.Product_ID=%d";
// 		$appdetail=parent::command("sa")
// 			->select("*","sa")
// 			->select("*","sav")
// 			->join("sky_appstore.sky_app_version as sav","sa.version_id=sav.v_id")
// 			->where("sa.Product_ID=:product_id")
// 			->bind(array(
// 				"product_id"=>$Product_ID,
// 				))
// 			->toList();
		$connection=Sky::app()->db;
		return $connection::createCommand(
				"select sa.*,sav.* from sky_appstore.sky_app as sa 
				left join sky_appstore.sky_app_version as sav on sa.version_id=sav.v_id 
				where sa.Product_ID=:product_id",
				array("product_id"=>$Product_ID)
				)->toList();
	}
}