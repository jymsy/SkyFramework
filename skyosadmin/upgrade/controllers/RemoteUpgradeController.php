<?php 
namespace upgrade\controllers;
use Sky\utils\Ftp;
use Sky\Sky;
use Sky\base\Controller;
use upgrade\models\UpgradeModel; 
use skyosadmin\components\PolicyController; 
use skyosadmin\components\ApkInfo;
 
 
class RemoteUpgradeController extends PolicyController {

	/*
	 *  远程接口基本访问地址
	*/
	private $remote_url = "";
	/*
	 *  apk包本地目录
	*/
	private $apkroot = "";
	/*
	 *  apk 图标本地目录
	*/
	private $iconroot = "";
	
	/*
	 *  上传主目录
	*/
	private $uploadroot = "";
	
	/*
	 *  网站根目录,php脚本主目录
	*/
	private $root = "";
	
	/*
	 * 上传压缩包目录
	*/
	private $ziproot = "";
	
	/*
	 * 在线升级包目录
	*/
	private $upgraderoot = "";
	
	/*
	 * 远程ftp 主目录
	*/
	private $rs_root = "";
	
 
	
	public function __construct(){
		$this->remote_url = "http://".RemoteHostName."/Framework/skyosadmin/index.php?";
		$this->apkroot = UPLOADROOT.APKROOT; //结果 = /rs/apk
		$this->iconroot = UPLOADROOT.ICONROOT; //结果 = /rs/icon
		$this->root = ROOT; //结果= /data/cloudservice
		$this->ziproot = UPLOADROOT.ZIPROOT; //结果 = /rs/zip
		$this->upgraderoot = UPLOADROOT.UPGRADEROOT;  // 结果 = /rs/onlineupgrade
		$this->rs_root = RS_ROOT; // 结果 = /data/www
	}
	
	//获取远程大包列表接口
	public function actionRemoteUpgrade(){
		$_REQUEST['_r'] = "upgrade/Upgrade/RemoteUpgrade";
		 
		$_REQUEST['area_code'] = OPERATOR_CODE;
		$parameter = http_build_query($_REQUEST);
		$url = $this->remote_url.$parameter; 
		$curl = \Sky\Sky::$app->curl->get($url);
		$data = json_decode($curl);
		foreach( $data->rows as $key=>$value){
			$value->islocal = $this->isLocalUpgrade($value->md5);
		}
		return $data;
	}
	
	
	
	/*
	 *  大包
	 * 判断远程包是否在本地已存在，存在显示”已经导入“ ，不存在显示”导入应用“
	*/
	public function isLocalUpgrade($md5){
		if(UpgradeModel::checkPackageExistByMd5($md5)){
			return 1;
		}else{
			return 0;
		}
	}
	
	
	 
	
	/*
	 * 获取 一个升级大包的完整信息，大包信息
	*/
	public function getOnePackage( $id ){
		$arr = array(
				'_r'=>"Upgrade/Upgrade/GetSysUpgradeById",
				"id"=>$id,
				"ws"=>""
		);
		$url = $this->remote_url.http_build_query($arr);
		$curl = \Sky\Sky::$app->curl->get($url);
		$data = json_decode($curl);
		return $data;
	}
	
