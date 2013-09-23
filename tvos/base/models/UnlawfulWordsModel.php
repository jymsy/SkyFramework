<?php
namespace base\models;

class UnlawfulWordsModel extends \Sky\db\ActiveRecord{

	/**
	 *@return UnlawfulWordsModel
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}
	protected static $tableName="skyg_base.base_shield_words";
	protected static $primeKey=array("words");
	
	/**
	 * 
	 * @param string $vwords   受限制的字符串
	 * @return Ambigous <NULL, unknown> 
	 */
	public static function getUnlawfulWordsCount($vwords){
	
	    
		return parent::createSQL(
				"select count(1) from`skyg_base`.`base_shield_words` where `words`='".$vwords."'"
		)->toValue();
	}
	
	
}