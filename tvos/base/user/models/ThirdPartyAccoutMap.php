<?php

namespace base\user\models;

/**
 * @property  string       third_party_accout  第三方账户      
 * @property  int          user_id             绑定的userid      
 * @property  int          third_party_type    1-qq，2-sina weibo  
 * @property  string       create_date         创建时间  
 *  
 * @author Zhengyun
 */
class ThirdPartyAccoutMap extends \Sky\db\ActiveRecord{
	/**
	 *@return ThirdPartyAccoutMap
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}

	protected static $tableName="skyg_base.base_third_party_accout_map";
	protected static $primeKey=array("user_id","third_party_accout","third_party_type");
	
	/**
	 * 通过第三方账户查询绑定的userid
	 * @param String $third_party_accout
	 * @param Int $third_party_type
	 * @return Int user_id
	 */
	
	public static function queryThirdParty($third_party_accout,$third_party_type){
		$result=parent::createSQL(
				"SELECT user_id FROM skyg_base.base_third_party_accout_map  WHERE third_party_accout=:third_party_accout AND third_party_type=:third_party_type",
		array(  
						"third_party_accout"=>$third_party_accout,
						"third_party_type"=>(int)$third_party_type
		)
		)->toValue();
		return $result;
	}
	
	/**
	 * 添加绑定关系
	 * @param String $third_party_accout
	 * @param Int $third_party_type
	 * @param Int $user_id
	 * @return Int 1-添加成功，0-添加失败
	 */
	public static function addThirdParty($third_party_accout,$third_party_type,$user_id){
		$result=parent::createSQL(
				"INSERT INTO skyg_base.base_third_party_accout_map (`third_party_accout`,`third_party_type`,`user_id`) VALUES (:third_party_accout,:third_party_type,:user_id)",
				array(  "third_party_accout"=>$third_party_accout,
						"third_party_type"=>(int)$third_party_type,
						"user_id"=>(int)$user_id
				)
		)->exec();
		return $result;
	}
	
	/**
	 * 删除绑定关系
	 * @param String $third_party_accout
	 * @param Int $third_party_type
	 * @param Int $user_id
	 * @return Int 1-删除成功，0-删除失败
	 */
	public static function delThirdParty($third_party_accout,$third_party_type,$user_id){
		$result=parent::createSQL(
				"delete from  skyg_base.base_third_party_accout_map where`third_party_accout`=:third_party_accout and `user_id`=:user_id and `third_party_type`= :third_party_type",
				array(  "third_party_accout"=>$third_party_accout,
						"third_party_type"=>(int)$third_party_type,
						"user_id"=>(int)$user_id
				)
		)->exec();
		return $result;
	}
	
	
}