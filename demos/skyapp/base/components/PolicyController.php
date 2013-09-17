<?php
namespace skyapp\base\components;

use Sky\base\Controller;
use Sky\Sky;
class PolicyController extends Controller{
	public function beforeAction($action){
		echo 'ok';
		var_dump(Sky::$app->params['policyActions']);
		var_dump($_GET);
		return true;
	}
}