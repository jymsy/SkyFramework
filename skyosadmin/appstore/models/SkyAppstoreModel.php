<?php

namespace appstore\models;

use Sky\db\DBCommand;
use skyosadmin\components\PublicModel;

/**table 
 */
class SkyAppstoreModel extends \Sky\db\ActiveRecord{
	/**
	 *@return SkyAppStoreModel
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}	
	
	//protected static $tableName="skyg_res.res_advert";
	//protected static $primeKey=array("ad_id");
	
	
	/**
	 * 
	 * @param Int $page
	 * @param Int $pagesize
	 * @param array $orderCondition  默认array("product_add_time"=>"DESC")，即为上载时间的降序
	 * @return multitype:
	 */
	public static function getAppsDetailAll($page,$pagesize,$orderCondition=array("product_add_time"=>"DESC")){
		$orderString=PublicModel::controlArray($orderCondition);
		$sql=sprintf(
				"SELECT 
				  sa.`product_id`,
				  sa.`product_is_available`,
				  sa.`rep_desc`,
				  sa.`product_name`,
				  sa.`product_score`,
				  sa.`product_owner_name`,
				  sa.`product_bag_name`,
				  sav.`download_url`,
				  sav.`product_small_show`,
				  sav.`product_version`,
				  sav.`product_size`,
				  sav.`product_add_time`,CASE
				    sa.`controller_type` 
				    WHEN 1 
				    THEN '遥控器' 
				    WHEN 2 
				    THEN '游戏操作手柄'
				    else '' 
				  END AS control_type,
				  '' as platform_info,
				  '' as product_type_id
				FROM
				  skyg_appstore.appstore_app_item AS sa 
				  LEFT JOIN skyg_appstore.appstore_app_version AS sav 
				    ON sa.version_id = sav.version_id 
				ORDER BY %s LIMIT %d,%d",$orderString,$page,$pagesize);
		//echo($sql);
		$result=parent::createSQL($sql)->toList();
		return $result;		
	}
	
	/**
	 * 
	 * @param String $platformInfo
	 * @param Int $page
	 * @param Int $pagesize
	 * @param array $orderCondition  默认array("product_add_time"=>"DESC")，即为上载时间的降序
	 * @return multitype:
	 */
	public static function getAppsDetailByPlatform($platformInfo,$page,$pagesize,$orderCondition=array("product_add_time"=>"DESC")){
		$orderString=PublicModel::controlArray($orderCondition);
		$sql=sprintf("SELECT
				  sa.`product_id`,
				  sa.`product_is_available`,
				  sa.`rep_desc`,
				  sa.`product_name`,
				  sa.`product_score`,
				  sa.`product_owner_name`,				
				  sa.`product_bag_name`,
				  sav.`download_url`,
				  sav.`product_small_show`,
				  sav.`product_version`,
				  sav.`product_size`,
				  sav.`product_add_time`,				
				  CASE
				    sa.`controller_type` 
				    WHEN 1 
				    THEN '遥控器' 
				    WHEN 2 
				    THEN '游戏操作手柄'
				    else '' 
				  END AS control_type 	
				FROM
				  `skyg_appstore`.`appstore_app_item` AS sa
				  LEFT JOIN `skyg_appstore`.`appstore_app_type_map` AS sca
				    ON sa.`product_id` = sca.`product_id`
				  LEFT JOIN `skyg_appstore`.`appstore_app_version` AS sav
				    ON sa.version_id = sav.version_id
				WHERE sca.`platform_info` = '%s'
				GROUP BY sa.`product_id`
				ORDER BY %s 
				LIMIT  %d,%d",$platformInfo,$orderString,$page,$pagesize);
		
		$result=parent::createSQL($sql)->toList();
		return $result;		
	}
	
	/**
	 * 
	 * @param String $platformInfo
	 * @param Int $productTypeId
	 * @param Int $page
	 * @param Int $pagesize
	 * @param array $orderCondition  默认array("product_add_time"=>"DESC")，即为上载时间的降序
	 * @return multitype:
	 */
	public static function getAppsDetailByType($productTypeId,$page,$pagesize,$orderCondition=array("product_add_time"=>"DESC")){
		$orderString=PublicModel::controlArray($orderCondition);
		$sql=sprintf(
				"SELECT 
				  sa.`product_id`,
				  sa.`product_is_available`,
				  sa.`rep_desc`,
				  sa.`product_name`,
				  sa.`product_score`,
				  sa.`product_owner_name`,				  
				  sa.`product_bag_name`,
				  sav.`download_url`,
				  sav.`product_small_show`,
				  sav.`product_version`,
				  sav.`product_size`,
				  sav.`product_add_time`,				
				  CASE
				    sa.`controller_type` 
				    WHEN 1 
				    THEN '遥控器' 
				    WHEN 2 
				    THEN '游戏操作手柄'
				    else '' 
				  END AS control_type 	
				FROM
				  `skyg_appstore`.`appstore_app_item` AS sa 
				  LEFT JOIN `skyg_appstore`.`appstore_app_type_map` AS sca 
				    ON sa.`product_id` = sca.`product_id` 
				  LEFT JOIN `skyg_appstore`.`appstore_app_version` AS sav 
				    ON sa.version_id = sav.version_id 
				WHERE  sca.`product_type_id` = %d
				GROUP BY sa.`product_id`
				ORDER BY %s 
				LIMIT %d,%d",$productTypeId,$orderString,$page,$pagesize);
		
		$result=parent::createSQL($sql)->toList();
		return $result;
	}
	
	
	
	public static function getAppNumber(){
		$result=parent::createSQL(
				"select count(*) from `skyg_appstore`.`appstore_app_item`"
		)->toValue();
		return $result;
	}
	
	/**
	 * 
	 * @param String $platformInfo
	 * @return Ambigous <NULL, unknown>
	 */
	public static function getAppNumberByPlatform($platformInfo){
		$result=parent::createSQL(
				"SELECT
				  COUNT(DISTINCT sa.`product_id`)
				FROM
				  `skyg_appstore`.`appstore_app_type_map` AS sca,
				  `skyg_appstore`.`appstore_app_item` AS sa
				WHERE sca.`product_id` = sa.`product_id`
				  AND sca.`platform_info` = :platformInfo ",
				array(
						'platformInfo'=>$platformInfo
				)
	
		)->toValue();
		return $result;
	}
	
	/**
	 * 
	 * @param String $platformInfo
	 * @param Int $productTypeId
	 * @return Ambigous <NULL, unknown>
	 */
	public static function getAppNumberByType($productTypeId){
		$result=parent::createSQL(
				"SELECT
				  COUNT(DISTINCT product_id)
				FROM
				  `skyg_appstore`.`appstore_app_type_map`
				WHERE `product_type_id` = :productTypeId",
				array(
						'productTypeId'=>$productTypeId
				)
	
		)->toValue();
		return $result;
	}
	
	/**app下架,仅修改res_sky_app.product_is_available的值
	 * @param Int $productId
	* @return number
	*/
	public static function appOffSale($productId){
		$result=parent::createSQL(
				"UPDATE
				  `skyg_appstore`.`appstore_app_item`
				SET
				  `product_is_available` = 0
				WHERE `product_id` =:productId",
				array(
						'productId'=>$productId
				)
	
		)->exec();
		
		///for old db
		parent::createSQL(
				"UPDATE 
				  `sky_appstore`.`sky_app` 
				SET
				  `Product_IsAvailable`= 0 
				WHERE `Product_ID` =:productId",
				array(
						'productId'=>$productId
				)		
		)->exec();		
		///
		return $result;
	}
	
	/**获取指定id应用的平台和对应分类
	 * @param int $productId
	* @return multitype:
	*/
	public static function getAppPlatformAndType($productId){
		$result=parent::createSQL(
				"SELECT
				  sca.`platform_info`,
				  sca.`product_type_id`,
				  sc.`product_type_name`
				FROM
				  `skyg_appstore`.`appstore_app_type_map` AS sca
				  LEFT JOIN `skyg_appstore`.`appstore_type` AS sc
				    ON sca.`product_type_id` = sc.`product_type_id`
				WHERE sca.`product_id` = :productId",
				array(
						'productId'=>$productId
				)
	
		)->toList();
		return $result;
	}
	
	/**获取指定id应用的平台信息
	 * @param int $productId
	* @return multitype:
	*/
	public static function getAppPlatformAndTypeID($productId){
		$result=parent::createSQL(
				"SELECT 
				  `platform_info`,
				  `product_type_id` 
				FROM
				  `skyg_appstore`.`appstore_app_type_map`
				WHERE `product_id` = :productId",
				array(
						'productId'=>(int)$productId
				)
	
		)->toList();
		return $result;
	}
	
	/**减少category应用数量，下架
	 * @param String $platform
	* @param Int $productTypeId
	* @return result>0 sucess,result=0 fail
	*/
	/*public static function descCotegoryAppCount($productTypeId){
		$result=parent::createSQL(
				"UPDATE
				  `skyg_appstore`.`appstore_type`
				SET
				  `product_coocaa_count` = `product_coocaa_count` - 1
				WHERE `product_type_id` =:productTypeId",
				array(
						'productTypeId'=>$productTypeId
				)
	
		)->exec();
		
		///for old db
		parent::createSQL(
				"UPDATE 
				  `sky_appstore`.`sky_category`
				SET
				   `ProductCoocaaCount`=`ProductCoocaaCount` - 1 
				WHERE `ProductTypeID` =:productTypeId",
				array(
						'productTypeId'=>$productTypeId
				)
		
		)->exec();
		///
		return $result;
	}*/
	
	/**app上架,仅修改res_sky_app.product_is_available的值
	 * @param Int $productId
	* @return number
	*/
	public static function appOnSale($productId){
		$result=parent::createSQL(
				"UPDATE
				  `skyg_appstore`.`appstore_app_item`
				SET
				  `product_is_available` = 1
				WHERE `product_id` =:productId",
				array(
						'productId'=>$productId
				)
	
		)->exec();
		
		///for old db
		parent::createSQL(
				"UPDATE 
				  `sky_appstore`.`sky_app`
				SET
				   `Product_IsAvailable`= 1 
				WHERE `Product_ID` =:productId",
				array(
						'productId'=>$productId
				)
		
		)->exec();
		
		///
		
		
		return $result;
	}
	
	/**增加category应用数量
	 * @param String $platform
	* @param Int $productTypeId
	* @return Ambigous <number, multitype:unknown >
	*/
	/*public static function incCotegoryAppCount($productTypeId){
		$result=parent::createSQL(
				"UPDATE
				  `skyg_appstore`.`appstore_type`
				SET
				  `product_coocaa_count` = `product_coocaa_count` + 1
				WHERE `product_type_id` =:productTypeId",
				array(
						'productTypeId'=>$productTypeId
				)
	
		)->exec();
		
		///for old db
		parent::createSQL(
				"UPDATE 
				  `sky_appstore`.`sky_category`
				SET
				   `ProductCoocaaCount`=`ProductCoocaaCount` + 1 
				WHERE `ProductTypeID`=:productTypeId",
				array(
						'productTypeId'=>$productTypeId
				)
		
		)->exec();		
		///		
		
		return $result;
	}*/
	
	/**
	 * 搜索
	 * @param array $searchCondition  e.g. array('product_name'=>'GOOGLE','product_owner_name'=>'RSR')
	 * @param Int $pageSize
	 * @param Int $pageIndex
	 * @return multitype:
	 */
	public static function searchAppBack($searchCondition,$start,$limit,$orderCondition=array("product_add_time"=>"DESC")){
		$orderString=PublicModel::controlArray($orderCondition);
		$searchString='';
		$searchString=PublicModel::controlsearch($searchCondition);
		if($searchString!='')
			$searchString='WHERE  '.$searchString;
		$sql=sprintf(
				"SELECT
				  sa.`product_id`,
				  sa.`product_is_available`,
				  sa.`rep_desc`,
				  sa.`product_name`,
				  sa.`product_score`,
				  sa.`product_owner_name`,
				  sa.`product_bag_name`,
				  sav.`download_url`,
				  sav.`product_small_show`,
				  sav.`product_version`,
				  sav.`product_size`,
				  sav.`product_add_time`,				
				  CASE
				    sa.`controller_type` 
				    WHEN 1 
				    THEN '遥控器' 
				    WHEN 2 
				    THEN '游戏操作手柄'
				    else '' 
				  END AS control_type ,
				  '' as platform_info,
				  '' as product_type_id	
				FROM
				  skyg_appstore.appstore_app_item AS sa
				  LEFT JOIN skyg_appstore.`appstore_app_version` AS sav
				    ON sa.version_id = sav.version_id
				%s
				ORDER BY %s 
				LIMIT %d,%d",$searchString,$orderString,$start,$limit);
		$result=parent::createSQL($sql)->toList();
		return $result;
	}
	
	/**
	 * 搜索出的对象数目
	 * @param array $searchCondition  e.g.array('product_name'=>'GOOGLE','product_owner_name'=>'RSR')
	 * @return Ambigous <NULL, unknown>
	 */
	public static function getSearchNum($searchCondition){
		$searchString='';
		$searchString=PublicModel::controlsearch($searchCondition);
		if($searchString!='')
			$searchString='WHERE  '.$searchString;
		$sql=sprintf(
				"SELECT COUNT(*) FROM `skyg_appstore`.`appstore_app_item` %s ",
				$searchString);
		$result=parent::createSQL($sql)->toValue();
		return $result;
	}
	
	
	
	
	/**检测指定包名的app是否存在
	 * @param string $Product_BagName
	* @return Ambigous <NULL, unknown>
	*/
	public static function checkAppExistByBagName($ProductBagName){
		$sql=sprintf("select `product_id` from `skyg_appstore`.`appstore_app_item` where `product_bag_name`='%s'",addslashes($ProductBagName));
		return $result=parent::createSQL($sql)->toValue();
	}
	
	/**获取app的现有版本
	 * @param int $Product_ID
	* @return number|Ambigous <NULL, unknown>
	*/
	public static function getAppLateVersion($Product_ID){
		$result=parent::createSQL(
				"SELECT 
				  `product_version_code` 
				FROM
				  `skyg_appstore`.`appstore_app_version` AS sav 
				  LEFT JOIN `skyg_appstore`.`appstore_app_item` AS sa 
				    ON sa.`version_id` = sav.`version_id` 
				WHERE sa.`product_id` = :Product_ID ",
				array(
						'Product_ID'=>$Product_ID)
		
		)->toValue();
		return $result;
	}
	
	/**添加新版app
	 * 
	 * @param unknown_type $product_id
	 * @param unknown_type $product_big_show
	 * @param unknown_type $product_small_show
	 * @param unknown_type $product_version
	 * @param unknown_type $product_version_code
	 * @param unknown_type $product_size
	 * @param unknown_type $product_language
	 * @param unknown_type $download_url
	 * @param unknown_type $developer
	 * @param unknown_type $vs_note
	 * @return unknown
	 */
	public static function addAppVersion($arr){
		extract($arr);
		$result=parent::createSQL(
				"INSERT INTO `skyg_appstore`.`appstore_app_version` (
				  `product_id`,
				  `product_big_show`,
				  `product_small_show`,
				  `product_version`,
				  `product_version_code`,
				  `product_size`,
				  `product_language`,
				  `download_url`,
				  `developer`,
				  `vs_note`
				) 
				VALUES
				  (
				    	:product_id,
				  		:product_big_show,
				  		:product_small_show,
				  		:product_version,
				  		:product_version_code,
				  		:product_size,
				  		:product_language,
				  		:download_url,
				  		:developer,
				  		:vs_note
				  )",
				  array(
				  		'product_id'=>$product_id,
				  		'product_big_show'=>$product_big_show,
				  		'product_small_show'=>$product_small_show,
				  		'product_version'=>$product_version,
				  		'product_version_code'=>$product_version_code,
				  		'product_size'=>$product_size,
				  		'product_language'=>$product_language,
				  		'download_url'=>$download_url,
				  		'developer'=>$developer,
				  		'vs_note'=>$vs_note
				  		)
		 );
		if($result->exec()!=0){
			$result->getPdoInstance();
			$result=$result->lastInsertID();
			
			///for old db
			parent::createSQL(
					"INSERT INTO `sky_appstore`.`sky_app_version` (
					  `v_id`,
					  `Product_ID`,
					  `Product_Big_Show`,
					  `Product_Small_Show`,
					  `Product_Version`,
					  `Product_VersionCode`,
					  `Product_Size`,
					  `Product_language`,
					  `DownloadUrl`,
					  `developer`,
					  `vs_note`
					) 
					VALUES
					  (
					    :v_id,
					    :product_id,
					    :product_big_show,
					    :product_small_show,
					    :product_version,
					    :product_version_code,
					    :product_size,
					    :product_language,
					    :download_url,
					    :developer,
					    :vs_note
					  )",
					array(
							'v_id'=>(int)$result,
							'product_id'=>(int)$product_id,
							'product_big_show'=>$product_big_show,
							'product_small_show'=>$product_small_show,
							'product_version'=>$product_version,
							'product_version_code'=>$product_version_code,
							'product_size'=>$product_size,
							'product_language'=>$product_language,
							'download_url'=>$download_url,
							'developer'=>$developer,
							'vs_note'=>$vs_note
					)
			)->exec();
			
			///
			
			return $result;
		}
		return 0;
	}
	
	/**插入app项
	 * $arr
	 * @param unknown_type $ProductOwnerName
	 * @param unknown_type $ProductName
	 * @param unknown_type $ProductBagName
	 * @param unknown_type $ProductSaleCCM
	 * @param unknown_type $ProductInstallationSite
	 * @param unknown_type $ProductScore
	 * @param unknown_type $RepDesc
	 * @param unknown_type $ProductisAvailable
	 * @param unknown_type $ProductSalesNum
	 * @param unknown_type $productDownloadNum
	 * @param unknown_type $controllerType
	 * @return number
	 */

	public static function insertAppItem($arr){
		extract($arr);
		$result=parent::createSQL(
				"INSERT INTO skyg_appstore.`appstore_app_item` (
				  `product_owner_id`,
				  `product_owner_name`,
				  `product_name`,
				  `product_sale_ccm`,
				  `product_bag_name`,
				  `product_installation_site`,
				  `product_score`,				
				  `rep_desc`,
				  `product_is_available`,
				  `product_sales_num`,
				  `product_download_num`,
				  `controller_type`
				) 
				VALUES
				  (:ProductOwnerId,
				   :ProductOwnerName,
				   :ProductName,
				   :ProductSaleCCM,
				   :ProductBagName,
				   :ProductInstallationSite,
				   :ProductScore,
				   :RepDesc,				  
				   :ProductisAvailable,
				   :ProductSalesNum,
				   :productDownloadNum,
				   :controllerType
				)",
				array(
						'ProductOwnerId'=>$ProductOwnerName,
						'ProductOwnerName'=>$ProductOwnerName,
						'ProductName'=>$ProductName,
						'ProductSaleCCM'=>$ProductSaleCCM,
						'ProductBagName'=>$ProductBagName,
						'ProductInstallationSite'=>$ProductInstallationSite,
						'ProductScore'=>$ProductScore,
						'RepDesc'=>$RepDesc,
						'ProductisAvailable'=>$ProductisAvailable,
						'ProductSalesNum'=>$ProductSalesNum,
						'productDownloadNum'=>$productDownloadNum,
						'controllerType'=>$controllerType
						)
			);
		
		if($result->exec()!=0){
			$result->getPdoInstance();
			$result=$result->lastInsertID();
			///for old db
			parent::createSQL(
					"INSERT INTO sky_appstore.`sky_app` (
					  `Product_ID`,
					  `Product_OwnerID`,
					  `Product_OwnerName`,
					  `Product_Name`,
					  `Product_Sale_CCM`,
					  `Product_BagName`,
					  `Product_InstallationSite`,
					  `Product_score`,
					  `REP_DESC`,
					  `Product_IsAvailable`,
					  `Product_SalesNum`,
					  `Product_DownloadNum`,
					  `controller_type`
					)
					VALUES
				  (
				   :Product_ID,
				   :ProductOwnerId,
				   :ProductOwnerName,
				   :ProductName,
				   :ProductSaleCCM,
				   :ProductBagName,
				   :ProductInstallationSite,
				   :ProductScore,
				   :RepDesc,
				   :ProductisAvailable,
				   :ProductSalesNum,
				   :productDownloadNum,
				   :controllerType
				)",
					array(
							'Product_ID'=>$result,
							'ProductOwnerId'=>$ProductOwnerName,
							'ProductOwnerName'=>$ProductOwnerName,
							'ProductName'=>$ProductName,
							'ProductSaleCCM'=>$ProductSaleCCM,
							'ProductBagName'=>$ProductBagName,
							'ProductInstallationSite'=>$ProductInstallationSite,
							'ProductScore'=>$ProductScore,
							'RepDesc'=>$RepDesc,
							'ProductisAvailable'=>$ProductisAvailable,
							'ProductSalesNum'=>$ProductSalesNum,
							'productDownloadNum'=>$productDownloadNum,
							'controllerType'=>$controllerType
					)
			)->exec();
			///
			
			return $result;
		}
		return 0;
	}
	
