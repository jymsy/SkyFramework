<?php
namespace res\controllers;

use Sky\base\Controller;
use Sky\Sky;
use res\models\DeserviceModel;
/**
 * 多屏互动服务
 * 
 * 在手机打开影视推送站点，可以获取到同局域网内的电视列表。
 * 用户可以将手机上的网站投射到电视机上。
 * 从技术的角度，电视机将搭建局域网的Http Server，
 * 并将本地的内网IP发送到服务器，记录到它的公网IP下。
 * 手机推送站点获取电视的内网IP列表，在用户选取某台终端后，
 * 即可访问它运行的监听内网IP的http server。
 * 
 * @author Jiangyumeng
 *
 */
class DeServiceController extends Controller{
	/**
	 * 注册多屏互动服务
	 * 
	 * @param string $devID 设备ID。如果是电视机，则为有线网卡的mac。
	 * @param string $devName 设备名称，用户选择设备时显示。TV缺省参考为“机型-Mac”
	 * @param string $proto 支持的多屏互动协议，以“|”分隔。如：“http”
	 * @param string $intraIP 内网IP。如果没有，传空。
	 * @param unknown $token 内网信息标识。
	 * 如果是wifi，则值为“wifi:名称(即SSID)”，
	 * 用于设备分组。当公网IP下有多个局域网时，
	 * 可以通过该值优化扫描顺序，提升扫描速度。
	 * @return integer 注册成功1或2，否则0
	 */
	public function actionRegister($devID, $devName, $proto, $intraIP, $token, $uname, $uid)
	{
		$wanIp=Sky::$app->getRequest()->getUserHostAddress();
		return DeserviceModel::insertDeservice($devID, $devName, $proto, 
				$intraIP, $wanIp, $token, $uid, $uname);
	}
	
	/**
	 * 获取同公网IP下的已注册多屏互动服务的设备列表。
	 * @param string $wanIP 公网ip
	 * @return array 公网IP下的已注册多屏互动服务的设备列表
	 */
	public function actionGetDevices($wanIP)
	{
		return DeserviceModel::getDeserviceList($wanIP);
	}
	
	/**
	 * @param string $devID 设备ID。如果是电视机，则为有线网卡的mac。
	 * @return integer 删除成功大于0，否则0
	 */
	public function actionDelDevice($devID)
	{
		return DeserviceModel::deleteDeserviceByDevid($devID);
	}
}