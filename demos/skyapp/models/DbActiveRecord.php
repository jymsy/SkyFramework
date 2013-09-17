<?php
namespace skyapp\models;

abstract class DbActiveRecord extends \Sky\db\ActiveRecord{
	public static $db;
	
	public static function getDbConnection() {
		if(self::$db!==null)
			return self::$db;
		else{
			self::$db=\Sky\sky::app()->db_cloud;
		}

	}
}