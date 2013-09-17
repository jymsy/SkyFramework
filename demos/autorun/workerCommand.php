<?php
namespace demos\autorun;
use Sky\console\ConsoleCommand;
use Sky\Sky;

class WorkerCommand extends ConsoleCommand{
	private $_woker;
	
	public function init(){
		$this->_woker=Sky::$app->gearman->worker();
		$this->_woker->addOptions(GEARMAN_WORKER_NON_BLOCKING);
		$this->_woker->addFunction('reverse2', array($this,'testRever'));
	}
	
	public function actionRun(){
		while (@$this->_woker->work()||
				$this->_woker->returnCode() == GEARMAN_IO_WAIT ||
				$this->_woker->returnCode() == GEARMAN_NO_JOBS){
			
			if ($this->_woker->returnCode() == GEARMAN_SUCCESS)
				continue;
		
			echo "Waiting for next job...\n";
			if (!@$this->_woker->wait()){
				if ($this->_woker->returnCode() == GEARMAN_NO_ACTIVE_FDS){
					echo "getting sleep\n";
					# We are not connected to any servers, so wait a bit before
					# trying to reconnect.
					sleep(5);
					continue;
				}
		
				    break;
			}
		}
		
	}
	
	public function testRever($job){
		echo "Received job: " . $job->handle() . "\n";
		echo "unique id: ".$job->unique()."\n";
		echo "Workload: ".$job->workload()."\n";
		return strrev($job->workload());
	}
}