	/*
	 * 导入操作流程
	*/
	public function actionImportPackage(){
		//解析json
		$temp = json_decode($_REQUEST['id']); //这个是id json值
		$PackageArr = (array) $temp;
		//准备入库数据 
		   //取下载地址$PackageArr['download_url']
		   //先入库后下载
			 $id = $this->add($PackageArr);
			 $zipUrl = $this->downloadRemote($PackageArr['download_url'],$PackageArr['md5'],$PackageArr['upgrade_version']);
			 if($zipUrl){
			 	 //更新download_url字段，该变量为ftp值
			 	UpgradeModel::updateSysUpgradeDownloadUrl($id,$zipUrl);
			 	return 1;
			 }
		return 0;
	}
	
	
	//添加到本地升级方法
	public function add( $addArr ){
		/*
		[upgrade_id] => 15
		[core_style] => HDP100E
		[core_chip] => 2S10
		[upgrade_version] => 13004020
		[inc_version] => 0
		[download_url] => http://42.121.119.71/onlineupgrade/data/2S10_HDP100E_V013.004.020.zip
		[mac_start] => 000000000000
		[mac_end] => ffffffffffff
		[md5] => 972e5d49d0c1695b274e858e2f90a3a2
		[package_type] => 0
		[package_owner] => system
		[area] => 000000
		[is_import] => 1
		[filesize] => 191932824
		[platform] =>
		[bag_type] =>
		[screen_size] =>
		[thirdparty_info] =>
		[desc] => full:013004020_inc:0000000_area:000000
		[introduce_page] =>
		*/
		
		
		  $arr = array(
				'core_style'=>$addArr['core_style'],
				'core_chip'=>$addArr['core_chip'],
				'init_version'=>$addArr['init_version'],
				'final_version'=>$addArr['final_version'],
				'download_url'=>$addArr['download_url'],
				'mac_start'=>'000000000000',
				'mac_end'=>'000000000000',
				'md5'=>$addArr['md5'],
				'package_type' =>$addArr['package_type'],
				'package_owner'=>$addArr['package_owner'],
				'area'=>$addArr['area'],
				'is_import'=>$addArr['is_import'],
				'filesize'=>$addArr['filesize'],
				'platform'=>$addArr['platform'],
				'bag_type'=>$addArr['bag_type'],
				'screen_size'=>$addArr['screen_size'],
				'thirdparty_info'=>$addArr['thirdparty_info'],
				'desc' =>$addArr['desc']
		);
		return UpgradeModel::insertSysUpgrade($arr);
	}
	
	
	/*
	 * 下载远程url zip包,
	 * @dirName 为目录名称
	 */
	
	function downloadRemote($url,$md5,$dirName){
		$downList=array(); 
		$zip=pathinfo($url);
		$localpath = ROOT.$this->upgraderoot."/".$zip['basename']; 
		$downList = array(
				$url=>$localpath //从远程下载到本地
		);
         
		//判断如果是同一个ftp目标文件
         $urlArr = parse_url($url);
         if($urlArr['host']==RS_HostName){
         	return $url;
         }
		parent::downloadfileWget($downList); 
		
		/*
		 $this->remote_url = "http://".HostName."/Framework/skyosadmin/index.php?";
		$this->apkroot = UPLOADROOT.APKROOT; //结果 = /rs/apk
		$this->iconroot = UPLOADROOT.ICONROOT; //结果 = /rs/icon
		$this->root = ROOT; //结果= /data/cloudservice
		$this->ziproot = UPLOADROOT.ZIPROOT; //结果 = /rs/zip
		$this->upgraderoot = UPLOADROOT.UPGRADEROOT;  // 结果 = /rs/onlineupgrade
		$this->rs_root = RS_ROOT; // 结果 = /data/www
		 */
		if($this->check_filemd5($localpath, $md5)){
			
			$localZipRoot=$this->upgraderoot."/"; 
			
			//ftp上传后的地址 
			$zipUrl = "http://".RS_HostName.$localZipRoot.$dirName.'/'.$zip['basename']; 
			
			//ftp上传列表
			$uploadList = array(
					$localpath=>$this->rs_root.$this->upgraderoot."/".$dirName.'/'.$zip['basename']
			);
		 
			//ftp上传
			if(parent::uploadFtp($uploadList))
			{
				//删除临时文件
				//parent::delete_folder($localpath); 
				return $zipUrl;
			}
			
			return false;
		}else{
			return false;
		}
	}
	
	/*
	 *  判断本地的包是否存在，如果下载完了就上传，
	 */
	function check_filemd5($filepath,$md5){
		if(file_exists($filepath)){
			$newmd5=md5_file($filepath);
			if($newmd5==$md5){
				return true;
			}
		}
		return false;
	}
	
	
	
	 
}