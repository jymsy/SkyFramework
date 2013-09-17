<?php
namespace skyapp\models;

/**
 * @property int v_id version id
 * @property int Product_ID
 * @property string Product_Big_Show
 * @property string Product_Small_Show
 * @property string Product_Version
 * @property int Product_VersionCode
 * @property int Product_Size
 * @property string Product_AddTime
 * @property string Product_language
 * @property string DownloadUrl
 * @property string developer
 * @property int vs_minsdkversion
 * @property string vs_note
 * @property string md5
 * 
 * @author Jiangyumeng
 */
class AppVersion extends \Sky\db\ActiveRecord{
	public static function model($className=__CLASS__){
		return parent::model($className);
	}
	
	protected static $tableName="sky_appstore.sky_app_version";
}