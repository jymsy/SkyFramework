<?php
namespace appstore\controllers; 
use Sky\Sky;
use Sky\base\Controller;
use appstore\models\SkyAppstoreModel;
use skyosadmin\components\PolicyController; 
use skyosadmin\components\ApkInfo;
use Sky\utils\Curl;

class RemoteAppStoreController extends PolicyController {
	private $page;
	private $limit;
	private $sidx;
	private $sord;
	private $count;
	private $total_pages;
	private $start;
	private $_search;
	private $oper;
	private $searchOn;
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
	
	
 
	
	public function __construct(){
       $this->remote_url = "http://".HostName."/Framework/skyosadmin/index.php?";
       $this->apkroot = UPLOADROOT.APKROOT; //结果 = /rs/apk
       $this->iconroot = UPLOADROOT.ICONROOT; //结果 = /rs/icon
       $this->root = ROOT; //结果= /data/cloudservice 
	}
	
	 //获取远程列表接口
	public function actionRemoteApp(){
		$_REQUEST['_r'] = "appstore/SkyApp/SkyApp";
		$parameter = http_build_query($_REQUEST); 
		$url = $this->remote_url.$parameter;
		$curl = \Sky\Sky::$app->curl->get($url);
		$data = json_decode($curl);
  		 foreach( $data->rows as $key=>$value){
  		 	$value->islocal = $this->isLocalApp($value->product_bag_name);
 		 }
		return $data;
	}
	
  
	
