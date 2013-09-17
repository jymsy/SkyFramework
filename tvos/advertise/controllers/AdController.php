<?php
namespace advertise\controllers;

use base\components\PolicyController;

use Sky\Sky; 
use Sky\base\Controller;
// use base\components\PolicyController;
use base\components\SkySession;

class AdController extends Controller{

	public function actions() {
		return array(
				"Wsdl"=>array(
						"class"=>"Sky\\base\\WebServiceAction"
				),
		);
	}
	
	public function actionGetAd($scene){		
		if (!defined('CP_SWITCH') || CP_SWITCH != "COOCAA")	{			
			return null;
		}else{
			$coocaaId = self::getCipherCoocaaId();
			//$coocaaId = "00DC144D5899D9AE310AB867A68A1795CFBC4DE2A570024E49646ED073649441EF17790076C7CB874433A67955FA9D4D";
			echo "【".$coocaaId."】";
			$ccAd = CoocaaAd::getInstance($coocaaId);
			$list = array();
			$ccAdSets = $ccAd->getAdInfo($scene, "", "");
			print_r($ccAdSets);
			if ($ccAdSets){
				foreach ($ccAdSets AS $v){
					$cl = self::generateAdRow($v, $ccAd,TRUE);
					if (isset($cl)){
						$list[] = $cl;
					}
				}
			}
		
			return $list;
		}
	}
	
	private static function getCoocaaId(){
		$session = Sky::$app->session;
		$tvInfo = $session->getTVInfo();		
		$mac = $session[SkySession::MAC];
		if (isset($tvInfo) && isset($mac)){
			return $tvInfo['barcode']."-".$mac;
		}else {
			return "37E72RD-M000079-Z110215-5B21A-001A9AE36806";
		}
	}
	
	private static function getCipherCoocaaId(){
		$coocaaId = self::getCoocaaId();
		echo "coocaaId:".$coocaaId."<br>";
		$key = "8005e1d84149a2a0";
		$cry = new CryptAES();
		return strtoupper($cry->encrypt($coocaaId,false));
	}
	
	private static function &generateAdRow(&$coocaaAdArr,&$ccApp,$incMode = FALSE){
		$importantKeys = array(
				'ADR_Product','ADR_BeginTime','ADR_EndTime','ADR_Url','ADR_Md5Format','ADS_VIPAdv'
		);
		foreach ($importantKeys AS $ikey){
			if (!array_key_exists($ikey, $coocaaAdArr)){
				$result = null;
				return $result;
			}
		}
		$cl = new \stdClass();
		$v = &$coocaaAdArr;
		$cl->ad_name = $v['ADR_Product'];
		$cl->ad_begin_time = $v['ADR_BeginTime'];
		$cl->ad_end_time = $v['ADR_EndTime'];
		$cl->ad_url = $v['ADR_Url'];
		$cl->ad_md5 = $v['ADR_Md5Format'];
		$cl->adOpen = $v['ADS_VIPAdv'];
		return $cl;
	}
}

?>