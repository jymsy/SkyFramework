<?php
namespace skyapp\base\controllers;

use skyapp\base\components\PolicyController;
class FuckkController extends PolicyController{
	public function actions(){
		return array(
				"Wsdl"=>array(
						"class"=>"Sky\\base\\WebServiceAction"
				),
		);
	}

	/**
	 * hello world 测试
	 * @return  string hello epg
	 */
	public function actionHello(){
		echo "this is skyapp in skyapp modules";
	}
}