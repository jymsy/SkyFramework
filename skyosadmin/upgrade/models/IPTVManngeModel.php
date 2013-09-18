<?php

namespace upgrade\models;

use Sky\db\DBCommand;
use skyosadmin\components\PublicModel;

/**
 * @author Zhengyun
 */
class IPTVManngeModel extends \Sky\db\ActiveRecord{
	/**
	 *@return IPTVManngeModel
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}	
	
	//protected static $tableName="skyg_res.res_advert";
	//protected static $primeKey=array("ad_id");
	
	
	
	/**
	 * 
	 * @param array $arr
	 * @return 添加成功$result>0，添加失败 $result=0
	 */
    public static function insertIPTV($arr){ 
    	extract($arr);
    	$result=parent::createSQL(
    			"INSERT INTO `skyg_base`.`base_upgrade_iptv_package` (
				  `iptv_package_name`,
				  `iptv_package_icon`,
				  `core_style`,
				  `core_chip`,
				  `area_id`,
				  `iptv_package_version`,
				  `download_url`,
				  `md5`,
				  `filesize`
				) 
				VALUES
				   (:iptv_package_name,
    				:iptv_package_icon,
    				:core_style,
	    			:core_chip,
	    			:area_id,
	    			:iptv_package_version,
	    			:download_url,
	    			:md5,
	    			:filesize)",
    			array(
    					'iptv_package_name'=>$iptv_package_name,
    					'iptv_package_icon'=>$iptv_package_icon,
    					'core_style'=>$core_style,
    					'core_chip'=>$core_chip,
    					'area_id'=>$area_id,
    					'iptv_package_version'=>$iptv_package_version,
    					'download_url'=>$download_url,
    					'md5'=>$md5,
    					'filesize'=>$filesize
    				)
    		);
    	if($result->exec()!=0){
			$result->getPdoInstance();
			$result=$result->lastInsertID();
			return $result;
		}
		return 0;
    	
    }
    
    /**
     * 
     * @param array $arr
     * @return unknown
     */
    public static function updateIPTV($arr){
    	extract($arr);
    	$result=parent::createSQL(
			"UPDATE `skyg_base`.`base_upgrade_iptv_package`
			SET `iptv_package_name`=:iptv_package_name,
			  `iptv_package_icon`=:iptv_package_icon,
			  `core_style`=:core_style,
			  `core_chip`=:core_chip,
			  `area_id`=:area_id,
			  `iptv_package_version`=:iptv_package_version,
			  `download_url`=:download_url,
			  `md5`=:md5,
			  `filesize`=:filesize 
			WHERE `iptv_package_id`= :iptv_package_id ",
    			array(
    					'iptv_package_name'=>$iptv_package_name,
    					'iptv_package_icon'=>$iptv_package_icon,
    					'core_style'=>$core_style,
    					'core_chip'=>$core_chip,
    					'area_id'=>$area_id,
    					'iptv_package_version'=>$iptv_package_version,
    					'download_url'=>$download_url,
    					'md5'=>$md5,
    					'filesize'=>$filesize,
    					'iptv_package_id'=>$iptv_package_id
    			)
    	)->exec();
    	
    	return $result;
    }
    
    /**
     * 
     * @param Int $iptv_package_id
     * @return unknown
     */
    public static function deleteIPTV($iptv_package_id){
    	$result=parent::createSQL(
    			"DELETE 
				FROM
				  `skyg_base`.`base_upgrade_iptv_package` 
				WHERE `iptv_package_id` = :iptv_package_id ",
    			array(
    					'iptv_package_id'=>$iptv_package_id
    			)
    	)->exec();
    	 
    	return $result;
    }
    
    /**
     * 
     * @param Array $searchCondition e.g. array('product_name'=>'GOOGLE','product_owner_name'=>'RSR')
     * @param Int $start
     * @param Int $limit
     * @param Array $orderCondition e.g. array("upgrade_module_id"=>"DESC")
     * @return multitype:
     */
    public static function searchIPTV($searchCondition,$start,$limit,$orderCondition=array("create_date"=>"DESC")){
    	$orderString=PublicModel::controlArray($orderCondition);
    	$searchString=PublicModel::controlsearch($searchCondition);
    	if($searchString!='')
    		$searchString=' WHERE  '.$searchString;
    	$searchString=str_replace("area_id", "a`.`area_id", $searchString);
    	$sql=sprintf(
				"SELECT 
				  a.`iptv_package_id`,
				  a.`iptv_package_name`,
				  a.`iptv_package_icon`,
				  a.`core_style`,
				  a.`core_chip`,
				  a.`area_id`,
				  a.`iptv_package_version`,
				  a.`download_url`,
				  a.`md5`,
				  a.`filesize`,
				  a.`create_date`,
				  b.province_id,
				  b.province_name,
				  b.city_name
				FROM
				  `skyg_base`.`base_upgrade_iptv_package` a 
				  LEFT JOIN 
				    (SELECT 
				      p.area_name AS province_name,
				      p.`area_id` AS province_id,
				      c.area_name AS city_name,
				      c.`area_id` AS area_id 
				    FROM
				      `skyg_base`.`base_area` p,
				      `skyg_base`.`base_area` c 
				    WHERE p.area_id = c.parent_id) b 
				    ON (
				      a.`area_id` = b.`area_id` 
				      OR a.`area_id` = b.province_id
				    ) 
    			%s
    			ORDER BY %s 
				LIMIT %d,%d",$searchString,$orderString,$start,$limit);
		$result=parent::createSQL($sql)->toList();
		return $result;    	
    	
    }
    
    /**
     * 
     * @param Int 
     * @param array $searchCondition
     * @return Ambigous <NULL, unknown>
     */
    public static function searchIPTVCount($searchCondition){
    	$searchString=PublicModel::controlsearch($searchCondition);
    	if($searchString!='')
    		$searchString=' WHERE  '.$searchString;
    	$sql=sprintf(
    			"SELECT
    			  count(*)
				FROM
				  `skyg_base`.`base_upgrade_iptv_package`
    			%s
				",$searchString);
    	$result=parent::createSQL($sql)->toValue();
    	return $result;
    	 
    }
    
    /**
     * 
     * @param Int 
     * @return multitype:
     */
    public static function getIPTVLists($start,$limit,$orderCondition=array('create_date'=>'DESC')){
    	$orderString=PublicModel::controlArray($orderCondition);
    	$sql=sprintf(
    			"SELECT 
				  a.`iptv_package_id`,
				  a.`iptv_package_name`,
				  a.`iptv_package_icon`,
				  a.`core_style`,
				  a.`core_chip`,
				  a.`area_id`,
				  a.`iptv_package_version`,
				  a.`download_url`,
				  a.`md5`,
				  a.`filesize`,
				  a.`create_date`,
				  b.province_id,
				  b.province_name,
				  b.city_name
				FROM
				  `skyg_base`.`base_upgrade_iptv_package` a 
				  LEFT JOIN 
				    (SELECT 
				      p.area_name AS province_name,
				      p.`area_id` AS province_id,
				      c.area_name AS city_name,
				      c.`area_id` AS area_id 
				    FROM
				      `skyg_base`.`base_area` p,
				      `skyg_base`.`base_area` c 
				    WHERE p.area_id = c.parent_id) b 
				    ON 
				      a.`area_id` = b.`area_id` 				     
    			ORDER BY %s 
				LIMIT %d,%d",$orderString,$start,$limit);
    	$result=parent::createSQL($sql)->toList();
    	return $result;
    	 
    }
    
    public static function getIPTVCount(){    	
    	$result=parent::createSQL("SELECT
				  count(*)
				FROM
				  `skyg_base`.`base_upgrade_iptv_package`
    			")->toValue();
    	return $result;
    
    }
    
    public static function getIPTVListByAreaID($area_id,$start,$limit,$orderCondition=array('create_date'=>'DESC')){
    	$orderString=PublicModel::controlArray($orderCondition);
    	$sql=sprintf(
    			"SELECT 
				  a.`iptv_package_id`,
				  a.`iptv_package_name`,
				  a.`iptv_package_icon`,
				  a.`core_style`,
				  a.`core_chip`,
				  a.`area_id`,
				  a.`iptv_package_version`,
				  a.`download_url`,
				  a.`md5`,
				  a.`filesize`,
				  a.`create_date`,
				  b.province_id,
				  b.province_name,
				  b.city_name
				FROM
				  `skyg_base`.`base_upgrade_iptv_package` a 
				  LEFT JOIN 
				    (SELECT 
				      p.area_name AS province_name,
				      p.`area_id` AS province_id,
				      c.area_name AS city_name,
				      c.`area_id` AS area_id 
				    FROM
				      `skyg_base`.`base_area` p,
				      `skyg_base`.`base_area` c 
				    WHERE p.area_id = c.parent_id) b 
				    ON (
				      a.`area_id` = b.`area_id` 
				      OR a.`area_id` = b.province_id
				    )  
    			WHERE a.area_id=%d 
    			ORDER BY %s
				LIMIT %d,%d",$area_id,$orderString,$start,$limit);
    	$result=parent::createSQL($sql)->toList();
    	return $result;
    
    }
    
    public static function getIPTVCountByAreaID($area_id){
    	$result=parent::createSQL("SELECT
				  count(*)
				FROM
				  `skyg_base`.`base_upgrade_iptv_package`
    			WHERE
    			   area_id=:area_id
    			",
    			array('area_id'=>$area_id)
    		)->toValue();
    	return $result;
    
    }
    
    public static function getProvinceList(){
    	$result=parent::createSQL(
    			"SELECT
			    	`area_id`,
			    	`area_name`,
			    	`parent_id`
		    	FROM
		    	   `skyg_base`.`base_area`
		    	WHERE `parent_id` = 0"
    			)->toList();
    	return $result;
    
    }
    
    public static function getAreaList($parent_id){ 
    	$result=parent::createSQL(
    			"SELECT
			    	`area_id`,
			    	`area_name`,
			    	`parent_id`
		    	FROM
		    	   `skyg_base`.`base_area`
		    	WHERE `parent_id` =:parent_id",
    			array('parent_id'=>$parent_id)
    	)->toList();
    	return $result;
    } 
    
    
}