<?php
namespace base\user\models;

/**
 * @property    int    dev_user_map_id  
 * @property    int    dev_id           
 * @property    int    user_id          
 * @property    string create_date      
 * @property    int    default_relation  
 * 
 * @author Zhengyun
 */
class BaseDevUserMap extends \Sky\db\ActiveRecord{
	/**
	 *@return BaseDevUserMap
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}
	
	protected static $tableName="skyg_base.base_dev_user_map";
}