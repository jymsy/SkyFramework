<?php
namespace skyapp\models;

use Sky\db\ActiveRecord;
use Sky\help\Security;
use Sky\Sky;
use Sky\base\IUserIdentity;
class User extends ActiveRecord implements IUserIdentity {
	public $username;
	private $_id;
	public $password;
	
	/**
	 * @param system $className
	 * @return User
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function getByName($name){
		$ret=static::createSQL(
				'select id,name,password from base.user where name=:name',
				array('name'=>$name)
		)->toList();
		if ($ret) {
			$this->_id=$ret[0]['id'];
			$this->username=$ret[0]['name'];
			$this->password=$ret[0]['password'];
			return $this->username;
		}else 
			return null;
	}
	
	public function validatePassword($password)
	{
		Sky::beginProfile('pass');
		return Security::validatePassword($password,$this->password);
		Sky::endProfile('pass');
	}
	
	public function getId(){
		return $this->_id;
	}
	
	public function getName(){
		return $this->username;
	}
	
	public function authenticate(){
		
	}
}