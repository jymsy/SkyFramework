<?php
namespace demos\components;

use Sky\utils\Socket;
use Sky\logging\LogRoute;
use Sky\utils\PushServer;
use Sky\logging\Logger;
/**
 * 用户行为收集日志程序。
 * 使用:
 * 		BiLogRoute::BiLog(array(...),$name);
 * 
 * @author Jiangyumeng
 *
 */
class BiLogRoute extends LogRoute{
	public $serverName='';
	
	public function init()
	{
		$this->levels = Logger::LEVET_BI;
	}
	
	public function processLogs($logs)
	{
		$socket=new Socket();
		PushServer::$svctype=100;
		$pushServer=PushServer::getHost($this->serverName);
		$pos=strpos($pushServer, ':');
		$pushIp=substr($pushServer, 0,$pos);
		$pushPort=substr($pushServer, $pos+1);
		
		if($socket->connect($pushIp,$pushPort)){
			foreach ($logs as $log){
				$this->process($log,$socket);
			}
			$socket->disconnect();
		}else
			return false;
	}
	
	/**
	 * @param array $log
	 * @param Socket $socket
	 */
	protected function process($log,$socket)
	{
		$msgStr=$this->parseMsg($log[0]);
		$msgLen=strlen($msgStr);
		$logFileName=$log[2];
		$filenameLen=strlen($logFileName);
		$iLen=40+$msgLen+$filenameLen;
		
		$uiSeq=substr($log[3], strpos($log[3], '.')+1);
		
		$logArr=array(
				'iLen'=>array('N',$iLen),
				'shVer'=>array('n','1'),
				'shCmd'=>array('n','2'),
				'uiSeq'=>array('N',$uiSeq),
				'backstageIDLen'=>array('n','20'),
				'backstageID'=>array('A20',' '),
				'shLogNameLen'=>array('n',$filenameLen),
				'logfilename'=>array('a*',$logFileName),
				'shLogCount'=>array('n','1'),
				'msgLen'=>array('n',$msgLen),
				'msg'=>array('a*',$msgStr),
		);
		
		$str=Socket::packByArr($logArr);
		$socket->sendRequest($str);
	}
	
	protected function parseMsg($logMsg){
		$msgStr='';
		$msgArr=explode('|', $logMsg);
		foreach ($msgArr as $msg){
			$msgStr.=$msg."\x05";
		}
		$msgStr=rtrim($msgStr,"\x05");
		return $msgStr."\x0a";
	}
	
	/**
	 * 记录日志
	 * @param array $msgArr
	 * @param string $category
	 */
	public static function BiLog($msgArr=array(), $filepath='application')
	{
		$msgStr=implode('|', $msgArr);
		\Sky\Sky::log($msgStr,Logger::LEVET_BI,$filepath);
	}
}