<?php
namespace demos\models;

class PolicyModel extends \Sky\db\ActiveRecord{

	/**
	 *@return PolicyModel
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}
	protected static $tableName="skyg_base.base_policy_conf";
	protected static $primeKey=array("policy_id");
	
	
	public static function querypolicy($fucname,$v_chip,$v_model,$v_platform,$v_screen_size,$v_para){
	
		
		$wheres = "(`platform`='' or `platform`='".addslashes($v_platform)."') and ";
		$wheres .="(`model`='' or `model`='".addslashes($v_model)."') and ";
		$wheres .="(`chip`='' or `chip`='".addslashes($v_chip)."') and ";
		$wheres .="(`screen_size`='' or `screen_size`='".addslashes($v_screen_size)."') ";
		return parent::createSQL(
				"select `policy_value` from `skyg_base`.`base_policy_conf` where `function_name`='".addslashes($fucname)."' and `remark`='".addslashes($v_para)."' and ".$wheres."
				order by model desc,chip desc,screen_size desc limit 1 "
		)->toValue();
	}
}