<?php
namespace res\controllers;

use Sky\base\Controller;
use base\components\PolicyController;
use res\models\CollectionModel;

class MemberController extends PolicyController {
	
	const InfoUrlPrefix = INFO_URL_PREFIX;
	
	public function actions() {
		return array(
				"Wsdl"=>array(
						"class"=>"Sky\\base\\WebServiceAction"
				),
		);
	}
	
	public function actionAddMemberCollect($u_id,$jsonConStr){
		$result = CollectionModel::addMemberCollect($u_id, $jsonConStr);
		
		return $result;
	}
	
	public function actionDeleteMemberCollect($u_id,$url){
		$result = CollectionModel::deleteMemberCollect($u_id, $url);
	
		return $result;
	}
	
	public function actionDeleteMemberCollectAll($u_id,$type){
		$result = CollectionModel::deleteMemberCollectAll($u_id, $type);
	
		return $result;
	}
	
	public function actionGetMemberCollect($u_id,$type,$page_size,$page_index){
		$result = CollectionModel::getMemberCollect($u_id, $type, $page_size, $page_index);
	
		return $result;
	}
	
}