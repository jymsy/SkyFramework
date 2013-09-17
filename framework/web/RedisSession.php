<?php
namespace Sky\web;

use Sky\Sky;
/**
 * 
 * @author Jiangyumeng
 *
 */
class RedisSession extends Session{
	/**
	 * redis 连接实例
	 * @var \Redis
	 */
	protected $_connection;
	
	/**
	 * 初始化
	 */
	public function init(){
		if ($this->_connection === null) {
			if (!isset(Sky::$app->redis))
				throw new \Exception(get_class($this)." expects a 'redis' application component");
			$this->_connection = Sky::$app->redis;
		}
	
		parent::init();
	}
}