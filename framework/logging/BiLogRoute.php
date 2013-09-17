<?php
namespace Sky\logging;

/**
 * 用户行为收集日志程序。
 * 使用:
 * 		\Sky\logging\BiLogRoute::BiLog(array(...),$name);
 * 
 * @author Jiangyumeng
 *
 */
class BiLogRoute extends SocketLogRoute{
	
	public function init(){
		$this->levels = Logger::LEVET_BI;
	}
	
	protected function process($log,$socket){
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
		
		$str=$this->packByArr($logArr);

		$this->sendLog($socket,$str);
	}
	
	protected function parseMsg($logMsg){
		$msgStr='';
		$msgArr=explode('|', $logMsg);
		foreach ($msgArr as $msg){
			$msgStr.=$msg."\x05";
		}
		$msgStr=rtrim($msgStr,"\x05");
		return $msgStr."\x07";
	}
	
	public static function BiLog($msgArr=array(), $category='application'){
		$msgStr=implode('|', $msgArr);
		\Sky\Sky::log($msgStr,Logger::LEVET_BI,$category);
	}
}