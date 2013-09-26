<?php
namespace appstore\controllers;

use Sky\Sky; 

use Sky\base\Controller;
use base\components\PolicyController; 
use appstore\models\AppstoreModel; 
 

class WebAppController extends PolicyController {
	
	public function actions(){
		return array(
				"Wsdl"=>array(
						"class"=>"Sky\\base\\WebServiceAction"
				),
		);
	}
	
	/*
	 *  webapp网页获取推荐列表应用
	*/
	public function actionRecommonApp(){
		$packageArr = array(
				
				//电视派安卓版（原优视）
				 
				"com.skyworth.skyclientcenter"=>array(
						"sortid"=>3,
						"xpush"=>"_hmt.push(['_trackEvent', 'nav', 'click', 'ys_a'])",
						"_id"=>"ys_a",
						"id"=>"",
						"xclass"=>""
						
				),
			 
				//玩转天赐（Android）
				"com.skyworth.directions"=>array(
						"sortid"=>2,
						"id"=>"tc_d",
						"_id"=>"tc_b",
						"xclass"=>"mid-ul-li",
						"xpush"=>"_hmt.push(['_trackEvent', 'nav', 'click', 'tc_d'])",
				),
				
				//传屏
				"com.skyworth.remotescreen.player"=>array(
						"sortid"=>1,
						"_id"=>"ys_b",
						"xclass"=>"",
						"id"=>"download",
						"xpush"=>"_hmt.push(['_trackEvent', 'nav', 'click', 'ys_b'])",
				)
				
		);
		$packageNameArr = array_keys($packageArr); 
		$packageName = "'".join("','", $packageNameArr)."'";
		$arr =  AppstoreModel::getSpecialAppDetail($packageName);
		//以package作为键名，且合并上面的排序配置
		$temp = array();
		foreach($arr as $key=>$value){
			$value["sortid"] = $packageArr[$value['product_bag_name']]['sortid'];
			$value["xpush"] = $packageArr[$value['product_bag_name']]['xpush'];
			$value["_id"] = $packageArr[$value['product_bag_name']]['_id'];
			$value["id"] = $packageArr[$value['product_bag_name']]['id'];
			$value["xclass"] = $packageArr[$value['product_bag_name']]['xclass'];
			$temp[]=$value;
		}
		
	    $arr = $this->array_sort($temp,"sortid",'desc'); 
		echo Sky::$app->end(json_encode($arr)); 
	}
	
	/*
	 * 按数组某一个键值排序 
	 */
	public function array_sort($array,$keys,$type='asc'){
		if(!isset($array) || !is_array($array) || empty($array)){
			return '';
		}
		if(!isset($keys) || trim($keys)==''){
			return '';
		}
		if(!isset($type) || $type=='' || !in_array(strtolower($type),array('asc','desc'))){
			return '';
		}
		$keysvalue=array();
		foreach($array as $key=>$val){
			$val[$keys] = str_replace('-','',$val[$keys]);
			$val[$keys] = str_replace(' ','',$val[$keys]);
			$val[$keys] = str_replace(':','',$val[$keys]);
			$keysvalue[] =$val[$keys];
		}
		asort($keysvalue); //key值排序
		reset($keysvalue); //指针重新指向数组第一个
		foreach($keysvalue as $key=>$vals) {
			$keysort[] = $key;
		}
		$keysvalue = array();
		$count=count($keysort);
		if(strtolower($type) != 'asc'){
			for($i=$count-1; $i>=0; $i--) {
				$keysvalue[] = $array[$keysort[$i]];
			}
		}else{
			for($i=0; $i<$count; $i++){
				$keysvalue[] = $array[$keysort[$i]];
			}
		}
		return $keysvalue;
	}
}