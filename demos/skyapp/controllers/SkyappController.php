<?php
namespace skyapp\controllers;

use skyapp\models\CategoryApp;
use skyapp\models\SkyApp;
use skyapp\models\SkyCategory;
use Sky\base\Controller;
use Sky\caching\CacheProxy;
use skyapp\models\LoginForm;
use Sky\help\Security;
use Sky\Sky;

class SkyappController extends Controller{
	public $layout='main';
	
	public function getActionParams(){
// 		return $_GET;
				return $_REQUEST;
	}
	
	public function actions(){
		return array(
				'Wsdl'=>array(
						'class'=>'Sky\base\WebServiceAction'
				),
				'Captcha'=>array(
						'class'=>'Sky\web\widgets\captcha\CaptchaAction'
				),
		);
	}
	
	
	/**
	 * hello world 测试
	 * @return  string hello world
	 */
	public function actionHello(){
// 		\Sky\Sky::log("jsdfsdf", "warning", "app");
// 		\Sky\logging\PQPLogRoute::logMemory("memory used after action call");
// 		echo $GLOBALS["session"];
// 		echo \Sky\Sky::$app->controller->module->idc;
// 		$ret=CacheProxy::newInstance(SkyCategory::model(),3600)->getCategory();
// 		var_dump($ret);
		return \Sky\Sky::$app->params["fuck"];
// 		\Sky\utils\VarDump::dump("sdfsdf",5,true);
// 		\Sky\Sky::$app->cache->set($id, $value);
	}
	
// 	public function actionResultList(){
// 		$resultlist = new \stdClass();
// 		$resultlist->page=0;
// 		$resultlist->pagesize=10;
// 		$resultlist->total=3;
// 		$resultlist->className='com.skyworth.webservice.appstore.AppEntity';
// 		$arr=array();
// 		$app = new \stdClass();
// 		$app->ap_name="jd";
// 		$app->ap_res="y";
// 		$app->ap_download_count=123;
// 		$app->ap_isAvaliable=true;
// 		$arr[] = $app;
// 		$resultlist->result = $arr;
// 		\Sky\utils\VarDump::dump($resultlist,20,true);
// 		return $resultlist;
// 	}
// 	public function actionList(){
// 		$arr=array();
// 		$app = new \stdClass();
// 		$app->ap_name="jd";
// 		$app->ap_res="y";
// 		$app->ap_download_count=123;
// 		$app->ap_isAvaliable=true;
// 		$arr[] = $app;
// 		return  $arr;
// 	}
	
	/**
	 * 返回object
	 * @return object_Test|object_Version|object_AppInfo test
	 */
	public function actionObject(){
		$app = new \stdClass();
		$app->ap_name="jd";
		$app->ap_res="y";
		$app->ap_download_count=123;
		$app->ap_isAvaliable=true;
		return $app;
	}
	
// 	/**
// 	 * view render 测试
// 	 * @return string nothing
// 	 */
	public function actionLogin(){

		$model=new LoginForm();
		$model->attributes=array('rememberMe'=>1,'fuckme'=>2,
														'username'=>'jym','password'=>'jymsy');
// 		$model->validate();
		if($model->login()){
			echo Sky::$app->getSession()->getId();
			echo Sky::$app->getUser()->getId();
			echo Sky::$app->getUser()->getName();
// 			$this->goHome();
		}else{
			
		}
		
	}
	
	public function actionLogout(){
		Sky::$app->getUser()->logout();
	}
	
	public function actionCatpcha(){
		$this->render("testview",array("a"=>1,"b"=>2));
	}
	
	public function actionPassword(){
		echo Security::generatePasswordHash('jymsy');
	}
	
	/**
	 * 获取应用分类信息
	 * @return array_Category 分类信息列表。
	 */
	public function actionGetCategory(){
// 		Sky::log($GLOBALS["session"]);
		\Sky\logging\PQPLogRoute::logMemory("memory used after action call");
		\Sky\Sky::beginProfile('category');
		$category=SkyCategory::getCategory();
		\Sky\Sky::endProfile('category');
// 		var_dump($category); 
		return $category;
	}
	
	/**
	 * 获取指定分类的
	 * 应用列表。
	 * @param int  分类id
	 * @param int  每页显示数目
	 * @param int  第几页
	 * @return array_Apps 应用列表
	 * 
	 * @see self::actionGetCategory
	 */
	public function actionGetApp($category,$page_size,$page_index){
		$result = SkyApp::getApp($category, $page_size, $page_index);
		return $result;//不可使用json_encode，框架会统一调用
	}
	
	/**
	 * 测试插入app,目前没有实现，不要调用
	 * @return boolean 是否插入成功。
	 */
	public function actionInsertApp(){
// 		$skyapp=SkyApp::model();
// 		$skyapp->Product_Name="test";
// 		$skyapp->save();
		return true;
	}
	
// 	/**
// 	 * 获取应用详细信息
// 	 * @param int 应用id
// 	 * @return array_AppDetail 应用详细信息
// 	 */
// 	public function actionGetAppDetail($Product_ID){
// 		return SkyApp::getAppDetail($Product_ID);
// 	}
	
	/**
	 * 获取应用升级信息
	 * @param string 平台名称
	 * @param string 应用包名
	 * @return array_AppInfo 应用升级信息
	 */
	public function actionGetAppInfo($platform,$ap_package){
		return CategoryApp::getAppInfo($platform,$ap_package);
	}
}