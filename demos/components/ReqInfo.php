<?php
namespace demos\components;

use Sky\base\Component;
use Sky\Sky;
/**
 * 统计接口执行时间
 * 为了减轻负荷，并不是每次请求都统计。
 * 根据设置的{@link Sky::$app->profProbability}的值决定采样率。
 * 
 * @author Jiangyumeng
 *
 */
class ReqInfo extends Component{
	/**
	 * @var string 本地保存采样结果的文件夹路径
	 */
	public $logDir='';
	
	/* 
	 * @see \Sky\base\Component::init()
	 */
	public function init()
	{
		Sky::$app->attachEventHandler('onEndRequest',array($this,'process'));
	}
	
	/**
	 * 执行采样
	 */
	public function process()
	{
		if(mt_rand(1, Sky::$app->profProbability) == 1 && is_dir($this->logDir))
		{
			$execTime=Sky::getLogger()->getExecutionTime()*1000*1000;
			$pos=strpos($execTime, '.');
			
			$route=$_REQUEST[Sky::$app->getUrlManager()->routeVar];
			$msg=substr($execTime, 0,$pos).' '.$route;
			$fileName=date('Hi');

			if(($fp=@fopen($this->logDir.$fileName, 'a'))===FALSE)
			{
				return false;
			}
			@flock($fp,LOCK_EX);
			@fwrite($fp, "$msg\n");
			@flock($fp,LOCK_UN);
			@fclose($fp);
		}	
	}
}