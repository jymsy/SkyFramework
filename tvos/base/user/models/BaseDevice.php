<?php
namespace base\user\models;

/**
 * @property  int          dev_id            设备id                  
 * @property  string       dev_mac           设备mac地址           
 * @property  string       dev_type          设备类型              
 * @property  string       chip              机芯                    
 * @property  string       model             机型                    
 * @property  string       system_version    当前系统版本        
 * @property  string       platform          平台                    
 * @property  string       barcode           序列号                 
 * @property  string       screen_size       屏幕尺寸              
 * @property  string       screen_type       屏幕类型              
 * @property  string       create_date       创建时间              
 * @property  string       last_update_date  最后一次修改时间
 * 
 * @author Zhengyun
 */
class BaseDevice extends \Sky\db\ActiveRecord{
	/**
	 *@return BaseDevice
	 */	
	public static function model($className=__CLASS__){
		return parent::model($className);
	}
	
	protected static $tableName="skyg_base.base_device";
	protected static $primeKey=array("dev_id");	
	
	/**
	 * 通过mac地址查询admin id
	 * @param String  mac  mac地址
	 * @return array  admin_user_id
	 */
	public static function queryAdmin($mac){
		$result=parent::createSQL(
				"SELECT user_id FROM skyg_base.`base_dev_user_map` WHERE dev_mac=:mac",
				array(
						"mac"=>$mac
				)
		)->toValue();		
		return $result;
	}
	
	
	/**
	 * 添加admin用户和设备的绑定关系
	 * @param String  mac  
	 * @param Int user_id
	 * @return Int 1-添加成功，0-添加失败
	 */
	public static function addDevUserMap($mac,$user_id){
		$result=parent::createSQL(
				"INSERT INTO `skyg_base`.`base_dev_user_map` (dev_mac,user_id) VALUES (:mac,:user_id)",
				array(
						"mac"=>$mac,
						"user_id"=>$user_id
				)
	
		)->exec();
		return $result;
	}
	
	/**
	 * 删除admin用户和设备的关系
	 * @param String  mac
	 * @param Int user_id
	 * @return Int 1-删除成功，0-删除失败
	 */
	public static function delDevUserMap($mac,$user_id){
		$result=parent::createSQL(
				"DELETE FROM  `skyg_base`.`base_dev_user_map` WHERE dev_mac=:mac AND user_id=:user_id",
				array(
						"mac"=>$mac,
						"user_id"=>$user_id
				)
	
		)->exec();
		return $result;
	}
	
	/**
	 * 添加设备信息
	 * @property   string  dev_mac         
     * @property   string  chip              
     * @property   string  model             
     * @property   string  system_version    
     * @property   string  platform          
     * @property   string  barcode           
     * @property   string  screen_size       
     * @property   string  screen_type  
	 * @return Int 1-添加成功，0-添加失败
	 */
	public static function addDevice($dev_mac,$chip,$model,$system_version,
			                            $platform,$barcode,$screen_size,$resolution){
		$sql=sprintf("insert into  skyg_base.base_device(dev_mac,chip,model,system_version,
													platform,barcode,screen_size,resolution,create_date) 
				                        values  ( '%s','%s','%s','%s',
				                                   '%s','%s','%s','%s',now())",$dev_mac,
						$chip,$model,$system_version,$platform,
						$barcode,$screen_size,$resolution);
		$result=parent::createSQL($sql)->exec();		
				
		return $result;		 
	}	

	
	/**通过mac查询设备信息
	 * 
	 * @param String $dev_mac
	 * @return array
	 */
	public static function getDeviceInfoByMac($dev_mac){
		$result=parent::createSQL(
				"SELECT
				  `dev_type`,
				  `chip`,
				  `model`,
				  `system_version`,
				  `platform`,
				  `barcode`,
				  `screen_size`,
				  `resolution` 
				FROM
				  `skyg_base`.`base_device`
				WHERE `dev_mac` = :dev_mac ",
				array(
						"dev_mac"=>$dev_mac
				)
		)->toList();
		
		if(!empty($result))		
			return $result[0];
	
		return $result;
	}
	
	/**更新设备信息
	 *
	* @param String $dev_mac
	* @return array
	*/
	public static function updateDevice($dev_mac,$chip,$model,$system_version,
			                            $platform,$barcode,$screen_size,$resolution){
		$result=parent::createSQL(
				"UPDATE 
				  `skyg_base`.`base_device` 
				SET
				  `chip` = :chip,
				  `model` = :model,
				  `system_version` = :system_version,
				  `platform` = :platform,
				  `barcode` = :barcode,
				  `screen_size` = :screen_size,
				  `resolution`= :resolution 
				WHERE dev_mac = :dev_mac  ",
				array(
						"chip"=>$chip,
						"model"=>$model,
						"system_version"=>$system_version,
						"platform"=>$platform,
						"barcode"=>$barcode,
						"screen_size"=>$screen_size,
						"resolution"=>$resolution,
						"dev_mac"=>$dev_mac
				)
		)->exec();	
	
		return $result;
	}
	
	/**设备信息添加/更新，如果该mac没有就添加，如果有就更新
	 * @property   string  dev_mac         
     * @property   string  chip              
     * @property   string  model             
     * @property   string  system_version    
     * @property   string  platform          
     * @property   string  barcode           
     * @property   string  screen_size       
     * @property   string  resolution  
	 * @return Int 1-添加成功，0-添加失败
	*/
	public static function replaceDevice($dev_mac,$chip,$model,$system_version,
			$platform,$barcode,$screen_size,$resolution){
		$result=parent::createSQL(
				"replace into  skyg_base.base_device(dev_mac,chip,model,system_version,
													platform,barcode,screen_size,resolution) 
				                        values  ( :dev_mac,:chip,:model,:system_version,
				                                   :platform,:barcode,:screen_size,:resolution)",
				array(
						"dev_mac"=>$dev_mac,
						"chip"=>$chip,
						"model"=>$model,
						"system_version"=>$system_version,
						"platform"=>$platform,
						"barcode"=>$barcode,
						"screen_size"=>$screen_size,
						"resolution"=>$resolution,
				)
		)->exec();
	
		return $result;
	}
	
	/**删除对应mac的设备信息
	 * 
	 * @param string $dev_mac
	 * @return number
	 */
	public static function delDevice($dev_mac){
		$result=parent::createSQL(
				"delete from  skyg_base.base_device where dev_mac=:dev_mac",
				array(
						"dev_mac"=>$dev_mac
				)
		)->exec();	
		return $result;
	}

	
}