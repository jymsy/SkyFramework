<?php
namespace base\models;

class DeviceConfigModel extends \Sky\db\ActiveRecord{

	/**
	 *@return DeviceConfigModel
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}
	protected static $tableName="skyg_base.base_policy_device_parameter";
	protected static $primeKey=array("dev_par_id");
	
	/**
	 * 
	 * @return multitype:  返回机芯，机型列表
	 */
	public static function getDeviceInfo(){
	
	    
		return parent::createSQL(
				"SELECT `chip`,`model` FROM `skyg_base`.`base_policy_device_parameter` GROUP BY `chip`,`model` ORDER BY `chip`"
		)->toList();
	}
	
	/**
	 * 
	 * 把DEVICE表数据GROUP BY出来放入base_policy_device_parameter中
	 */
	public static function insertDeviceInfo(){
		return parent::createSQL("CALL `skyg_base`.`proc_import_device_parameter`()")->toValue();
	}
}