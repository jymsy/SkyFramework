<?php
namespace tvos\autorun\models;
/**            
 * 
 * @author xiaokeming
 */

class CrawlerFileVersionModel extends \Sky\db\ActiveRecord{
	/**
	 *@return CrawlerFileVersionModel
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}
	
	/**
	 *
	 * @param int $v_versionid    版本号
	 * @param string $v_filename  文件名
	 *
	 */
	public static function insertcrewlerfileversion($v_versionid,$v_filename){
		return parent::createSQL("insert into `skyg_base`.`base_crawlerfile_version` (
										`version_id`,
										`file_name`)
								  values(:v_versionid,:v_filename)",
				              array("v_versionid"=>(int)$v_versionid,
				              		"v_filename"=>$v_filename))->exec();
	}
	
	/**
	 *
	 * 返回最大版本号
	 */
	public static function querycrawlerfileversion(){
		return parent::createSQL("SELECT
									     ifnull(max(`version_id`),0)
									FROM `skyg_base`.`base_crawlerfile_version`")->toValue();
	}
}