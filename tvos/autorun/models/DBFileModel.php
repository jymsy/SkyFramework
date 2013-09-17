<?php
namespace tvos\autorun\models;
/**            
 * 
 * @author zhengyun
 */

class DBFileModel extends \Sky\db\ActiveRecord{
	/**
	 *@return DBFileModel
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}
	
	
	
	//get db file
	/**
	 * 
	 * @param string $currentVersion
	 * @return multitype:
	 */
	public static 	function getDbFile($currentVersion,$res_type) {		
		$sql = "SELECT 
				  `db_file_id`,
				  `res_type`,
				  `file_version`,
				  `download_url`,
				  `is_zip`,
				  `created_time` 
				FROM
				  `skyg_res`.`res_dbfile` 
				WHERE `file_version` > '$currentVersion' 
				AND `res_type`=$res_type 
				ORDER BY `db_file_id` DESC 
				LIMIT 1 ";
		$result = parent::createSQL($sql)->toList();
		return $result;
		
	}
	
	/**添加dbfile新版本数据
	 * 
	 * @param string $version
	 * @param string $downloadUrl
	 * @param int $isZip
	 * @param int $resType  1-first_char;2-web_site
	 * @return multitype:
	 */
	public static function insertDbFile($version,$downloadUrl,$isZip,$resType) {		
		$sql = sprintf('REPLACE INTO `skyg_res`.`res_dbfile` (
						  `file_version`,
						  `download_url`,
						  `is_zip`,
						  `res_type`
						) 
						VALUES
						  ("%1$s","%2$s", %3$d,%4$d)',
				addslashes($version),addslashes($downloadUrl),$isZip,$resType);
		$result = parent::createSQL($sql)->toList();
		return $result;
		
	}
	
	/**获取对应资源类型的最大版本号
	 * 
	 * @param Int $resType
	 * @return string version
	 */
	public static function getDbFileVersion($resType) {
		$sql = sprintf('SELECT max(`file_version`) FROM `skyg_res`.`res_dbfile` where `res_type`=%d',
				$resType);
		$result = parent::createSQL($sql)->toValue();
		return $result;
	}
	
	
}
