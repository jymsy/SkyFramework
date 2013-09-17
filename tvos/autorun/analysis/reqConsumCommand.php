<?php
namespace tvos\autorun\analysis;

use Sky\console\ConsoleCommand;
use Sky\Sky;
use Sky\utils\Socket;
/**
 * 定时提交本地保存的接口执行时间日志
 * 
 * 每次执行前会删除过期的日志
 * @author Jiangyumeng
 *
 */
class reqConsumCommand extends ConsoleCommand{
	/**
	 * @var integer 日志保存时限，默认为1200秒
	 */
	private $secDel=1200;
	/**
	 * @var string 本地web服务器的ip
	 */
	private $serverIp='127.0.0.1';
	/**
	 * @var string 远程日志服务器的ip
	 */
	public $logServer='10.132.43.142';//121.199.33.201
	/**
	 * @var integer 远程日志服务器的端口
	 */
	public $port=40026;
	/**
	 * @var string 数据表interface字段填充内容。
	 */
	public $interface='cmd_stat';
	/**
	 * @var string 保存在日志服务器的目录
	 */
	public $fileName='/data/skyos_log/stat/SkyOsStat.log';
	/**
	 * @var string 消息的backstageID字段
	 */
	public $backstageID='AA';

	/* *
	 * @see \Sky\console\ConsoleCommand::init()
	 */
	public function init()
	{
		$ip=shell_exec("/sbin/ifconfig eth1|grep \"inet addr:\"|cut -d: -f2|awk '{print $1}'");
		$this->serverIp=trim($ip);
		// 		echo strlen($this->serverIp);
		echo $this->serverIp."\n";
		if (!preg_match('/^(([1-9]?[0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5]).){3}([1-9]?[0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$/',$this->serverIp)) {
			echo 'ip format error';
			Sky::$app->end();
		}
	}

	/**
	 * 执行的动作
	 * @return number
	 */
	public function actionRun()
	{
		$now=time();
		$this->delOldLog($now);
		$fileName=REQ_LOG_DIR.date('Hi',$now-60);
// 		$fileName=REQ_LOG_DIR.'1551';
		echo 'read from log file:'.$fileName."\n";
		if (is_file($fileName))
		{
			$result=shell_exec("cat $fileName|awk '{a[$2]++;b[$2]+=$1} END {for (i in a) print i,\":\"a[i]\":\"b[i]}'|sed 's/ //g'|xargs");
			echo $result."\n";
			$formatRet=$this->formatRet($result);
			if($this->sendMsg($formatRet)===false)
			{
				echo "send msg error\n";
				return 1;
			}
		}
		else
		{
			echo "$fileName is not exist!";
		}

	}

	/**
	 * 将awk后的结果转换为数组。
	 * demo/shit:5:47006 demo/list:30:276746 demo/fuck:6:57336
	 * @param string $str 处理后的请求信息
	 * @return array 转换后的请求信息数组
	 */
	private function formatRet($str)
	{
		$retArr=array();
		$tmpArr=explode(' ', $str);
		foreach ($tmpArr as $cmd)
		{
			$pos=strpos($cmd, ':');
			$retArr[substr($cmd, 0, $pos)]=substr($cmd, $pos+1);
		}
		// 		var_dump($retArr);
		
		return $retArr;
	}

	/**
	 * 删除过期的日志
	 * @param integer $now 当前时间
	 */
	private function delOldLog($now)
	{
		$name=REQ_LOG_DIR.date('Hi',$now-$this->secDel);
		@unlink($name);
		echo 'delete old file:'.$name."\n";
	}

	/**
	 * 向日志服务器发送信息。
	 * @param array $formatRet 包含请求信息的数组
	 * @return boolean false发送失败
	 */
	private function sendMsg($formatRet)
	{
		$msgArr=array();
		$msgLens=0;
		$time=date('Y-m-d H:i:s');
		foreach ($formatRet as $cmd=>$content)
		{
			sscanf($content, "%d:%d",$cmdCount, $cmdConsum);
			$content="[$time] $this->interface\t$this->serverIp\t$cmd\t$cmdCount\t$cmdConsum";
			$msgArr[]=array(
					'len'=>strlen($content),
					'content'=>$content
			);
			$msgLens+=2+strlen($content);
		}

		$fileNameLen=strlen($this->fileName);
		$iLen=20+$fileNameLen+$msgLens;
// 		echo $iLen."\n";
		var_dump($msgArr);

		$logArr=array(
				'iLen'=>array('N',$iLen),
				'shVer'=>array('n','1'),
				'shCmd'=>array('n','2'),
				'uiSeq'=>array('N',time()),
				'backstageIDLen'=>array('n','2'),
				'backstageID'=>array('A2','AA'),
				'shLogNameLen'=>array('n',$fileNameLen),
				'logfilename'=>array('a*',$this->fileName),
				'shLogCount'=>array('n',count($msgArr)),
		);

		foreach ($msgArr as $i=>$msg)
		{
			$logArr['msgLen'.$i]=array('n',$msg['len']);
			$logArr['msg'.$i]=array('a*',$msg['content']);
		}
		// 		var_dump($logArr);

		$str=Socket::packByArr($logArr);

		return $this->sendLog($str);
	}

	/**
	 * 发送日志。
	 * @param string $str 发送的字符串
	 * @return boolean 连接或发送失败返回false
	 */
	private function sendLog($str)
	{
		$socket=new Socket();
		if($socket->connect($this->logServer,$this->port))
		{
			$ret=$socket->sendRequest($str);
			$socket->disconnect();
			return $ret;
		}else{
			echo "connect $this->logServer:$this->port error!\n";
			return false;
		}
	}
}