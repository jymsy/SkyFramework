<?php
namespace base\upgrade\controllers;

use Sky\Sky;

use base\upgrade\models\UpgradeClientModel;
use base\components\PolicyController;
use Sky\base\Controller;
use base\user\models\UserModel;
use base\user\models\BaseDevice;
use Sky\db\DBCommand;
use base\components\IPAnalyze;


class UpgradeActionController extends PolicyController {

	public function actions(){
		return array(
				"Wsdl"=>array(
						"class"=>"Sky\\base\\WebServiceAction"
				),
		);
	}
	
	/*
	 *  获取 iptv升级包
	*/
	public function actionGetIPTVUpgradeInfo($iptv_package_version, $iptv_package_version_code){
		    
		 $ip = \Sky\Sky::$app->request->getUserHostAddress(); 
	 
		 $i = new IPAnalyze();
		 $addr = $i->get_ip($ip);
		if($addr!=''){
			$session=Sky::$app->session;
			//是不是非法的session
			if (!$session->illegalSession()){
				$tvinfo=$session->getTVInfo();
				$area_name = end(explode(",",$addr));
				$result = UpgradeClientModel::getIPTVUpgradeInfo(
						$iptv_package_version,
						$area_name,
						$tvinfo['model'], 
						$tvinfo['chip']
				);
				return $result;
			}
			return "Illegal Session";
		}
		return "City is empty";
	}
	
	/*
	 *  获取iptv包由城市和session取得
	 */
	public function actionGetIPTVUpgradeInfoToHtml($area_name){
		$session=Sky::$app->session;
		 //检查是否为非法session
		if (!$session->illegalSession()){
			$tvinfo=$session->getTVInfo(); 
			$result = UpgradeClientModel::getIPTVUpgradeInfo2(
					$area_name,
					$tvinfo['model'],
					$tvinfo['chip']
			);
			if($result){
				return $result[0];
			}
			return null;
		}
		return "Illegal Session"; 
	}


	public function actionGetUpgradeInfo($mac){
		$core_style = "";
		$core_chip = "";
		$CurVersion = "0";
		$appName = 'system';
		$tv = BaseDevice::getDeviceInfoByMac($mac);
		if(count($tv)==0)
			return '';
		if(isset($tv['chip'])){
			$core_chip = $tv['chip'];
		}
		if(isset($tv['model'])){
			$core_style = $tv['model'];
		}
		if(isset($tv['system_version'])){
			$CurVersion = $tv['system_version'];
		}
		$result = UpgradeClientModel::getUpgradeVesionInfo($core_style, $core_chip, $mac, $appName, $CurVersion);
		return $result;
	}

	public function actionGetModuleUpgradeLists($mac){
		$upgrade_id = UpgradeClientModel::getUpgradeIdByMac($mac);
		$result = UpgradeClientModel::getModuleUpgradeLists($upgrade_id,$mac);
		if(count($result)==0)
			return '';
		return $result;
	}

	public function actionGetDtvUpgradeInfo($dtv_code,$dtv_version,$hw_version){
		$result = UpgradeClientModel::getDtvUpgradeInfo($dtv_version, $dtv_code,$hw_version);
		if(count($result)==0)
			return '';
		return $result;
	}
}