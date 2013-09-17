<?php
namespace tvos\autorun\models;
/**            
 * 
 * @author zhengyun
 */

class WebSiteNavModel extends \Sky\db\ActiveRecord{
	/**
	 *@return WebSiteNavModel
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}
	
	/**
	 * 获取websiteNavigation表
	 */
	public static function getWebsiteNavigation(){
		$sql = "select `site_url`,`site_name`
				from `skyg_res`.`res_site_navigation`";
		$result = parent::createSQL($sql)->toList();
		return $result;	
	}
	
	public static function getCreatDB_flag(){
		$sql = "select `promise_value` from `skyg_base`.`base_promise` where `promise_type`='websiteNavigation' and `promise_key`='flag_creatDB' limit 1";
		$result = parent::createSQL($sql)->toValue();
		if (count($result)<1){
			$sql = "insert into `skyg_base`.`base_promise` (`promise_type`,`promise_key`,`promise_value`) values ('websiteNavigation','flag_creatDB','1')";
			$result = parent::createSQL($sql)->exec();
			return 1;
		}
		return intval($result);
	}
	
	public static function alterCreatDB_flag($value){		
		$sql = "UPDATE `skyg_base`.`base_promise` SET `promise_value`='$value' WHERE `promise_type`='websiteNavigation' AND `promise_key`='flag_creatDB'";
		$result = parent::createSQL($sql)->exec();
		return $result;
	}	
	
	
	
	
}
