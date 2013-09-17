<?php
namespace base\user\models;

/**
 * @property int     user_id          
 * @property int     operator_code    
 * @property int     seqence_id       
 * @property string  birthday         
 * @property int     sex              
 * @property string  user_realname
 * @property string  card_id          
 * @property string  telephone_no     
 * @property string  address          
 * @property string  remark           
 * @property string  create_date      
 * @property string  last_update_date  
 * 
 * @author Zhengyun
 */
class UserInfoModel extends \Sky\db\ActiveRecord{
	/**
	 *@return UserInfoModel
	 */	
	public static function model($className=__CLASS__){
		return parent::model($className);
	}
	
	protected static $tableName="skyg_base.base_user_detail";
	protected static $primeKey=array("user_id");
}
