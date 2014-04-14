<?php
namespace epg\controllers;

use skyapp\models\SkyCategory;
class TestController extends \Sky\base\Controller{
	
	public function beforeAction($action){
		return true;
	}
	
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
// 		\Sky\Sky::$app->cache->set("111222", "this is mymcache test",60);
// 		$cache=\Sky\Sky::$app->cache->get("111222");
// 		\Sky\logging\BiLogRoute::BiLog(array("test1","test2"),"TVInfo");
// 		echo \Sky\Sky::$app->request->getUserHostAddress();
// 		echo $GLOBALS['session'];
// 		echo 'ok';
// 		echo \Sky\Sky::$app->curl->get("http://www.baidu.com","path",array(),true);
			return SkyCategory::getCategory();
// 		\Sky\Sky::$app->ftp->get("index.php");
		return "epg action hello";
	}
	
	/**
	 * 传入两个int整数，返回求和后的值
	 * @param int first value
	 * @param int second value
	 * @return int 求和后的值
	 */
	public function actionInt($first,$second){
		return $first+$second;
	}
	
	/**
	 * 检测传入的值是否大于5
	 * @param int $num
	 * @return boolean
	 */
	public function actionNumber($num){
		if(is_numeric($num) && $num>5)
			return true;
		else 
			return false;
	}
	
	/**
	 * 返回1.2354
	 * @return float
	 */
	public function actionFloat(){
		return 1.2354;
	}
	
	/**
	 * 返回object
	 * @return object_App
	 */
	public function actionObject(){
		$app = new \stdClass();
		$app->ap_name="jd";
		$app->ap_icon="y";
		$app->ap_version=123;
		return $app;
	}
	
	/**
	 * 返回数组,
	 * @return array_Category
	 */
	public function actionArray(){
		$app = new \stdClass();
		$app->cg_name="jd";
		$app->cg_icon="y";
		$app->cg_id=123;
		return $app;
		
// 		return $this->forward('skyapp/app/getApp',316,10,0);
// 		return $tt;
	}
}