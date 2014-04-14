<?php
namespace demos\components;

use Sky\base\UrlManager;
use Sky\help\Security;
use Sky\Sky;
use Sky\base\HttpException;
use Sky\web\Response;
/**
 * 验证url的签名是否正确
 * 
 * 原始url：http://localhost:8080/demos/index.php?_r=demo/list&par=dddd&ws&_s=jasdofijwoeij23123
 * 添加认证的url：http://localhost:8080/demos/index.php?_r=demo/list&par=dddd&ws&_s=jasdofijwoeij23123&sig=fjwoiejsdfjsife3234&timestamp=123123123&appid=11233
 * 
 * 添加了三个参数
 * timestamp：时间戳（秒）
 * appid：应用的id
 * sig：客户端信息签名值
 * 
 * sig的组装格式
 * (1)根据参数名称（除签名）将所有请求参数按照字母先后顺序排序:
 * 		key + value .... key + value。
 *     没有参数值或参数值为空的除外，编码为utf-8.
 *     例如：将foo=1,bar=2,baz=3 排序为bar=2,baz=3,foo=1，
 *     参数名和参数值链接后，得到拼装字符串bar2baz3foo1。
 * (2)将应用的密钥拼接到参数字符串头，将timestamp添加到尾部行md5加密，
 * 		格式是：md5(secretkey1value1key2value2...timestamp)
 * 
 * 其中timestamp允许的误差为{timeout}秒。
 * @author Jiangyumeng
 *
 */
class CryptUrl extends UrlManager{
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
	
	public function init()
	{
		parent::init();
		if(isset($_REQUEST[$this->routeVar]) && in_array(strtolower(trim($_REQUEST[$this->routeVar],'/')), Sky::$app->params['crypt_route']))
		{
			if (isset($_REQUEST[self::APPID]) && isset($_REQUEST[self::TIMESTAMP]) && isset($_REQUEST[self::SIG])) 
			{
				$this->key=$this->getClientKey($_REQUEST[self::APPID]);
				if ($this->key!==false) {
					if($this->getSig($this->key, $_REQUEST[self::TIMESTAMP]) === $_REQUEST[self::SIG])
					{
						if ($this->verifyTimestamp($_REQUEST[self::TIMESTAMP])) {
							define('ENCRYPT_REQUEST', true);
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
// 		echo md5($str);
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