<?php
namespace demos\controllers;

use Sky\base\Controller;
use Sky\Sky;
use Sky\utils\PushMsg;
use Sky\utils\PushServer;
/**
 * 南京所微信推送
 * @author Jiangyumeng
 *
 */
class PushController extends Controller{
	public $cmd=1000;
	public $svctype=102;
	public $ver="1.0";
	public $pushServer;
	public $pushPort;
	
	public function actionToUser($uid, $msg)
	{
// 		PushServer::$svctype=100;
		$addr=PushServer::getHost(PUSH_SERVER);
		echo $addr;
		if ($addr=='fail') {
			throw new \Exception('push server has gone away.');
		}
		$pos=strpos($addr, ':');
		$this->pushServer=substr($addr, 0,$pos);
		$this->pushPort=substr($addr, $pos+1);
		$push=Sky::$app->push;
		
// 		$push->pushServer=$this->pushServer;
		$push->pushServer='121.199.45.31';
// 		$push->pushPort=$this->pushPort;
		$push->pushPort=50034;
		$push->initMsg(PushMsg::SINGLE,10001,0,'TC_2013_Push');
		$push->addUser($uid);
		$msg="delivery#2002#[{\"delivery_u_nickname\":\"\u626c\u5dde\u5e7f\u7535\",\"delivery_u_icon\":\"\",\"delivery_f_nickname\":\"\u626c\u5dde\u5e7f\u7535\",\"dn_res_title\":\"nanjing\",\"dn_res_content\":\"$msg\",\"dn_res_type\":\"txt\",\"dn_res_url\":\"\",\"dn_exist_thumbnail\":\"0\",\"dn_direct_play\":\"0\"}]#";
		$push->addMsg(time(), rand(0,100000),$msg);
		return $push->push();
	}
	
	protected function getDeviceId()
	{
		$ip=gethostbyname(HostName);
		$deviceid=ip2long($ip);
		if ($deviceid===-1) {
			return sprintf('%u',ip2long('127.0.0.1'));
		}else{
			return sprintf('%u', $deviceid);
		}
	}
}