	/**更新指定appid的vid
	 * @param int $Product_ID
	* @param int $vid
	* @return update result
	*/
	public static function updateAppVid($Product_ID,$vid){
		$result=parent::createSQL("update `skyg_appstore`.`appstore_app_item` 
				set `version_id`=:vid where `product_id`=:Product_ID",
		array(
				'vid'=>(int)$vid,
				'Product_ID'=>(int)$Product_ID
				)
		)->exec();
		
		
		///for old db
		parent::createSQL("UPDATE `sky_appstore`.`sky_app`
				SET `version_id`=:vid WHERE `Product_ID`=:Product_ID",
				array(
						'vid'=>(int)$vid,
						'Product_ID'=>(int)$Product_ID
				)
		)->exec();
		
		///
		return $result;
	}
	
	/**插入categoryApp
	 * @param string $platformInfo
	* @param int $ProductTypeID
	* @param int $Product_ID
	* @return insert result
	*/
	public static function insertCategoryAppItem($platformInfo,$ProductTypeID,$Product_ID){
		$result=parent::createSQL(
				"INSERT INTO `skyg_appstore`.`appstore_app_type_map` (
					  `platform_info`,
					  `product_type_id`,
					  `product_id`
					) 
					VALUES
					  (
					    :platformInfo,
					    :ProductTypeID,
					    :Product_ID
					  )",
				array(
						'platformInfo'=>$platformInfo,
						'ProductTypeID'=>$ProductTypeID,
						'Product_ID'=>$Product_ID
						)
		)->exec();
		
