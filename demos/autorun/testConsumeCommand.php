<?php
namespace demos\autorun;

use Sky\utils\rabbitmq\ConsumerCommand;
class testConsumeCommand extends ConsumerCommand{
	public $timeout;
	public $maxExecCount;
	public $maxExecTime;
	public $ename = 'e_linvo'; //交换机名
	public $qname = 'q_linvo'; //队列名
	public $kroute = 'key_1'; //路由key
	
	public function init()
	{
		$this->timeout=10;
		$this->maxExecCount =5;
		$this->maxExecTime = 10;
		parent::init();
	}
	
	public function actionRun()
	{
		$this->initExchange($this->ename);
		$this->initQueue($this->qname);
		$this->bindQueue($this->ename, $this->kroute);
		$this->consume();
	}
	
	public function processMsg($msgArray)
	{
		var_dump($msgArray);
	}
}