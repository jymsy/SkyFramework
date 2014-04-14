<?php
namespace skyapp\models;

/**
 * @property int id 
 * @property string platformInfo
 * @property int ProductTypeID
 * @property int Product_ID
 * 
 * @author Jiangyumeng
 */
class CategoryApp extends \Sky\db\ActiveRecord{
	protected static $tableName="sky_appstore.sky_category_app";
	
	public static function model($className=__CLASS__){
		return parent::model($className);
	}
	
	public static function getAppInfo($platform,$ap_package){
// 		select distinct sa.`Product_ID`,sa.`Product_Name`,sav.`Product_Version`,sa.`Product_BagName`,sav.`Product_VersionCode`,sa.`Product_InstallationSite`,
// 		sav.`Product_Size` from `sky_appstore`.`sky_category_app` as sca left join
// 		`sky_appstore`.`sky_app` as sa on sca.`Product_ID`=sa.`Product_ID` left join `sky_appstore`.`sky_app_version` as sav on sa.`version_id`=sav.`v_id`
// 		where sa.`Product_BagName`='%s' and sca.`platformInfo`='%s'";
		$appinfo=parent::command("sca")
// 				->command("sca")
				->select("distinct sa.`Product_ID`")
				->select("`Product_Name`,`Product_BagName`,`Product_InstallationSite`","sa")
				->select("`Product_Version`,`Product_VersionCode`,`Product_Size`","sav")
				->join("`sky_appstore`.`sky_app` as sa", "sca.`Product_ID`=sa.`Product_ID`")
				->join("`sky_appstore`.`sky_app_version` as sav", "sa.`version_id`=sav.`v_id`")
				->where("sa.`Product_BagName`=:bagName and sca.`platformInfo`=:platform")
				->bind(array(
						"bagName"=>$ap_package,
						"platform"=>$platform,
						))
				->toList();
				
		return $appinfo;
	}
}