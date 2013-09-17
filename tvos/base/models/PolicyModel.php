<?php
namespace base\models;

class PolicyModel extends \Sky\db\ActiveRecord{

	/**
	 *@return PolicyModel
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}
	protected static $tableName="skyg_base.base_policy_conf";
	protected static $primeKey=array("policy_id");
	
	
	public static function queryPolicy($fucname,$v_chip,$v_model,$v_platform,$v_screen_size,$v_mac,$v_para,$v_version){
	
	    
		$wheres = "(`platform`='' or `platform`='".addslashes($v_platform)."') and ";
		$wheres .="(`model`='' or `model`='".addslashes($v_model)."') and ";
		$wheres .="(`chip`='' or `chip`='".addslashes($v_chip)."') and ";
		$wheres .="(`screen_size`='' or `screen_size`='".addslashes($v_screen_size)."') and ";
		$wheres .="(`mac_start`='' or (`mac_start`<='".addslashes($v_mac)."' and `mac_end`>='".addslashes($v_mac)."')) and ";
		$wheres .="(`version`='' or `version`='".addslashes($v_version)."')";
		$result = parent::createSQL(
				"select `policy_value` from `skyg_base`.`base_policy_conf` 
				  where `function_name`='".addslashes($fucname)."' 
				    and `remark`='".addslashes($v_para)."' 
				    and ".$wheres."
				    and flag=0
				  order by priority desc limit 1 "
		)->toValue();
		
		if(empty($result)){
			$result = parent::createSQL(
					"select `policy_value` from `skyg_base`.`base_policy_conf`
				  where `function_name`=substring_index('".addslashes($fucname)."','/',1)
				    and `remark`='".addslashes($v_para)."'
				    and ".$wheres."
				    and flag=0
				  order by priority desc limit 1 "
			)->toValue();
		}
		
		return $result;
	}
}