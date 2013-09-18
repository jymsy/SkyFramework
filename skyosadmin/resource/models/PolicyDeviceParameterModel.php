<?php

namespace resource\models;

use skyosadmin\components\PublicModel;

use Sky\db\DBCommand;

/**table 
 */
class PolicyDeviceParameterModel extends \Sky\db\ActiveRecord{
	/**
	 *@return PolicyDeviceParameterModel
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}	
	
    /**
     * 
     * @return multitype: 返回所有机型列表
     */
    public static function GetPolicyModel(){
    	
    	return parent::createSQL("select distinct `model`
    			                    from `skyg_base`.`base_policy_device_parameter`")->toList();
    }
    
    /**
     * 
     * @param string $vmodel  机型
     * @return multitype:     返回所有机芯列表
     */
    public static function GetPolicyChip($vmodel){
    	if($vmodel=='false'){
    		$sql=" where `model` is null";
    	}elseif ($vmodel!=''){
    		$sql=" where `model`='$vmodel'";
    	}
    		return parent::createSQL("select distinct `chip`
    			                    from `skyg_base`.`base_policy_device_parameter`".$sql)->toList();
    }
	
    /**
     * 
     * @param string $vchip   机芯
     * @param string $vmodel  机型
     * @return multitype:     返回所有机型列表
     */
    public static function GetPolicyPlatform($vchip,$vmodel){
    	if ($vchip=='false'){
    		$sql=" where `chip` is null ";
    	}elseif ($vchip!=""){
	    	$sql=" where `chip`='$vchip'";
	    	
    	}
    	
    	if($vmodel=="false"){
    		$sql.=" and `model` is null";
    	}elseif($vmodel!=""){
    		$sql.=" and `model`='$vmodel'";
    	}
    	
    	return parent::createSQL("select distinct `platform`
    			                    from `skyg_base`.`base_policy_device_parameter`".$sql)->toList();
    }
}