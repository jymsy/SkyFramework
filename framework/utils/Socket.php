<?php
namespace Sky\utils;

/**
 * Socket 类
 * @author Jiangyumeng
 *
 */
class Socket{
	private $_defaultServer='127.0.0.1';
	private $_defaultPort=80;
	private $_socket;
	private $_connectState=false;
	
	public function connect($server=false,$port=false){
		if ($server===false) {
			$server=$this->_defaultServer;
		}
		if ($port===false) {
			$port=$this->_defaultPort;
		}
		
		if(($this->_socket = socket_create(AF_INET,SOCK_STREAM,SOL_TCP))===false){
			return false;
		}
		
		$con=@socket_connect($this->_socket ,$server,$port);
		if($con===false){
			socket_close($this->_socket);
			return false;
		}
		$this->_connectState=true;
		return true;
		
	}
	
	protected function validateConnection(){
		return (is_resource($this->_socket) && ($this->_connectState!= false));
	}
	
	public function sendRequest($msg){
		if($this->validateConnection()){
			$result = socket_write($this->_socket,$msg,strlen($msg));
			 return $result;
		}
		return false;
	}
	
	public function disconnect(){
		if($this->validateConnection()){
			socket_shutdown($this->_socket);
			socket_close($this->_socket);
			$this->_connectState= false;
			return true;
		}
		return false;
	}
	
	public static function  packByArr($logArr){
		$atStr='';
		foreach ($logArr as $k=>$v){
			if(isset($v[1]))
				$atStr.=pack($v[0],$v[1]);
			else
				$atStr.=pack($v[0]);
		}
		return $atStr;
	}
}