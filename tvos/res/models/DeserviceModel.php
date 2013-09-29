<?php

namespace res\models;

/**table res_deservice
 * @property  string       devid          设备ID                     
 * @property  string       devname        设备名称                 
 * @property  string       protocols      支持的多屏互动协议  
 * @property  string       intranetip     内网IP，会重复  
 * @property  string       wanip          外网IP，会重复       
 * @property  string       intranettoken  内网信息标识                         
 * @author XiaoKeMing
 */
class DeserviceModel extends \Sky\db\ActiveRecord{
	/**
	 *@return DeserviceModel
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}

	protected static $tableName="skyg_res.res_deservice";
	protected static $primeKey=array("devid");
	
	/**
	 * 
	 * @param string $v_ip   ip
	 * @return multitype:    返回此IP下的所有设备列表
	 */
	public static function getDeserviceList($v_ip){
		
		return parent::createSQL("select `devid`,
				                         `devname`,
				                         `protocols`,
				                         `intranetip`,
				                         `wanip`,
				                         `intranettoken`
				                    from `skyg_res`.`res_deservice`
				                   where `wanip`=:v_ip",
				                 array( "v_ip"=>$v_ip
				                 		))->toList();
	}
	
	/**
	 * 
	 * @property  string       $v_devid          设备ID                     
     * @property  string       $v_devname        设备名称                 
     * @property  string       $v_protocols      支持的多屏互动协议  
     * @property  string       $v_intranetip     内网IP，会重复  
     * @property  string       $v_wanip          外网IP，会重复       
     * @property  string       $v_intranettoken  内网信息标识   
	 * @return number
	 */
	public static function insertDeservice($v_devid,$v_devname,$v_protocols,$v_intranetip,$v_wanip,$v_intranettoken){
		
		return parent::createSQL("replace into `skyg_res`.`res_deservice`(`devid`,
				                                                         `devname`,
				                                                         `protocols`,
				                                                         `intranetip`,
				                                                         `wanip`,
				                                                         `intranettoken`)
				                                                   values(:v_devid,
				                                                          :v_devname,
				                                                          :v_protocols,
				                                                          :v_intranetip,
				                                                          :v_wanip,
				                                                          :v_intranettoken)",
				                                                  array("v_devid"=>$v_devid,
				                                                  		"v_devname"=>$v_devname,
				                                                  		"v_protocols"=>$v_protocols,
				                                                  		"v_intranetip"=>$v_intranetip,
				                                                  		"v_wanip"=>$v_wanip,
				                                                  		"v_intranettoken"=>$v_intranettoken
				                                                  		))->exec();
	}
	
	
	/**
	 * 
	 * @param string $v_devid   设备ID
	 * @return number
	 */
	public static function deleteDeserviceByDevid($v_devid){
		
		return parent::createSQL("delete from `skyg_res`.`res_deservice`
				                        where `devid`=:v_devid",
				                  array("v_devid"=>$v_devid
				                  		))->exec();
	}

}