<?php
namespace epg\base\controllers;

class FuckController extends \Sky\base\Controller{
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
		echo "this is epg in epg modules";
	}
}