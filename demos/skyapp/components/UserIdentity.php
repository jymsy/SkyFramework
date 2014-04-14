<?php
namespace skyapp\components;

use skyapp\models\User;
class UserIdentity extends \Sky\web\auth\UserIdentity{
	public function authenticate()
	{
		$user=User::model();
		$userName=$user->getByName($this->username);
		if ($userName===null) {
			$this->errorCode=self::ERROR_USERNAME_INVALID;
		}else if (!$user->validatePassword($this->password)) {
			$this->errorCode=self::ERROR_PASSWORD_INVALID;
		}else{
			$this->errorCode=self::ERROR_NONE;
		}
		return $this->errorCode==self::ERROR_NONE;
	}
	
}