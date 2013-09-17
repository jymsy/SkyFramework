<?php
namespace Sky\web;

use Sky\base\Component;
use Sky\Sky;
use Sky\base\IUserIdentity;
/**
 * 
 * @author Jiangyumeng
 *
 */
class User extends Component{
	/**
	 * @var string 身份类名
	 */
	public $identityClass;
	
 	private $_identity=false;
 	/**
 	 * @var string 用来存储id的session变量名。
 	 */
 	public $idVar='__id';
 	/**
 	 * @var string 用来存储name的session变量名。
 	 */
 	public $nameVar='__name';
 	
	/**
	 * 初始化应用组件。
	 */
	public function init()
	{
		if ($this->identityClass === null) {
			throw new \Exception('User::identityClass must be set.');
		}
	
		Sky::$app->getSession()->open();
	}
	
	/**
	 * 用户登录
	 *
	 * 该方法存储必要的session信息
	 *
	 * @param IUserIdentity $identity 用户身份(已经应该被认证过了)
	 * @return boolean 用户是否登陆
	 */
	public function login($identity)
	{
		$this->switchIdentity($identity);
		return !$this->getIsGuest();
	}
	
	/**
	 * @return boolean 当前用户是否是客人用户。
	 */
	public function getIsGuest()
	{
		return $this->getIdentity() === null;
	}
	
	/**
	 * 退出当前用户。
	 * 如果`$destroySession`为true，这会删除session数据。
	 * @param boolean $destroySession 是否删除session数据。 默认为true。
	 */
	public function logout($destroySession = true)
	{
		$identity = $this->getIdentity();
		if ($identity !== null ) {
			$this->switchIdentity(null);
			if ($destroySession) {
				Sky::$app->getSession()->destroy();
			}
		}
	}
	
	/**
	 * 为当前用户切换新身份。
	 * 该方法将会保存必要的session信息。
	 *
	 * 该方法主要被{@link login()},{@link logout()}调用
	 *
	 * @param IUserIdentity $identity 关联到当前用户的身份信息。
	 * 如果为null意味着切换到客人用户。
	 */
	public function switchIdentity($identity)
	{
		$session = Sky::$app->getSession();
		$this->setIdentity($identity);
		$session->remove($this->idVar);
		$session->remove($this->nameVar);
		if ($identity instanceof IUserIdentity) {
			$session->set($this->idVar, $identity->getId());
			$session->set($this->nameVar, $identity->getName());
		}
	}
	
	/**
	 * 返回当前登陆用户的身份对象。
	 * @return \Sky\base\IUserIdentity 关联到当前登陆用户的身份对象。
	 * 如果用户未登陆返回null
	 * @see login
	 * @see logout
	 */
	public function getIdentity()
	{
		if ($this->_identity === false) {
			$id = $this->getId();
			if ($id === null) {
				$this->_identity = null;
			} else {
				/** @var $class Identity */
				$class = $this->identityClass;
				$this->_identity = new $class(null);
			}
		}
		return $this->_identity;
	}
	
	/**
	 * 设置身份对象。
	 * @param IUserIdentity $identity 当前登陆用户的身份对象。
	 */
	public function setIdentity($identity)
	{
		$this->_identity = $identity;
	}
	
	/**
	 * 返回当前标识用户的唯一值
	 * @return string|integer 用户的唯一身份。
	 *  如果为null的话以为着这是一个客人用户。
	 */
	public function getId()
	{
		return Sky::$app->getSession()->get($this->idVar);
	}
	
	/**
	 * 返回当前用户的用户名。
	 * @return string 用户的用户名。
	 */
	public function getName()
	{
		return Sky::$app->getSession()->get($this->nameVar);
	}
}