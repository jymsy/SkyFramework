<?php
namespace tvos\autorun\models;
/**            
 * 
 * @author zhengyun
 */

class ThesaurusModel extends \Sky\db\ActiveRecord{
	/**
	 *@return ThesaurusModel
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}
	
	/**获取first_char信息列表
	 * 
	 * @param string $key
	 * @param Int $page_index
	 * @param Int $page_size
	 * @return array
	 */
	public static function getThesaurusSearch($key,$page_index,$page_size) {
		$start = $page_index*$page_size;
		$sql = "SELECT 
				  `firstchar_id`,
				  `source_title`,
				  `first_chars`,
				  `flag`,
				  `version_id`,
				  `platform`,
				  `create_time` 
				FROM
				  `skyg_res`.`res_first_chars` 
				WHERE `first_chars` LIKE '$key%' 
				ORDER BY create_time ASC 
				LIMIT $start, $page_size ";
		$result = parent::createSQL($sql)->toList();
		return $result;		
	}
	
	//flag: 1表示新添词库 ；0表示新新修改词库 ；-1表示新删除词库
	//version_id: 表示修改该记录时的对应版本号
	public static function getInfoByIdAndFlag($versionId,$flag){//ver0.0.0 更新.删除.添加
		$sql = "SELECT 
				  `firstchar_id`,
				  `source_title`,
				  `first_chars`,
				  `flag`,
				  `version_id`,
				  `platform`,
				  `create_time` 
				FROM
				  `skyg_res`.`res_first_chars` 
				WHERE `version_id` > $versionId 
				  AND `flag` = $flag 
				ORDER BY `create_time` ASC ";
		$result = parent::createSQL($sql)->toList();
		return $result;
	}
	
	//flag: 1表示新添词库 ；0表示新新修改词库 ；-1表示新删除词库
	//version_id: 表示修改该记录时的对应版本号
	//ver0.0.0 更新.删除.添加
	/**
	 * 
	 * @param Int $versionId
	 * @param Int $flag
	 * @return Int
	 */
	public static function getMaxVersionByIdAndFlag($versionId,$flag){
		$sql = "SELECT 
				  MAX(version_id) 
				FROM
				  `skyg_res`.`res_first_chars`  
				WHERE `version_id` > $versionId 
				  AND `flag` = $flag ";
		$result = parent::createSQL($sql)->toValue();
		return $result;
	}
	
	/**删除first_chars
	 * 
	 * @param Int $id
	 * @param string $title
	 * @return Int 0-delete failed 1-deleted successfully
	 */
	public static function deleteThesaurus($id,$title) {		
		$sql = "DELETE 
				FROM
				  `skyg_res`.`res_first_chars` 
				WHERE `flag` = - 1 
				  AND `source_title` = '$title' ";
		$result1 = parent::createSQL($sql)->exec();		
	
		$sql = "UPDATE 
				  `skyg_res`.`res_first_chars` a,
				  (SELECT MAX(`version_id`)+1 AS max_v_id FROM  `skyg_res`.`res_first_chars`  WHERE `flag` = - 1 )AS b
				SET
				   a.`flag` = - 1 ,
				   a.`version_id` = IFNULL(b.max_v_id,0)
				WHERE  a.`firstchar_id` = $id";
		$result2 = parent::createSQL($sql)->exec();
		if(($result1==0)&&($result1==0))
			return 0;
		else 
			return 1;
		
	}
	
	/**更新first_chars
	 * 
	 * @param Int $id
	 * @param string $title
	 * @param string $first_chars
	 * @return int
	 */
	public static function updateThesaurus($id,$title,$first_chars) {
		$first_chars = strtoupper($first_chars);
		$sql = "UPDATE 
					`skyg_res`.`res_first_chars`  a,
					(SELECT MAX(version_id)+1 AS update_version FROM `skyg_res`.`res_first_chars` WHERE `flag`=0) AS b
				SET
					 `source_title` = '$title',
					 `first_chars` = '$first_chars',
					 `flag` = 0,
					 `version_id` = IFNULL(b.update_version,0)
				WHERE `firstchar_id` = $id ";
		$result = parent::createSQL($sql)->exec();
		return $result;
	}
	
	/**添加first_chars
	 * 
	 * @param string $title
	 * @param string $first_chars
	 * @return number
	 */
	public static function add_thesaurus($title,$first_chars) {
		$first_chars = strtoupper($first_chars);
		$sql ="INSERT INTO `skyg_res`.`res_first_chars` (
			`source_title`,
			`first_chars`,
			`flag`,
			`version_id`
			)
			SELECT
			'$title',
			'$first_chars',
			1,
			MAX(version_id)+1
			FROM
			`skyg_res`.`res_first_chars`
			WHERE `flag` = 1";
		$result = parent::createSQL($sql)->exec();
		return $result;	
	}
	
	//$type = 1 搜索title $type = 0搜索first_chars
	/**搜索first_chars信息
	 * 
	 * @param Int $type
	 * @param string $key
	 * @param Int $page_index
	 * @param Int $page_size
	 * @return array:
	 */
	public static function search_thesaurus($type,$key,$page_index,$page_size) {
		$start = $page_index*$page_size;
		if($type==1){
			$col_name='source_title';
		}else{
			$col_name='first_chars';
		}
		
		$sql = "SELECT 
				  `firstchar_id`,
				  `source_title`,
				  `first_chars`,
				  `flag`,
				  `version_id`,
				  `platform`,
				  `create_time` 
				FROM
				  `skyg_res`.`res_first_chars` 
				WHERE `flag` != - 1 
				  AND $col_name LIKE '%$key%' 
				ORDER BY LENGTH(`source_title`) ASC 
				LIMIT $start, $page_size ";
		var_dump($sql);
		$result = parent::createSQL($sql)->toList();
		return $result;
			
	}
	
	/**获取热词信息
	 * 
	 * @param int $type
	 * @param int $size
	 * @return array:
	 */
	public static function getHotWords($type,$size) {
		$sql = "select `key`,`type`,`num` from `skyg_res`.`res_hotword`";
		if ($type != '') {
			$sql .= " where `type`=$type";
		}
		$sql .= " order by `num` desc limit $size";
		$result = parent::createSQL($sql)->toList();
		return $result;

	}	
	
	
}
