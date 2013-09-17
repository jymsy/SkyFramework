<?php
namespace Sky\base;

use Sky\web\WebService;
/**
 * WebServiceAction 用来处理带有ws参数的请求
 * @author Jiangyumeng
 *
 */
class WebServiceAction extends Action{
	private $_service;
	public $provider;
	public $namespace;
	public $wsdl='Wsdl';
	
	/**
	 * 执行请求
	 * @param array $params 请求的参数数组
	 * @throws \Sky\base\HttpException
	 */
	public function run($params){
		$controller=$this->getController();
		
		if(isset($_REQUEST['ws'])){
// 			ob_start();
			
			$methodName='action'.$this->getId();
			
			
			$method=new \ReflectionMethod($controller, $methodName);

			$useParamName=\Sky\Sky::$app->getUrlManager()->useParamName;
			if($method->getNumberOfParameters()>0){
				if(isset($useParamName) && $useParamName===true)
					$result=$this->runWithParamsInternal($controller, $method, $params);
				else
					$result=$this->runWithParamsOuter($controller, $method, $params);
			}else{
				$result=$this->runInternal($controller,$methodName);
			}
			
			if($result==false){
				throw new \Sky\base\HttpException(400,'Your request is invalid.'/*.ob_get_clean()*/);
			}else{
// 				ob_end_clean();
				if(!headers_sent())
					header('Content-Type: application/json;charset=utf-8');
				echo \Sky\help\JSON::encode($this->getActionOutput());
			}		
		}else{
			
			if($this->id===$this->wsdl)
				$_REQUEST['IsWsdlRequest']=true;
			$provider=$controller;
// 			$namespace=\Sky\Sky::$app->getRequest()->getBaseUrl();
			
			if(($module=\Sky\Sky::$app->controller->module)===null)
				$namespace=ltrim(\Sky\Sky::$app->getRequest()->getBaseUrl(),'\\/');
			else{
				$namespace=$module->name;
				if(($pos=strrpos($module->name,'\\'))!==false)
					$namespace=substr($module->name,$pos+1);
			}
			$this->_service=new WebService($provider,$namespace);
			$this->_service->renderWsdl();
		}
		if(function_exists('fastcgi_finish_request'))
			fastcgi_finish_request();
		\Sky\Sky::$app->end();

	}
	
// 	protected function createWebService($controller,$method,$params)
// 	{
// 		return new \Sky\base\WebService($controller,$method,$params);
// 	}
	
}