	/*
	 * 判断远程包是否在本地已存在，存在显示”已经导入“ ，不存在显示”导入应用“
	 */
	public function isLocalApp($ProductBagName){
		if(SkyAppstoreModel::checkAppExistByBagName($ProductBagName)){
			return 1;
		}else{
		    return 0;
		}
	}
	
 
	  
	
	/*
	 * 获取 一个app的完整信息
	 */
	public function getOneApp( $Product_ID ){
		$arr = array(
		   '_r'=>"appstore/skyApp/GetAppDetailById",
		   "id"=>$Product_ID,
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
	public function actionImportApp(){
		$Product_ID = $_REQUEST['id'];
		//取记录
	 
		$remoteAppArr = (array)$this->getOneApp( $Product_ID ); 
		$baseApp = (array)$remoteAppArr[0]; 
		$appTypeArr = array_splice($remoteAppArr,1); 
		$typeid = $appTypeArr[0]->product_type_id; 
		$platform = array();
		foreach($appTypeArr as $k=>$v){
			$platform[]=$v->platform_info;
		}
		/*
		$this->apkroot = UPLOADROOT.APKROOT; //结果 = /rs/apk
		$this->iconroot = UPLOADROOT.ICONROOT; //结果 = /rs/icon
		$this->root = ROOT; //结果= /data/cloudservice
		*/
		
		$localapkroot=$this->apkroot."/";
		$localpicroot=$this->iconroot."/";
		$localpicfile=$this->root.$localpicroot;
		$localapkfile=$this->root.$localapkroot;
		
		
		//比较版本和是否存在
		$compareArr = array(
				'product_bag_name'=>$baseApp['product_bag_name'],
				'product_version_code'=>$baseApp['product_version_code']
		);
		$isLocalApp = SkyAppstoreModel::compareApp($compareArr);
		
		if($isLocalApp){
			
			$apk = pathinfo($baseApp['download_url']);
			$icons = pathinfo($baseApp['product_small_show']);
			$iconb = pathinfo($baseApp['product_big_show']);
			
			//存在则开始下载
			$downLists=array(
					$baseApp['download_url']=>$localapkfile.$apk['basename'],
					$baseApp['product_small_show']=>$localpicfile.$icons['basename'],
					$baseApp['product_big_show']=>$localpicfile.$iconb['basename'] 
			);
			 
			
			parent::downloadfileWget($downLists); 
			
			$flag = time();
			//ftp上传后的地址
			$iconUrl = "http://".RS_HostName.$localpicroot.$flag.$icons['basename'];
			$apkUrl = "http://".RS_HostName.$localapkroot.$flag.$apk['basename'];
			
			//ftp上传列表
			$uploadList = array(
				 $localpicfile.$icons['basename']=>RS_ROOT.$localpicroot.$flag.$icons['basename'],
			     $localapkfile.$apk['basename']=>RS_ROOT.$localapkroot.$flag.$apk['basename']
			);
		 
			//ftp上传
			if(parent::uploadFtp($uploadList))
			{
				//删除临时文件
				unlink($localpicfile.$icons['basename']);
				unlink($localapkfile.$apk['basename']);
			}
			
			//准备入库数据
			$arr = array(
			  "product_owner_id"=>$baseApp["product_owner_id"],
			  "product_owner_name"=>$baseApp["product_owner_name"],
			  "product_name"=>$baseApp["product_name"],
			  "product_bag_name"=>$baseApp["product_bag_name"],
			  "product_score"=>$baseApp["product_score"],
			  "product_is_available"=>$baseApp["product_is_available"],
			  "product_sales_num"=>$baseApp["product_sales_num"],
			  "product_download_num"=>$baseApp["product_download_num"],
			  "rep_desc"=>$baseApp["rep_desc"],
			  "control_type"=>$baseApp["control_type"],
			  "product_big_show"=>$iconUrl,
			  "product_small_show"=>$iconUrl,
			  "product_version"=>$baseApp["product_version"],
			  "product_version_code"=>$baseApp["product_version_code"],
			  "product_size"=>$baseApp["product_size"],
			  "product_add_time"=>$baseApp["product_add_time"],
			  "product_language"=>$baseApp["product_language"],
			  "download_url"=>$apkUrl,
			  "developer"=>$baseApp["developer"],
			  "vs_minsdkversion"=>$baseApp["vs_minsdkversion"],
			  "vs_note"=>$baseApp["vs_note"],
			  "developer"=>$baseApp["developer"],
			  "platform"=>$platform,
			  "type_id"=>$typeid		
			); 
			
			return $this->add($arr); 
			//删除临时文件
			//插入本地库 (资源地址)
			//返回相关信息 
		}
	}
	

	//添加到本地应用方法
	public function add( $addArr ){
	
		$platformTypes = $addArr['platform'];
	
		$ProductBagName = $addArr["product_bag_name"];
			
		$typeid = $addArr['type_id'];
	
		$arrVersion = array(
				"product_big_show"=>$addArr["product_big_show"],
				"product_small_show"=>$addArr["product_small_show"],
				"product_version"=>$addArr['product_version'],
				"product_version_code"=>$addArr['product_version_code'],
				"product_size"=>$addArr['product_size'],
				"product_language"=>"zh",
				"download_url"=>$addArr['download_url'],
				"developer"=>$addArr['developer'],
				"vs_note"=>$addArr['vs_note']
	
		);
	
		$arrItem = array(
				"ProductOwnerId"=>$addArr["product_owner_id"],
				"ProductOwnerName"=>$addArr["product_owner_name"],
				"ProductName"=>$addArr["roduct_name"],
				"ProductSaleCCM"=>0,
				"ProductBagName"=>$addArr["product_bag_name"],
				"ProductInstallationSite"=>"0",
				"ProductScore"=>$addArr["product_score"],
				"RepDesc"=>$addArr["rep_desc"],
				"ProductisAvailable"=>0,
				"ProductSalesNum"=>0,
				"productDownloadNum"=>0,
				"controllerType"=>$addArr["controller_type"]
		);
	
		//应用入库
		//通过id,分类入库
		$productId = SkyAppstoreModel::checkAppExistByBagName($ProductBagName);
		if($productId)
		{
			$arr = $arrVersion + array("product_id"=>$productId);
			$a = SkyAppstoreModel::getAppLateVersion($productId);
			$b = $arrVersion["product_version_code"];
			//如果当前版比原有版高不添加应用
			if($a>=$b)
			{
				//表示不能添加比原有版本底的
				return 11;
			}
	
			$ver_id = SkyAppstoreModel::addAppVersion($arr);
			if($ver_id){
				//返回1
				return SkyAppstoreModel::updateAppVid($productId, $ver_id);
			}else{
				//添加addAppVersion失败
				return 22;
			}
	
		}else{
			$productId = SkyAppstoreModel::insertAppItem($arrItem);
	
			if($productId){
					
				$arr = $arrVersion+array("product_id"=>$productId);
					
				$ver_id = SkyAppstoreModel::addAppVersion($arr);
					
				if($ver_id){
					SkyAppstoreModel::updateAppVid($productId, $ver_id);
				}else{
					//添加addAppVersion失败
					return 22;
				}
					
				//多平台，单分类入库
				foreach ($platformTypes as $platformtype){
	
					SkyAppstoreModel::insertCategoryAppItem($platformtype,$typeid,$productId);
				} 	
				return 1;
			}else{
				//添加AppItem失败
				return 33;
			}
	
		}
	}
	 
	
	public function actionTest(){
		 $path = "/data/cloudservice/rs/apk/201304121853423210.apk"; 
		 $ftp = Sky::$app->ftp; 
		 return $ftp->put( $path,'/data/www/rs/1.apk'); 
	}
	
}