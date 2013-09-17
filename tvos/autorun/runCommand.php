<?php
namespace tvos\autorun;

use Sky\console\ConsoleCommand;

class runCommand extends ConsoleCommand {
	
	public function actionRunFirst() {
		$ruf = new resourceUpdateFirst();
		$ruf->run();
	}
	
	public function actionRun() {
		$ru = new resourceUpdate();
		$ru->run();
		$uw = new updateWeights();
		$uw->run();
// 		$uc = new updateCategory();
// 		$uc->run();
// 		$uf = new updateFirstchars();
// 		$uf->run();
	}
	
	public function actionTest(){
//		$ru = new resourceSqlite();
//		$ru->run('websiteNavigation');
		$ru = new resourceUpdate();
		$ru->GetLocalData('2013-08-24');
	}
	
	public function actionRunCopy(){
		$ru = new resourceUpdate_copy();
		$ru->run();
	}
	
	public function actionRunWeights() {
		$uw = new updateWeights();
		$uw->run();
	}
	
}