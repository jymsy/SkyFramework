<?php
namespace epg\controllers;
class AddController extends \Sky\base\Controller{
	
	public function actionAdd($f,$s,$t,$fo){
		return $f+$s+$t+$fo;
	}
	
}