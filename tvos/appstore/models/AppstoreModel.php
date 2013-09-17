<?php
namespace appstore\models;
/**
 *           
 * 
 * @author xiaokeming
 */

class AppstoreModel extends \Sky\db\ActiveRecord{
	/**
	 *@return AppstoreModel
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}
	
    /* for get_app */
    /**
     * 
     * @param string $platformInfo   platform值
     * @return multitype:            
     */
	public static function getCategory(){
		
					
	    return parent::createSQL("SELECT 
									  `product_type_id`,
									  `product_type_name`,
									  `product_type_img_url`
									FROM
									  `skyg_appstore`.`appstore_type` ")->toList();
	}
	/**
	 * 
	 * @param string $platformInfo     platform值
	 * @param int    $productTypeId    分类ID
	 * @param int    $pageSize         
	 * @param int    $pageIndex
	 * @return multitype:
	 */
	public static function getApp($platformInfo, $productTypeId, $pageSize, $pageIndex){
		$start = $pageSize*$pageIndex;
		return parent::createSQL("SELECT 
									  aai.`product_id`,
									  aai.`controller_type`,
									  aai.`product_owner_id`,
									  aai.`product_owner_name`,
									  aai.`product_sale_ccm`,
									  aai.`product_installation_site`,
									  aai.`product_is_available`,
									  aai.`product_name`,
				                      aai.`rep_desc`,
									  aai.`product_bag_name`,
									  aai.`product_score`,
				                      aav.`product_big_show`,
									  aav.`product_small_show`,
									  aav.`product_version`,
									  aav.`product_version_code`,
									  aav.`download_url`,
									  aav.`product_size`,
									  aav.`vs_note` 
									FROM `skyg_appstore`.`appstore_app_type_map` AS aatm,
				                         `skyg_appstore`.`appstore_app_item` AS aai,
				                         `skyg_appstore`.`appstore_app_version` AS aav 
									WHERE aai.`product_id` = aatm.`product_id`
				                      AND aai.`product_id` = aav.`product_id`
				                      AND aai.`version_id` = aav.`version_id`
				                      AND aatm.`platform_info` = :platforminfo 
									  AND aatm.`product_type_id` = :producttypeid 
									  AND aai.`product_is_available` = 1 
									ORDER BY aav.`product_add_time` DESC,
									  aai.`product_score` DESC 
									LIMIT :star, :pagesize ",
				               array("platforminfo"=>$platformInfo,
				               		 "producttypeid"=>(int)$productTypeId,
				               		 "pagesize"=>(int)$pageSize,
				               		 "star"=>(int)$start
				               		  ))->toList();
		
		
	}
	
	/**
	 * 
	 * @param string $platformInfo    platform值
	 * @param int    $productTypeId   分类ID
	 * @return multitype:
	 */
	public static function getAppCount($platformInfo, $productTypeId){
		return parent::createSQL("SELECT count(1)
									FROM `skyg_appstore`.`appstore_app_type_map` AS aatm,
				                         `skyg_appstore`.`appstore_app_item` AS aai,
				                         `skyg_appstore`.`appstore_app_version` AS aav
									WHERE aai.`product_id` = aatm.`product_id`
				                      AND aai.`product_id` = aav.`product_id`
				                      AND aai.`version_id` = aav.`version_id`
				                      AND aatm.`platform_info` = :platforminfo
									  AND aatm.`product_type_id` = :producttypeid
									  AND aai.`product_is_available` = 1",
				array("platforminfo"=>$platformInfo,
					  "producttypeid"=>(int)$productTypeId
				))->toValue();
	
	
	}
	
	/**
	 * 
	 * @param int $Product_ID     应用ID
	 * @return multitype:
	 */
	public static function getAppDetail($Product_ID){
		return parent::createSQL("SELECT 
									  aai.`product_id`,
									  aai.`product_owner_id`,
									  aai.`product_owner_name`,
									  aai.`product_name`,
									  aai.`version_id`,
									  aai.`product_bag_name`,
									  aai.`product_score`,
									  aai.`product_is_available`,
									  aai.`product_sales_num`,
									  aai.`product_download_num`,
									  aai.`rep_desc`,
									  aai.`controller_type`,
									  aai.`product_sale_ccm`,
									  aai.`product_installation_site`,
									  aav.`md5`,
									  aav.`product_add_time`,
									  aav.`product_big_show`,
									  aav.`download_url`,
									  aav.`developer`,
									  aav.`product_language`,
									  aav.`product_size`,
									  aav.`product_small_show`,
									  aav.`product_version`,
									  aav.`product_version_code`,
									  aav.`vs_minsdkversion`,
									  aav.`vs_note` 
									FROM
									  `skyg_appstore`.`appstore_app_item` AS aai,
				                      `skyg_appstore`.`appstore_app_version` AS aav 
									WHERE aai.`product_id` = aav.`product_id`
				                      AND aai.version_id = aav.version_id 
				                      AND aai.product_id = :productid
									  AND aai.product_is_available = 1",
				              array( "productid"=>$Product_ID
				              		 ))->toList();
										
	}
	
	/**
	 *
	 * @param string $v_bagname   拼接好的应用包名
	 * @return multitype:
	 */
	public static function getSpecialAppDetail($v_bagname){
		return parent::createSQL("SELECT
									   aai.`product_id`,
									  aai.`product_owner_id`,
									  aai.`product_owner_name`,
									  aai.`product_name`,
									  aai.`version_id`,
									  aai.`product_bag_name`,
									  aai.`product_score`,
									  aai.`product_is_available`,
									  aai.`product_sales_num`,
									  aai.`product_download_num`,
									  aai.`rep_desc`,
									  aai.`controller_type`,
									  aai.`product_sale_ccm`,
									  aai.`product_installation_site`,
									  aav.`md5`,
									  aav.`product_add_time`,
									  aav.`product_big_show`,
									  aav.`download_url`,
									  aav.`developer`,
									  aav.`product_language`,
									  aav.`product_size`,
									  aav.`product_small_show`,
									  aav.`product_version`,
									  aav.`product_version_code`,
									  aav.`vs_minsdkversion`,
									  aav.`vs_note` 
									FROM   `skyg_appstore`.`appstore_app_item` AS aai,
									  `skyg_appstore`.`appstore_app_version` AS aav
									WHERE  aai.`product_id` = aav.`product_id`
									  AND aai.`version_id` = aav.`version_id`
									  AND aai.`product_bag_name` IN (".$v_bagname.") 
				                      AND aai.`product_is_available` = 1"
		)->toList();
	}
	
	/**
	 * 
	 * @param string $platformInfo    platform值
	 * @param string $appname         应用名称
	 * @param int    $startpos
	 * @param int    $len
	 * @return multitype:
	 */
	public static function searchApp($platformInfo,$appname,$startpos=0,$len=10000){
		return parent::createSQL("SELECT 
									  aai.`product_id`,
									  aai.`controller_type`,
									  aai.`product_owner_id`,
									  aai.`product_owner_name`,
									  aai.`product_sale_ccm`,
									  aai.`product_installation_site`,
									  aai.`product_is_available`,
									  aai.`product_name`,
									  aai.`product_bag_name`,
									  aai.`product_score`,
									  aav.`product_small_show`,
									  aav.`product_version`,
									  aav.`product_version_code`,
									  aav.`download_url`,
									  aav.`product_size`,
									  aav.`vs_note` 
									FROM
									  `skyg_appstore`.`appstore_app_item` AS aai ,
									  `skyg_appstore`.`appstore_app_type_map` AS aatm ,
									  `skyg_appstore`.`appstore_app_version` AS aav
									WHERE aai.`product_id` = aatm.`product_id`
				                      AND aai.`product_id` = aav.`product_id`
									  AND aai.`version_id` = aav.`version_id`  
									  AND aatm.`platform_info` = :platforminfo
									  AND aai.`product_name` LIKE '%".$appname."' 
									  AND aai.`product_is_available` = 1 
									  LIMIT :star,:pagesize ",
				                array("platforminfo"=>$platformInfo,
				                	  "star"=>(int)$startpos,
				                	  "pagesize"=>(int)$len
				                		))->toList();
	
	}
	
	/**
	 *
	 * @param string $platformInfo    platform值
	 * @param string $appname         应用名称
	 * @return multitype:
	 */
	public static function searchAppCount($platformInfo,$appname){
		return parent::createSQL("SELECT count(1)
									FROM
									  `skyg_appstore`.`appstore_app_item` AS aai ,
									  `skyg_appstore`.`appstore_app_type_map` AS aatm ,
									  `skyg_appstore`.`appstore_app_version` AS aav
									WHERE aai.`product_id` = aatm.`product_id`
				                      AND aai.`product_id` = aav.`product_id`
									  AND aai.`version_id` = aav.`version_id`
									  AND aatm.`platform_info` = :platforminfo
									  AND aai.`product_name` LIKE '%".$appname."'
									  AND aai.`product_is_available` = 1 ",
				array("platforminfo"=>$platformInfo
				))->toValue();
	
	}
	
	
	
	
	
}