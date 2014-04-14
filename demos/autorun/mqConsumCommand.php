<?php
namespace demos\autorun;

use Sky\console\ConsoleCommand;
use Sky\Sky;
class mqConsumCommand extends ConsoleCommand {
	public $ename = 'e_linvo'; //交换机名
	public $qname = 'q_linvo'; //队列名
	public $kroute = 'key_1'; //路由key
	public $maxExecCount = 5;
	private $counter=0;
	public $maxExecTime=10;
	private $_msgArray  = array();
	private $beginTime;
	
	public function init()
	{
		
	}
	
	public function actionRun()
	{
		$rabbit=Sky::$app->rabbitmq;
		$rabbit->createConnection();
		$rabbit->setTimeOut(10, 'read');
		$rabbit->exchange->init('e_linvo', AMQP_EX_TYPE_DIRECT, AMQP_DURABLE);
		$rabbit->queue->init('q_linvo', AMQP_DURABLE);
		$rabbit->queue->bind('e_linvo', 'key_1');
		
		while (1) {
			try {
				$this->beginTime = time();
				$rabbit->queue->consume(array($this, 'myCallback'));
			} catch (\Exception $e) {
				echo "get exception\n";
				if ($rabbit->isConnected()) {
					echo "just timeout\n";
				}else
					return 1;
			}
			echo "get max!!\n";
			$this->process($this->_msgArray);
			$this->_msgArray = array();
			$this->counter = 0;
		}
	}
	
	public function actionStomp()
	{
		$stomp = Sky::$app->activemq;
// 		$stomp->setSendHeader('persistent', 'true');
		$stomp->send("/queue/test", "test1");
	}
	
	public function actionRecv()
	{
		$stomp = Sky::$app->activemq;
		$stomp->recvMsg("/queue/test",array($this, 'stompCallback'));
	}
	
	public function actionRecvr($ename = null, $qname = null)
	{
		if ($ename != null) {
			$this->ename = $ename;
		}
		if ($qname != null) {
			$this->qname = $qname;
		}
		$rabbit=Sky::$app->rabbitmq;
		$rabbit->createConnection();
		$rabbit->exchange->init($this->ename, AMQP_EX_TYPE_DIRECT, AMQP_DURABLE);
		$rabbit->queue->init($this->qname, AMQP_DURABLE);
		$rabbit->queue->bind($this->ename, 'key_1');
		
		$first = time();
		while (1)
		{
			do{
				// 	echo microtime(true)."\n";
				$msg=$rabbit->queue->get();
				// 	echo microtime(true);
				if ($msg) {
					
					echo $msg->getBody();
					$this->_msgArray[]=$msg->getBody();
					$this->counter++;
				}
				
				if ($this->counter > $this->maxExecCount || $this->timeout($first)) {
					echo "max count\n";
					$this->process($this->_msgArray);
					$this->_msgArray = array();
					$this->counter = 0;
					$first=time();
				}
			}while($msg);
			usleep(50);
		}
	}
	
	public function timeout($first)
	{
		return time()-$first > $this->maxExecTime;
	}
	
	private function process($msgArray)
	{
		var_dump($msgArray);
	}
	
	public function stompCallback($msg, $queue)
	{
		if ( $msg != null) {
			echo "Received message with body '$msg->body'\n";
			// mark the message as received in the queue
			$queue->ack($msg);
		} else {
			echo "Failed to receive a message\n";
		}
		if($this->counter++ > $this->maxExecCount)
			return FALSE;  //处理5个消息后退出
	}
	
	public function myCallback($envelope, $queue)
	{
		$msg = $envelope->getBody();
		echo $msg."\n"; //处理消息
		$this->_msgArray[] = $msg;
// 		$queue->ack($envelope->getDeliveryTag()); //手动发送ACK应答
		if($this->counter++ > $this->maxExecCount || $this->timeout($this->beginTime))
			return FALSE;  //处理n个消息或超时后退出
	}
	
	public function actionCurl($url)
	{
		Sky::$app->curl->downloadFile($url, '/tmp/curldown.file');
	}
}