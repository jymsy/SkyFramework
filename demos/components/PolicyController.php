<?php
namespace demos\components;

use Sky\base\Controller;
use Sky\Sky;
use demos\models\PolicyModel;
/**
 * 策略控制Controller
 * @author Jiangyumeng
 *
 */
class PolicyController extends Controller{
	
	/**
	 * 通过session获取平台
	 * @return multitype:
	 */
	public function getPolicyValue($params,$action){
// 		$session=Sky::$app->getSession();
// 		if (!$session->illegalSession()) 
		{
// 			$tvinfo=$session->getTVInfo();
			$tvinfo=Sky::$app->tvinfo->getTVInfo();
			if ($action==='') {
				$action=ucfirst($this->id).'/'.$this->action->id;
			}

			if (count($tvinfo)) {
				$platform = explode("|", $tvinfo['platform']);
				return PolicyModel::querypolicy(//'8A12','E730','AML-M6',''
						$action,
						$tvinfo['chip'],
						$tvinfo['model'],
						$platform[0],
						$tvinfo['screen_size'],
						$tvinfo['dev_mac'],
						$params,
						$tvinfo['system_version']
				);
			}else{
				return PolicyModel::querypolicy(//'8A12','E730','AML-M6',''
						$action,'','','','','',$params,'');
			}
		}
		return '';
	}
}