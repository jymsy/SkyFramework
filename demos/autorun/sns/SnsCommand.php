<?php
namespace demos\autorun\sns;

use Sky\console\ConsoleCommand;
use skyapp\models\SkyCategory;
class SnsCommand extends ConsoleCommand{
	public $paar=2;
	public function actionTest($par=6){
		echo 'test command:'.$par.':'.$this->paar;
		return 0;
	}
	
	public function actionSns($a=3,$b){
		echo 'hello  '.$a.':'.$b;
		return 0;
	}
}