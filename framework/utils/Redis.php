<?php
namespace Sky\utils;

use Sky\base\Component;
/**
 * Redis handler class
 * 
 * Usage:
 * 'components' => array(
 *   'redis' => array(
 *       'class' => 'Sky\utils\Redis',
 *       'hostname' => 'localhost',
 *       'port' => 6379,
 *       'database' => 1,
 *       'prefix' => 'Sky.redis.'
 *   ),
 *   ...
 * ),
 * 
 * @author Jiangyumeng
 *
 */
class Redis extends Component{
	/**
	 * redis客户端实例
	 * @var \Redis
	 */
	protected $_client;
	
	/**
	 * redis服务器地址
	 * @var string
	 */
	public $hostname = 'localhost';
	
	/**
	 * Redis默认前缀
	 * @var string
	 */
	public $prefix = 'Sky.redis:';
	
	/**
	 * redis服务器端口
	 * @var integer
	 */
	public $port=6379;
	
	/**
	 * 要使用的redis数据库默认是1
	 * @var integer
	 */
	public $database=1;
	
	/**
	 * 是否是长连接
	 * @var boolean
	 */
	public $persistent=false;
	
	/**
	 * 连接超时时间，默认为0 不限制时长
	 * @var int
	 */
	public $timeout=0;
	
	/**
	 * redis服务器密码
	 * @var string
	 */
	public $password=null;
	
	public function init(){
		if ($this->_client === null) {
			$this->_client = new Redis;
			if($this->persistent)
				$this->_client->pconnect($this->hostname, $this->port,$this->timeout);
			else 
				$this->_client->connect($this->hostname, $this->port,$this->timeout);
			if (isset($this->password)) {
				if ($this->_client->auth($this->password) === false) {
					throw new \Exception('Redis authentication failed!');
				}
			}
			$this->_client->setOption(Redis::OPT_PREFIX, $this->prefix);
			$this->_client->select($this->database);
		}
		
		parent::init();
// 		return $this->_client;
	}
	
	/**
	 * 写入key-value
	 * @param $key string 要存储的key名
	 * @param $value mixed 要存储的值
	 * 	@param $time float 过期时间(S)
	 * @param $type int 写入方式 0:不添加到现有值后面 1:添加到现有值的后面 默认0
	 * @param $repeat int 0:不判断重复 1:判断重复
	 * @param $old int 1:返回旧的value 默认0
	 * @return $return bool true:成功 flase:失败
	 */
	public function set($key,$value,$time=0,$type=0,$repeat=0,$old=0){
		if ($type) {
			return $this->_client->append($key, $value);
		} else {
			if ($old) {
				return $this->_client->getSet($key, $value);
			} else {
				if ($repeat) {
					return $this->_client->setnx($key, $value);
				} else {
					if ($time && is_numeric($time)) 
						return $this->_client->setex($key, $time, $value);
					else 
						return $this->_client->set($key, $value);
				}
			}
		}
	}
	
	/**
	 * 获取某个key值 如果指定了start end 则返回key值的start跟end之间的字符
	 * @param $key string/array 要获取的key或者key数组
	 * @param $start int 字符串开始index
	 * @param $end int 字符串结束index
	 * @return $return mixed 如果key存在则返回key值 如果不存在返回false
	 */
	public function get($key=null,$start=null,$end=null){
		$return = null;
	
		if (is_array($key) && !empty($key)) {
			$return = $this->_client->getMultiple($key);
		} else {
			if (isset($start) && isset($end)) 
				$return = $this->_client->getRange($key,$start,$end);
			else 
				$return = $this->_client->get($key);
		}
	
		return $return;
	}
}