		///for old db
		parent::createSQL(
				"INSERT INTO `sky_appstore`.`sky_category_app` (
					  `platformInfo`,
					  `ProductTypeID`,
					  `Product_ID`
					)
					VALUES
					  (
					    :platformInfo,
					    :ProductTypeID,
					    :Product_ID
					  )",
				array(
						'platformInfo'=>$platformInfo,
						'ProductTypeID'=>$ProductTypeID,
						'Product_ID'=>$Product_ID
				)
		)->exec();
		///
		return $result;
	}
	
	/**更新app
	 * @param unknown_type $ProductId
	 * @param unknown_type $ProductName
	 * @param unknown_type $ProductScore
	 * @param unknown_type $ProductDesc
	 * @param unknown_type $icon
	 * @return number|string
	 */
	public static function updateApp($array){
		extract($array);
		$sql=parent::createSQL("BEGIN")->exec();
		$sql=sprintf(
				"UPDATE 
				  `skyg_appstore`.`appstore_app_item` 
				SET
				  `product_owner_id` = '%s',
				  `product_owner_name` = '%s',
				  `product_name` ='%s',
				  `product_score` = % d,
				  `rep_desc` = '%s',
				  `controller_type` = % d 
				WHERE `product_id` = % d",
				addslashes($product_owner_id),
				addslashes($product_owner_name),
				addslashes($product_name),
				$product_score,
				addslashes($rep_desc),
				$controller_type,
				$product_id);
		$result=parent::createSQL($sql)->exec();

					
		$sql=sprintf(
				"UPDATE 
				  `skyg_appstore`.`appstore_app_version` 
				SET
				  `product_big_show` = '%s',
				  `product_small_show` = '%s',
				  `product_version` = '%s',
				  `product_version_code` = '%s',
				  `product_size` = '%s',
				  `product_language` = '%s',
				  `developer` = '%s',
				  `vs_minsdkversion` = '%s',
				  `vs_note` = '%s' 
				WHERE `product_id` = %d",
				addslashes($product_big_show),
				addslashes($product_small_show),
				addslashes($product_version),
				addslashes($product_version_code),
				addslashes($product_size),
				addslashes($product_language),
				addslashes($developer),
				addslashes($vs_minsdkversion),
				addslashes($vs_note),
				$product_id);
		$result=parent::createSQL($sql)->exec();
		
		///for old db
		$sql=sprintf(
				"UPDATE 
				  `sky_appstore`.`sky_app`
				SET
				  `Product_OwnerID` = '%s',
				  `Product_OwnerName` = '%s',
				  `Product_Name` = '%s',
				  `Product_score`= %d,
				  `REP_DESC` = '%s',
				  `controller_type` = %d 
				WHERE `Product_ID` = %d ",
				addslashes($product_owner_id),
				addslashes($product_owner_name),
				addslashes($product_name),
				$product_score,
				addslashes($rep_desc),
				$controller_type,
				$product_id);
		parent::createSQL($sql)->exec();
		
			
		$sql=sprintf(
				"UPDATE 
				  `sky_appstore`.`sky_app_version` 
				SET
				  `Product_Big_Show`= '%s',
				  `Product_Small_Show` = '%s',
				  `Product_Version`= '%s',
				  `Product_VersionCode` = '%s',
				  `Product_Size`= '%s',
				  `Product_language`= '%s',
				  `developer` = '%s',
				  `vs_minsdkversion`= '%s',
				  `vs_note` = '%s' 
				WHERE `Product_ID` = %d ",
				addslashes($product_big_show),
				addslashes($product_small_show),
				addslashes($product_version),
				addslashes($product_version_code),
				addslashes($product_size),
				addslashes($product_language),
				addslashes($developer),
				addslashes($vs_minsdkversion),
				addslashes($vs_note),
				$product_id);
		parent::createSQL($sql)->exec();
		///
				
		
		//删除该应用对应的所有分类
		$oldPlattypes=self::getAppPlatformAndTypeID($product_id);
		if(is_array($oldPlattypes) && count($oldPlattypes)){
			foreach($oldPlattypes as $oldPlattype){				
				$result=self::deleteCategoryApp($oldPlattype['platform_info'], $oldPlattype['product_type_id'], $product_id);
				if($result==0){
					$sql=parent::createSQL("RollBack")->exec();
					return 0;
				}
				/*else {
					if(self::checkAppOnSale($product_id))
						$result=self::descCotegoryAppCount($oldPlattype['product_type_id']);
					if($result==0){
						$sql=parent::createSQL("RollBack")->exec();
						return 0;
					}
				}*/
			}
		}		
		//添加分类信息
		foreach ($platform_types as $platformtype){						
			$result=self::insertCategoryAppItem($platformtype,$type_id,$product_id); 
			if($result==0){
					$sql=parent::createSQL("RollBack")->exec();
					return 0;
				}
		}					
		/*$result=self::incCotegoryAppCount($type_id);
		if($result==0){
			$sql=parent::createSQL("RollBack")->exec();
			return 0;
		}
		*/
		$sql=parent::createSQL("Commit")->exec();
		$sql=parent::createSQL("set autocommit=1")->exec();
		return 1;
	}
	
	
	/**将应用从分类中删除
	 * @param unknown_type $platform
	 * @param unknown_type $ProductTypeID
	 * @param unknown_type $ProductId
	 * @return number
	 */
	public static function deleteCategoryApp($platform,$ProductTypeID,$ProductId){		
		$result=parent::createSQL("DELETE FROM `skyg_appstore`.`appstore_app_type_map` WHERE `platform_info`=:platform_info
				 AND `product_type_id`=:product_type_id AND `product_id`=:product_id",
				array(
						'platform_info'=>$platform,
						'product_type_id'=>$ProductTypeID,
						'product_id'=>(int)$ProductId						
						)
		)->exec();
		
		///for old db
		parent::createSQL("DELETE FROM `sky_appstore`.`sky_category_app` WHERE `platformInfo`=:platform_info
				 AND `ProductTypeID`=:product_type_id AND `Product_ID`=:product_id",
				array(
						'platform_info'=>$platform,
						'product_type_id'=>$ProductTypeID,
						'product_id'=>(int)$ProductId
				)
		)->exec();
		///
		
		return $result;
	}
	
	/**检查应用状态是否有效
	 * @param unknown_type $Product_ID
	 * @return multitype:
	 */
	public static function checkAppOnSale($Product_ID){
		$sql=sprintf("select `product_is_available` from `skyg_appstore`.`appstore_app_item` where `product_id`=%d",$Product_ID);
		$result=parent::createSQL($sql)->toValue();
		return $result;
	}
	
	/**删除某应用
	 * @param unknown_type $Product_ID
	 * @return boolean 
	 */
	public static function deleteApp($Product_ID){
		$plattypes=self::getAppPlatformAndTypeID($Product_ID);
		if(is_array($plattypes) && count($plattypes)){
			foreach($plattypes as $plattype){				
				$result=self::deleteCategoryApp($plattype['platform_info'], $plattype['product_type_id'], $Product_ID);
				/*if($result){
					if(self::checkAppOnSale($Product_ID))
						self::descCotegoryAppCount($plattype['product_type_id']);
				}*/
			}
		}
		$sql=sprintf("delete from `skyg_appstore`.`appstore_app_item` where `product_id`=%d",$Product_ID);
		$result1=parent::createSQL($sql)->exec();
		$sql=sprintf("delete from `skyg_appstore`.`appstore_app_version` where `product_id`=%d",$Product_ID);
		$result2=parent::createSQL($sql)->exec();
		$result=(($result1>0)||($result2>0));
		///for old db
		$sql=sprintf("DELETE `sky_appstore`.`sky_app_version`,`sky_appstore`.`sky_app` FROM `sky_appstore`.`sky_app_version` ,`sky_appstore`.`sky_app`  
				WHERE `sky_appstore`.`sky_app_version`.`Product_ID`=%d AND `sky_appstore`.`sky_app`.`Product_ID`=%d",$Product_ID,$Product_ID);
		parent::createSQL($sql)->exec();
		///
		
		return $result;
		
		
	}
	/**删除app项
	 * @param unknown_type $Product_ID
	* @return number
	*/
	public static function deleteAppItem($Product_ID){
			$sql=sprintf("delete from `skyg_appstore`.`appstore_app_item` where `product_id`=%d",$Product_ID);
			$result=parent::createSQL($sql)->exec();	

			///for old db
			$sql=sprintf("DELETE FROM `sky_appstore`.`sky_app` WHERE `Product_ID`=%d",$Product_ID);
			parent::createSQL($sql)->exec();
			///
			
			return $result;		
	}
	
	/**删除app
	 * @param unknown_type $Product_ID
	* @return number
	*/
	public static function deleteAppVersion($Product_ID){
		$sql=sprintf("delete from `skyg_appstore`.`appstore_app_version` where `product_id`=%d",$Product_ID);
		$result=parent::createSQL($sql)->exec();
		
		///for old db
		$sql=sprintf("DELETE FROM `sky_appstore`.`sky_app_version` WHERE `Product_ID`=%d",$Product_ID);		
		parent::createSQL($sql)->exec();
		///
		
		return $result;
	}
	
	
	
	/**获取app所有的信息，包括分类
	 * 
	 */
	public static function getAppDetailById($Product_ID){
		$sql=sprintf("SELECT
					  sa.`product_owner_id`,
					  sa.`product_owner_name`,
					  sa.`product_name`,
					  sa.`product_bag_name`,
					  sa.`product_score`,
					  sa.`product_is_available`,
					  sa.`product_sales_num`,
					  sa.`product_download_num`,				
				      sa.`product_bag_name`,
					  sa.`rep_desc`,
					  CASE
					    sa.`controller_type` 
					    WHEN 1 
					    THEN '遥控器' 
					    WHEN 2 
					    THEN '游戏操作手柄'
					    else '' 
					  END AS control_type,
					  sav.`product_big_show`,
					  sav.`product_small_show`,
					  sav.`product_version`,
					  sav.`product_version_code`,
					  sav.`product_size`,
					  sav.`product_add_time`,
					  sav.`product_language`,
					  sav.`download_url`,
					  sav.`developer`,
					  sav.`vs_minsdkversion`,
					  sav.`vs_note`
					FROM
					  skyg_appstore.appstore_app_item AS sa
					  LEFT JOIN skyg_appstore.appstore_app_version AS sav
					    ON sa.version_id = sav.version_id
					WHERE sa.product_id= %d ",$Product_ID);
		$result1=parent::createSQL($sql)->toList();
		$result2=self::getAppPlatformAndType($Product_ID);
		$result=array_merge($result1, $result2);
		return $result;
	}
	
	/**将应用从分类中删除
	 * @param unknown_type $platform
	* @param unknown_type $ProductTypeID
	* @param unknown_type $ProductId
	* @return number
	*/
	public static function deleteCatAppByTypeAndId($ProductId){
		$result=parent::createSQL("DELETE FROM `skyg_appstore`.`appstore_app_type_map` 
				WHERE  `product_id`=:product_id",
				array(
						'product_id'=>(int)$ProductId
				)
		)->exec();
		
		///for old db
		parent::createSQL("DELETE FROM sky_appstore.`sky_category_app` 
				WHERE `Product_ID`=:product_id",
				array(
						'product_id'=>(int)$ProductId
				)
		)->exec();
		///
		return $result;
	}
	
	/**版本对比
	 *
	 * @param array $remoteAppArr
	 * @return 
	 */
	public static function compareApp($remoteAppArr){
		extract($remoteAppArr);
		$product_id=self::checkAppExistByBagName($product_bag_name);
		if($product_id==0)
			return 0;
		$result=parent::createSQL(
				"SELECT
				  count(*)
				FROM
				  `skyg_appstore`.`appstore_app_version`
				WHERE `product_id` = :Product_ID AND `product_version_code`=:version_code" ,
				array(
						'Product_ID'=>(int)$product_id,
						'version_code'=>(int)$product_version_code)
	
		)->toValue();
		return $result;
	}
	
	///////////////////平台管理///////////////////////
	
	/**添加平台信息
	 * 
	 * @param string $platform_info
	 * @return unknown|number
	 */
	
	/*
	 * public static function insertPlatform($platform_info){
		$sql=sprintf("INSERT INTO skyg_appstore.`appstore_platform_info` (`platform_info`) 
					VALUES
					  ('%s')",$platform_info);
		$result=parent::createSQL($sql);
		if($result->exec()!=0){
			$result->getPdoInstance();
			$result=$result->lastInsertID();
			
			///for old db
			$sql=sprintf("INSERT INTO sky_appstore.`sky_category` (
						  `ProductTypeID`,
						  `ProductTypeName`,
						  `platform`
						) 
						SELECT 
						  `product_type_id`,
						  `product_type_name`,
						  '%s'  
						FROM
						  `skyg_appstore`.`appstore_type` ",
				$platform_info);
			parent::createSQL($sql)->exec();
			///
			
			
			return $result;
		}
		return 0;
	}
	*/
	/**更新平台信息
	 *
	* @param string $platform_info
	* @return number
	*/
	/*
	 * 
	 public static function updatePlatform($arr){
		extract($arr);
		///for old db
		$sql=sprintf("UPDATE
						  sky_appstore.`sky_category` a,
						  skyg_appstore.`appstore_platform_info` b 
						SET
						  a.`platform`='%s'  
						WHERE b.`platform_id` = %d 
						  AND a.`platform` = b.`platform_info`",$platform_info,$platform_id);
		parent::createSQL($sql)->exec();
		///
		
		$sql=sprintf("UPDATE skyg_appstore.`appstore_platform_info` api,skyg_appstore.`appstore_app_type_map` aatm
		SET aatm.`platform_info`='%s' WHERE
		api.`platform_info`=aatm.`platform_info` AND api.`platform_id`=%d ",$platform_info,$platform_id);
		$result_map=parent::createSQL($sql)->exec();
		
		$sql=sprintf("UPDATE skyg_appstore.`appstore_platform_info`
				SET `platform_info`='%s' WHERE `platform_id`=%d ",$platform_info,$platform_id);
		$result=parent::createSQL($sql)->exec();
	    if($result_map==0&&$result==0)
	    	return 0;
	    else		
			return 1;
	}
	*/
	
	/**删除平台信息
	 * 
	 * @param string $platform_info
	 * @return number
	 */
	/*
	 * 
	 public static function deletePlatform($platform_id){
		///for old db
		$sql=sprintf("DELETE 
		  sky_appstore.`sky_category` 
		FROM
		  sky_appstore.`sky_category`,
		  skyg_appstore.`appstore_platform_info`
		WHERE skyg_appstore.`appstore_platform_info`.`platform_id` = %d
		  AND sky_appstore.`sky_category`.`platform` = skyg_appstore.`appstore_platform_info`.`platform_info`  ",$platform_id);
		parent::createSQL($sql)->exec();
		
		$sql=sprintf("DELETE
		  sky_appstore.`sky_category_app`
		FROM
		  sky_appstore.`sky_category_app`,
		  skyg_appstore.`appstore_platform_info`
		WHERE skyg_appstore.`appstore_platform_info`.`platform_id` = %d
		  AND sky_appstore.`sky_category_app`.`platformInfo` = skyg_appstore.`appstore_platform_info`.`platform_info`  ",$platform_id);
		parent::createSQL($sql)->exec();
		
		///
		$sql=sprintf("DELETE
					   skyg_appstore.`appstore_platform_info`,
					   skyg_appstore.`appstore_app_type_map`
					FROM
					  skyg_appstore.`appstore_platform_info` ,
					  skyg_appstore.`appstore_app_type_map`
					WHERE skyg_appstore.`appstore_platform_info`.`platform_id` =%d
					  AND skyg_appstore.`appstore_platform_info`.`platform_info` = skyg_appstore.`appstore_app_type_map`.`platform_info`",$platform_id);
		$result=parent::createSQL($sql)->exec();
		
		$sql=sprintf("DELETE
					FROM
					  skyg_appstore.`appstore_platform_info` 
					WHERE skyg_appstore.`appstore_platform_info`.`platform_id` =%d",
				$platform_id);
		$result=parent::createSQL($sql)->exec();	
		
		return $result;
	}
	*/
	/**查询所有平台名称
	 *
	* @return multitype:
	*/	
	public static function getAllPlatform($start=0,$limit=0,$orderCondition=array("policy_value"=>"DESC")){
		$orderString=PublicModel::controlArray($orderCondition);	
		if(($start!=0)||($limit!=0))
			$limitString=sprintf("limit %d,%d",$start,$limit);
		else
			$limitString='';
		
		$sql="SELECT 
				  distinct(policy_value)
				FROM
				  skyg_base.`base_policy_conf` 
				WHERE function_name= 'appstore'
				AND flag=0 ";
		$sql.=sprintf("ORDER BY %s 
				%s "
				,$orderString,$limitString);
		$result=parent::createSQL($sql)->toList();
		return $result;
	}
	
	
	
	//平台统计
	public static function getAllPlatformCount(){	
		$sql="SELECT 
				  count(distinct(policy_value)) 
				FROM
				  skyg_base.`base_policy_conf` 
				WHERE function_name ='appstore'
				AND flag=0";
		$result=parent::createSQL($sql)->toValue();
		
		return $result;
	}
	
	//平台搜索统计
	public static function searchPlatformCount($searchCondition){
		$searchString=PublicModel::controlsearch($searchCondition);
		if($searchString!='')
			$searchString=' AND  '.$searchString;
	
		$sql="SELECT 
				  count(*) 
				FROM
				  skyg_base.`base_policy_conf` 
				
				WHERE function_name = 'appstore'".sprintf(
				" %s",$searchString);
		$result=parent::createSQL($sql)->toValue();
		
		return $result;
	}
	
	//平台搜索
	public static function searchPlatformDetail($searchCondition,$start,$limit,$orderCondition=array("policy_value"=>"DESC")){
		$orderString=PublicModel::controlArray($orderCondition);		
		$searchString=PublicModel::controlsearch($searchCondition);
		if($searchString!='')
			$searchString=' AND  '.$searchString;
		$sql="SELECT 
				  chip,
				  model,
				  policy_value 
				FROM
				  skyg_base.`base_policy_conf` 
				WHERE function_name = 'appstore'";
		$sql.=sprintf(
				" %s
				ORDER BY %s
				limit %d,%d ",$searchString,$orderString,$start,$limit);
		$result=parent::createSQL($sql)->toList();
		
		return $result;
	}
	
	
	////////////////////////////////分类管理//////////////////////////////
	/**添加分类信息
	 * 
	 * @param string $product_type_name
	 * @param string $product_type_img_url
	 * @return unknown|number
	 */
	public static function insertType($arr){
		extract($arr);
		$sql=sprintf("SELECT `product_type_id` FROM  skyg_appstore.`appstore_type` where `product_type_name`='%s' ",$product_type_name);
		$result=parent::createSQL($sql)->toValue();
		if($result>0)
			return $result;
			
		$sql=sprintf("INSERT INTO skyg_appstore.`appstore_type` (
					  `product_type_name` 
					) 
					VALUES
					  ('%s') ",$product_type_name);
		$result=parent::createSQL($sql);
		if($result->exec()!=0){
			$result->getPdoInstance();
			$result=$result->lastInsertID();
			
			///for old db
			$sql=sprintf("INSERT INTO sky_appstore.`sky_category` (
					  `ProductTypeID`,
					  `ProductTypeName`,
					  `platform`
					)
					SELECT
					  '%d',
					  '%s',
					  `policy_value`
					FROM
					  `skyg_base`.`base_policy_conf` ",
					$result,$product_type_name);
			$sql.="	WHERE function_name LIKE 'appstore%'";
			parent::createSQL($sql)->exec();
			///
			
			
			return $result;
		}
		
		
		return 0;
	}
	
	/**更新分类信息
	 * 
	 * @param string $product_type_name
	 * @param int $product_type_id
	 * @return number
	 */
	public static function updateType($arr){
		extract($arr);
		$sql=sprintf("UPDATE skyg_appstore.`appstore_type`
					  	SET
				      `product_type_name`='%s' 
					 WHERE
				       `product_type_id`=%d",
				$product_type_name,
				$product_type_id);
		$result=parent::createSQL($sql)->exec();
		
		///for old db
		$sql=sprintf("UPDATE sky_appstore.`sky_category`
					  	SET
				      `ProductTypeName`='%s' 
					 WHERE
				       `ProductTypeID`=%d",
				$product_type_name,$product_type_id);
		parent::createSQL($sql)->exec();
		///
		
		
		return $result;
	}
	
	/**删除分类信息
	 * 
	 * @param int $product_type_id
	 * @return number
	 */
	public static function deleteType($product_type_id){
		$sql=sprintf("DELETE
			FROM			  
			  `skyg_appstore`.`appstore_app_type_map`
			WHERE skyg_appstore.appstore_app_type_map.`product_type_id` =%d",
				$product_type_id);
		parent::createSQL($sql)->exec();
		
		$sql=sprintf("DELETE
			FROM
			  skyg_appstore.`appstore_type` 		  
			WHERE `product_type_id` =%d",
				$product_type_id);
		$result=parent::createSQL($sql)->exec();		
			    
		///for old db
		$sql=sprintf("DELETE
					FROM
					  `sky_appstore`.`sky_category`				  
					WHERE `sky_appstore`.`sky_category`.`ProductTypeID` = %d",
				$product_type_id);
		parent::createSQL($sql)->exec();
		$sql=sprintf("DELETE
					FROM					  
					  `sky_appstore`.`sky_category_app`
					WHERE `sky_appstore`.`sky_category_app`.`ProductTypeID` = %d ",
				$product_type_id);
		parent::createSQL($sql)->exec();		
		///
		
		return $result;
	}
	
	
	
	/**查询所有分类名称
	 *
	* @return number
	*/
	public static function getAllType($start=0,$limit=0,$orderCondition=array("product_type_name"=>"DESC")){
		$orderString=PublicModel::controlArray($orderCondition);		
		if(($start!=0)||($limit!=0))
			$limitString=sprintf("limit %d,%d",$start,$limit);
		else 
			$limitString='';
			
		$sql=sprintf("SELECT DISTINCT 
						  `product_type_id`,
						  `product_type_name` 
						FROM
						  skyg_appstore.`appstore_type`
				          ORDER BY %s
				           %s",$orderString,$limitString);
		$result=parent::createSQL($sql)->toList();
		return $result;
	}
	
	//统计
	public static function getAllTypeCount(){		
		$sql="SELECT DISTINCT
						  count(*)
						FROM
						  skyg_appstore.`appstore_type`";
		$result=parent::createSQL($sql)->toValue();
		
		return $result;
	}
	
	//搜索统计
	public static function searchTypeCount($searchCondition){
		$searchString=PublicModel::controlsearch($searchCondition);
		if($searchString!='')
			$searchString=' WHERE  '.$searchString;
		$sql=sprintf("
				SELECT count(*)
				FROM
				skyg_appstore.`appstore_type`
				%s",$searchString);
		$result=parent::createSQL($sql)->toValue();
		
		return $result;
	}
	
	//搜索
	public static function searchTypeDetail($searchCondition,$start,$limit,$orderCondition=array("product_type_name"=>"DESC")){
		$orderString=PublicModel::controlArray($orderCondition);
		$searchString=PublicModel::controlsearch($searchCondition);
		if($searchString!='')
			$searchString=' WHERE  '.$searchString;
		$sql=sprintf("SELECT 
		 		`product_type_id`,
				`product_type_name`
				FROM
				skyg_appstore.`appstore_type`
				%s
				ORDER BY %s
				limit %d,%d ",$searchString,$orderString,$start,$limit);
		$result=parent::createSQL($sql)->toList();
		
		return $result;
	}
	
	
	
}