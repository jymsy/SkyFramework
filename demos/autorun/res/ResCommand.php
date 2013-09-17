<?php
namespace demos\autorun\res;

use Sky\console\ConsoleCommand;
use autorun\res\models\SkyCategory;
// use \autorun\res\models\SkyCategory;
class ResCommand extends ConsoleCommand{
	public $paar=2;
	public function actionTest($par=6){
		echo 'test command:'.$par.':'.$this->paar;
		return 0;
	}
	
	public function actionGetCategory(){
		$t=new Test();
		$t->test();
		var_dump(SkyCategory::getCategory());
	}
}