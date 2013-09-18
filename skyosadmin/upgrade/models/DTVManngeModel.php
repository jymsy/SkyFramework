<?php

namespace upgrade\models;

use Sky\db\DBCommand;
use skyosadmin\components\PublicModel;

/**
 * @author Zhengyun
 */
class DTVManngeModel extends \Sky\db\ActiveRecord{
	/**
	 *@return DTVManngeModel
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}	
	
	//protected static $tableName="skyg_res.res_advert";
	//protected static $primeKey=array("ad_id");
	
	
	
	/**
	 * 
	 * @param array $arr
	 * @return 添加成功$result>0，添加失败 $result=0,对应dtv存在升级包$result=-1
	 */
    public static function insertDTV($arr){    	
    	extract($arr);
    	$result=parent::createSQL(
    			"SELECT
			    	count(*)
		    	FROM
		    	`skyg_base`.`base_upgrade_dtv_info`
		    	WHERE `dtv_code`= :dtv_code
    			AND `hw_version`=:hw_version",
    			array(
    				'dtv_code'=>$dtv_code,
    				'hw_version'=>$hw_version
    			)
    	)->toValue();
    	if($result!=0)
    		return -1;
    			
    	$result=parent::createSQL(
    			"INSERT INTO skyg_base.`base_upgrade_dtv_info` (
				  `dtv_name`,
				  `dtv_code`,
    			  `hw_version`,
				  `dtv_version`,
				  `download_url`,
				  `md5`,
				  `filesize`
				) 
				VALUES
				  (
				    :dtv_name,
				    :dtv_code,
    			    :hw_version,
				    :dtv_version,
				    :download_url,
				    :md5,
				    :filesize
				  )",
    			array(
    				'dtv_name'=>$dtv_name,
				    'dtv_code'=>$dtv_code,
    				'hw_version'=>$hw_version,
				    'dtv_version'=>$dtv_version,
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
    public static function updateDTV($arr){
    	extract($arr);
    	$result=parent::createSQL(
			"UPDATE 
			  skyg_base.`base_upgrade_dtv_info` 
			SET
			  `dtv_name` =:dtv_name,
			  `dtv_code` =:dtv_code,
    		  `hw_version`=:hw_version,
			  `dtv_version` =:dtv_version,
			  `download_url` = :download_url,
			  `md5` = :md5,
			  `filesize` =:filesize 
			WHERE `upgrade_dtv_id` = :upgrade_dtv_id ",
    		array(
    				'dtv_name'=>$dtv_name,
				    'dtv_code'=>$dtv_code,
    				'hw_version'=>$hw_version,
				    'dtv_version'=>$dtv_version,
				    'download_url'=>$download_url,
				    'md5'=>$md5,
				    'filesize'=>$filesize,
    				'upgrade_dtv_id'=>$upgrade_dtv_id
    			)
    	)->exec();
    	
    	return $result;
    }
    
    /**
     * 
     * @param Int $iptv_package_id
     * @return unknown
     */
    public static function deleteDTV($upgrade_dtv_id){
    	$result=parent::createSQL(
    			"DELETE 
				FROM
				  `skyg_base`.`base_upgrade_dtv_info` 
				WHERE `upgrade_dtv_id` = :upgrade_dtv_id ",
    			array(
    					'upgrade_dtv_id'=>$upgrade_dtv_id
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
    public static function searchDTV($searchCondition,$start,$limit,$orderCondition=array("create_date"=>"DESC")){
    	$orderString=PublicModel::controlArray($orderCondition);
    	$searchString=PublicModel::controlsearch($searchCondition);
    	if($searchString!='')
    		$searchString=' WHERE  '.$searchString;
    	$sql=sprintf(
				"SELECT 
    			  `upgrade_dtv_id`,
				  `dtv_name`,
				  `dtv_code`,
    			  `hw_version`,
				  `dtv_version`,
				  `download_url`,
				  `md5`,
				  `filesize`,
				  `create_date` 
				FROM
				  `skyg_base`.`base_upgrade_dtv_info`
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
    public static function searchDTVCount($searchCondition){
    	$searchString=PublicModel::controlsearch($searchCondition);
    	if($searchString!='')
    		$searchString=' WHERE  '.$searchString;
    	$sql=sprintf(
    			"SELECT
    			  count(*)
				FROM
				  `skyg_base`.`base_upgrade_dtv_info`
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
    public static function getDTVLists($start,$limit,$orderCondition=array('create_date'=>'DESC')){
    	$orderString=PublicModel::controlArray($orderCondition);
    	$sql=sprintf(
    			"SELECT 
    			  `upgrade_dtv_id`,
				  `dtv_name`,
				  `dtv_code`,
    			  `hw_version`,
				  `dtv_version`,
				  `download_url`,
				  `md5`,
				  `filesize`,
				  `create_date` 
				FROM
				  `skyg_base`.`base_upgrade_dtv_info` 				     
    			ORDER BY %s 
				LIMIT %d,%d",$orderString,$start,$limit);
    	$result=parent::createSQL($sql)->toList();
    	return $result;
    	 
    }
    
    public static function getDTVCount(){    	
    	$result=parent::createSQL("SELECT
				  count(*)
				FROM
				  `skyg_base`.`base_upgrade_dtv_info`
    			")->toValue();
    	return $result;
    
    }
    
    
    
    
}