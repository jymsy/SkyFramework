<?php
namespace appstore\controllers;

use Sky\Sky; 

use Sky\base\Controller;
use base\components\PolicyController; 
use appstore\models\AppstoreModel; 
use skyapp\controllers\wsdlObject\Apps;
 

class AppStoreController extends PolicyController {
	
	public function actions(){
		return array(
				"Wsdl"=>array(
						"class"=>"Sky\\base\\WebServiceAction"
				),
		);
	}
	
	
	public $params = '';
	
	/*
	 * 获取 分类
	 */
	public function actionListCategory(){
		return AppstoreModel::getCategory();
	}
	
	
	/*
	 * 获取policy 
	 */
     private function getPolicy(){
     	return $this->getPolicyValue($this->params);
     }
     
	/*
	 * 获取分类列表
	 */
	public function actionListApp($productTypeId,$page,$pagesize)
	{
		$count = AppstoreModel::getAppCount($this->getPolicy(),$productTypeId);
		$result =  AppstoreModel::getApp(
				     $this->getPolicy(), 
				     $productTypeId, 
				     $page, 
				     $pagesize
				 );
		return array('total'=>$count, 'result'=>$result);
	}
	 
	/*
	 * 获取搜索,按名称 搜索
	 */
	public function actionListSearchApp($appname,$page,$pagesize){
		$count = AppstoreModel::searchAppCount(
				   $this->getPolicy(),
				   $appname
				 );
		$result =  AppstoreModel::searchApp(
				      $this->getPolicy(),
				      $appname,
				      $page,
				      $pagesize
		            );
		return array('total'=>$count, 'result'=>$result);
	}
	 
	
	/*
	 * 按应用产品id获取app详细信息 
	 */
	public function actionShowDetail($Product_ID){
		return AppstoreModel::getAppDetail($Product_ID);
	}
	
	/*
	 *  按包名取app详细信息
	 *  
	 */
	public function actionShowDetailByCondition($packageName){
		$packageName = "'".$packageName."'";
		return AppstoreModel::getSpecialAppDetail($packageName);
	}
	
	
}