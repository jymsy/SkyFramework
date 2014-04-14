<?php
namespace demos\components;

use Sky\web\filters\Filter;
use Sky\help\Security;
use Sky\Sky;
use Sky\base\HttpException;
use Sky\web\Response;

class VerifyFilter extends Filter{
	/**
	 * @var string appid的参数名
	 */
	const APPID='appid';
	/**
	 * @var string timestamp的参数名
	 */
	const TIMESTAMP='timestamp';
	/**
	 * @var string 签名的参数名
	 */
	const SIG='sig';
	/**
	 * @var integer 超时时间
	 */
	public $timeout=300;
	/**
	 * @var string appid对应的密码
	 */
	private $key;
	
	/**
	 * @param FilterChain $filterChain
	 * @see \Sky\web\filters\Filter::filter()
	 */
	public function filter($filterChain)
	{
		$urlman = Sky::$app->getUrlManager();
		if(isset($_REQUEST[$urlman->routeVar]) && in_array(strtolower(trim($_REQUEST[$urlman->routeVar],'/')), Sky::$app->params['crypt_route']))
		{
			if (isset($_REQUEST[self::APPID]) && isset($_REQUEST[self::TIMESTAMP]) && isset($_REQUEST[self::SIG]))
			{
				$this->key=$this->getClientKey($_REQUEST[self::APPID]);
				if ($this->key!==false) {
					if($this->getSig($this->key, $_REQUEST[self::TIMESTAMP]) === $_REQUEST[self::SIG])
					{
						if ($this->verifyTimestamp($_REQUEST[self::TIMESTAMP])) {
							Sky::$app->getResponse()->attachEventHandler(Response::EVENT_AFTER_PREPARE, array($this,'encryptResponse'));
						}else
							throw new HttpException(400,'request is out of date.');
					}else
						throw new HttpException(400,'verify signature error.');
				}else
					throw new HttpException(400,'error appid.');
			}else{
				throw new HttpException(400,'missing verify params.');
			}
		}
		return $filterChain->run();
	}
	
	/**
	 * 加密响应的内容。
	 * @param Event $event
	 */
	public function encryptResponse($event)
	{
		$event->sender->setHeader('Content-Type', 'text/html; charset=' . $event->sender->charset);
		$event->sender->content=Security::strCode($event->sender->content, 'ENCODE', $this->key);
	}
	
	/**
	 * 验证时间戳是否在允许的范围内。
	 * @param integer $timestamp
	 * @return boolean
	 */
	public function verifyTimestamp($timestamp)
	{
		$now = time();
		return $timestamp > $now - $this->timeout && $timestamp < $now+$this->timeout;
	}
	
	/**
	 * 计算签名值
	 * @param string $key 应用密码
	 * @param integer $timestamp 时间戳
	 * @return string 签名值
	 */
	public function getSig($key, $timestamp)
	{
		$params=$_REQUEST;
		ksort($params);
		$str=$key;
		foreach ($params as $name=>$value)
		{
			if ($name!=self::SIG && !empty($value)) {
				$str.=$name.$value;
			}
		}
		$str.=$timestamp;
		echo md5($str);
		return md5($str);
	}
	
	/**
	 * 根据appid获取应用密码。
	 * @param string $appid
	 * @return string
	 */
	public function getClientKey($appid)
	{
		return 'jym';
	}
}