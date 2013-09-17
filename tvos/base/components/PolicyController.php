<?php
namespace base\components;

use Sky\base\Controller;
use Sky\Sky;
use base\models\PolicyModel;
class PolicyController extends Controller{

	/**
	 * 获取策略
	 * 根据方法名（Controller/Action），机芯，机型，平台，尺寸，mac,参数,系统版本
	 * @param string $params
	 * @return string 如果能获取到策略值的话返回非空字符串，否则获取失败返回''
	 */
	public function getPolicyValue($params){
		$session=Sky::$app->getSession();
		if (!$session->illegalSession()) {
			$tvinfo=$session->getTVInfo();
			if (count($tvinfo)) {
				$policyAction=ucfirst($this->id).'/'.$this->action->id;
				$platform = explode("|", $tvinfo['platform']);
				return PolicyModel::querypolicy(//'8A12','E730','AML-M6',''
						$policyAction,
						$tvinfo['chip'],
						$tvinfo['model'],
						$platform[0],
						$tvinfo['screen_size'],
						$tvinfo['dev_mac'],
						$params,
						$tvinfo['system_version']
				);
			}
		}
		return '';
	}

}