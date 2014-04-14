<?php
namespace skyapp;

class SkyappModule extends \Sky\base\WebModule{
// 	public $idc=1101;
	
// 	public function init(){
// 		echo "this is init";
// 	}

	/* (non-PHPdoc)
	 * @see \Sky\base\WebModule::beforeControllerAction()
	 */
	public function beforeControllerAction($controller, $action){
		if(parent::beforeControllerAction($controller,$action)){
			var_dump($controller->getActionParams());
			return true;
		}else 
			return false;
	}
}