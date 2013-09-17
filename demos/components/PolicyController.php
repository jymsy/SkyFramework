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

	/* 
	 * @see \Sky\base\Controller::beforeAction()
	 */
// 	public function beforeAction($action){
// // 		$policyAction=ucfirst($this->id).'/'.$action->id;
// 		$policyAction=ucfirst($this->id).'/'.$this->action->id;
// 		echo $policyAction;
// // 		if(in_array($policyAction,Sky::$app->params['policyActions'])){
// // 			if(is_array($policy=$this->getPolicyValue()) && count($policy)){			
// // 				$rawCondition=json_decode($_REQUEST['condition'],true);
// // 				$_REQUEST['condition']=json_encode(array_merge($rawCondition,$policy[0]));
// // 			}
// // 		}
// 		return true;
// 	}
	
	/**
	 * 通过session获取平台
	 * @return multitype:
	 */
	public function getPolicyValue($params){
		$session=Sky::$app->session;
		if (!$session->illegalSession()) {
			$tvinfo=$session->getTVInfo();
			if (count($tvinfo)) {
				$policyAction=ucfirst($this->id).'/'.$this->action->id;
				return PolicyModel::querypolicy(//'8A12','E730','AML-M6',''
						$policyAction,
						$tvinfo['chip'],
						$tvinfo['model'],
						$tvinfo['platform'],
						$tvinfo['screen_size'],
						$params
				);
			}
		}
		return '';
	}
}