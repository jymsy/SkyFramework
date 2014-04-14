<?php
namespace skyapp\models;

use Sky\base\Model;
use skyapp\components\UserIdentity;
use Sky\Sky;
class LoginForm extends Model{
	public $username;
	public $password;
	
	public $rememberMe;
	public $fuckme;
	
	private $_identity;
	
	public function rules()
	{
		return array(
// 				array('fuckme', 'authenticate','params'=>array('first'=>123,'second'=>'fycc')),
				array('rememberMe','boolean'),
				array('password','validatePassword')
		);
	}
	
	public function authenticate($attribute,$params)
	{
// 		$this->_identity=new UserIdentity($this->username,$this->password);
// 		if(!$this->_identity->authenticate())
// 			$this->addError('password','Incorrect username or password.');
		$this->addError($attribute,'not right');
	}
	
	public function validatePassword()
	{
		$user=User::model();
		$userName=$user->getByName($this->username);
// 		$user = User::findByUsername($this->username);
		if (!$userName || !$user->validatePassword($this->password)) {
			$this->addError('password', 'Incorrect username or password.');
		}
	}
	
	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
				'rememberMe'=>'Remember me next time',
		);
	}
	
	public function login()
	{
		if ($this->validate()) {
			Sky::$app->getUser()->login(User::model());
			return true;
		}else 
			return false;
// 		if($this->_identity===null)
// 		{
// 			$this->_identity=new UserIdentity($this->username,$this->password);
// 			$this->_identity->authenticate();
// 		}
// 		if($this->_identity->errorCode===UserIdentity::ERROR_NONE)
// 		{
// // 			$duration=$this->rememberMe ? 3600*24*30 : 0; // 30 days
// 			Sky::$app->user->login($this->_identity,$duration);
// 			return true;
// 		}
// 		else
// 			return false;
	}
}